<?php if (!defined('FW')) die('Forbidden');

/**
 * Test if HTTP Loopback Connections are enabled on this server
 */
function fw_ext_backups_loopback_test() {
	/** @var FW_Extension_Backups $backups */
	$backups = fw_ext('backups');

	$url = site_url( 'wp-admin/admin-ajax.php' );

	$response = wp_remote_post($url, array(
		'blocking'  => true,
		'sslverify' => false,
		'body' => array(
			'action' => $backups->_get_test_ajax_action(),
		),
	));

	$error = false;

	if (is_wp_error($response)) {
		$error = $response->get_error_message();
	} elseif(200 !== ($response_code = intval(wp_remote_retrieve_response_code($response)))) {
		$error = sprintf(esc_html__('Response code: %d', 'fw'), $response_code);
	} elseif (
		isset($response['body'])
		&&
		($response_json = json_decode($response['body'], true))
		&&
		isset($response_json['success'])
		&&
		true === $response_json['success']
	) {
		return false;
	} else {
		$error = __('Invalid JSON response', 'fw');
	}

	return str_replace(
		array('{url}', '{error}'),
		array($url, $error),
		__(
			'HTTP Loopback Connections are not enabled on this server. ' .
			'If you need to contact your web host, '
			. 'tell them that when PHP tries to connect back to the site '
			. 'at the URL `{url}` and it gets the error `{error}`. '
			. 'There may be a problem with the server configuration (eg local DNS problems, mod_security, etc) '
			. 'preventing connections from working properly.',
			'fw'
		)
	);
}

function fw_ext_backups_rmdir_recursive($dir) {
	if (is_dir($dir = fw_fix_path($dir))) {
		if ($files = array_diff(($files = scandir($dir)) ? $files : array(), array('.', '..'))) {
			foreach ( $files as $file ) {
				$file = $dir .'/'. $file;

				if ( is_dir( $file ) ) {
					if ( ! fw_ext_backups_rmdir_recursive( $file ) ) {
						return false;
					}
				} else {
					if ( ! unlink( $file ) ) {
						return false;
					}
				}
			}
		}

		if ( ! rmdir($dir) ) {
			return false;
		}

		return true;
	}

	return false;
}

/**
 * @param string $dir
 * @return bool|null
 * http://stackoverflow.com/a/7497848/1794248
 */
function fw_ext_backups_is_dir_empty($dir) {
	if (!is_readable($dir)) {
		return null;
	}

	if (false === ($handle = opendir($dir))) {
		return null;
	}

	while (false !== ($entry = readdir($handle))) {
		if ($entry !== '.' && $entry !== '..') {
			return false;
		}
	}

	return true;
}

/**
 * @param string $source_dir
 * @param string $destination_dir
 * @return bool|WP_Error
 */
function fw_ext_backups_copy_dir_recursive($source_dir, $destination_dir) {
	$source_dir = fw_fix_path($source_dir);
	$destination_dir = fw_fix_path($destination_dir);

	$dir_chmod = 0755;

	if (!file_exists($destination_dir)) {
		if (!mkdir($destination_dir, $dir_chmod)) {
			return new WP_Error(
				'mkdir_fail',
				sprintf(__('Failed to create dir: %s'), $destination_dir)
			);
		}
	}

	try {
		foreach (
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($source_dir),
				RecursiveIteratorIterator::SELF_FIRST
			) as $item /** @var SplFileInfo $item */
		) {
			if (in_array(basename($iterator->getSubPathName()), array('.', '..'), true)) {
				continue; // We can't use RecursiveDirectoryIterator::SKIP_DOTS, it was added in php 5.3
			}

			$destination_path = $destination_dir . '/' . $iterator->getSubPathName();

			if ($item->isDir()) {
				if (!mkdir($destination_path, $dir_chmod)) {
					return new WP_Error(
						'mk_sub_dir_fail',
						sprintf(__('Failed to create dir: %s'), $destination_path)
					);
				}
			} else {
				if (!copy($item->getPathname(), $destination_path)) {
					return new WP_Error(
						'copy_fail',
						sprintf(__('Failed to copy: %s'), $destination_path)
					);
				}
			}
		}
	} catch (UnexpectedValueException $e) {
		return new WP_Error(
			'dir_copy_fail',
			(string)$e->getMessage()
		);
	}

	return true;
}

/**
 * If current user is allowed to make full backup or restore
 * This method must be used before calling $backups->tasks()->do_backup|restore()
 * to not allow simple admins to make full backup|restore on multisite and affect all sites.
 *
 * $backups->tasks()->do_backup|restore() Can't do that check
 * because those methods are also called in cron, when the user is not logged in
 */
function fw_ext_backups_current_user_can_full() {
	if ( is_multisite() ) {
		return is_main_site() &&
		       fw_current_user_can( array( 'manage_network_plugins' ), false ) &&
		       fw_current_user_can( array( 'manage_network_themes' ), false );
	} else {
		return fw_current_user_can( array( 'install_plugins' ), false ) &&
		       fw_current_user_can( array( 'install_themes' ), false );
	}
}

/**
 * Extract from zip files one by one and check if timeout wasn't reached
 * @param string|resource $zip Path or zip_open() result
 * @param string $destination_dir
 * @param int $timeout
 * @param string $last_entry
 * @return array|WP_Error Done when $result['finished'] === true
 * @since 2.0.16
 */


function fw_ext_backups_unzip_partial($zip_path, $destination_dir, $last_entry = '', $timeout = 0) {
    $zip = new ZipArchive();

    if ($zip->open($zip_path) !== true) {
        return new WP_Error(
            'cannot_open_zip', sprintf(__('Cannot open zip: %s', 'fw'), $zip_path)
        );
    }

    if ($timeout <= 0) {
        $ext = fw_ext('backups');
        $timeout = $ext->get_timeout(-10);
    }

    $result = [
        'finished' => false,
        'last_entry' => $last_entry,
        'extracted_files' => 0,
    ];

    $max_time = time() + $timeout;
    $start_index = $last_entry ? $zip->locateName($last_entry) + 1 : 0;

    for ($i = $start_index; $i < $zip->numFiles; $i++) {
        if (time() >= $max_time) {
            $result['last_entry'] = $zip->getNameIndex($i - 1);
            break;
        }

        $entryName = $zip->getNameIndex($i);
        $destination_path = $destination_dir . '/' . $entryName;

        if (substr($entryName, -1) === '/') {
            continue; // Skip directories
        }

        if (!is_dir($dest_dir = dirname($destination_path)) && !mkdir($dest_dir, 0777, true)) {
            $zip->close();
            return new WP_Error(
                'mkdir_fail', sprintf(__('Cannot create directory: %s', 'fw'), $dest_dir)
            );
        }

        $content = $zip->getFromIndex($i);
        if ($content === false) {
            $zip->close();
            return new WP_Error(
                'zip_entry_read_fail', sprintf(__('Cannot read entry: %s', 'fw'), $entryName)
            );
        }

        if (file_put_contents($destination_path, $content) === false) {
            $zip->close();
            return new WP_Error(
                'fopen_fail', sprintf(__('Cannot create file: %s', 'fw'), $destination_path)
            );
        }

        $result['extracted_files']++;
    }

    $result['finished'] = ($i >= $zip->numFiles);
    $zip->close();

    return $result;
}


function fw_ext_backups_destination_directory() {
	$uploads = wp_upload_dir();

	return fw_fix_path( $uploads['basedir'] ) . '/fw-backup';
}