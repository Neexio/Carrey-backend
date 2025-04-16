<?php

use Elementor\Core\Files\File_Types\Svg;

if ( ! function_exists( 'str_ends_with' ) ) {
	function str_ends_with( string $haystack, string $needle ) {
		return empty( $needle ) || substr( $haystack, - strlen( $needle ) ) === $needle;
	}
}

if ( ! function_exists( 'str_starts_with' ) ) {
	function str_starts_with( string $haystack, string $needle ) {
		return empty( $needle ) || strpos( $haystack, $needle ) === 0;
	}
}


if ( ! function_exists( 'str_contains' ) ) {
	function str_contains( string $haystack, string $needle ) {
		return empty( $needle ) || strpos( $haystack, $needle ) !== false;
	}
}

function sassly_has_string_prefix( $string, $prefix ) {

	if ( is_array( $prefix ) ) {
		foreach ( $prefix as $match ) {
			if ( substr( $string, 0, strlen( $match ) ) == $match ) {
				return true;
			}
		}

		return false;
	} else {
		return substr( $string, 0, strlen( $prefix ) ) == $prefix;
	}
}

function sassly_new_directory( $cache_folder ) {

	if ( ! is_dir( $cache_folder ) ) {
		wp_mkdir_p( $cache_folder );
		chmod( $cache_folder, 0777 );
	}

}

if ( ! function_exists( 'sassly_get_cache_tax_types' ) ) {
	function sassly_get_cache_tax_types() {
		$data = get_option( 'sassly_get_all_custom_taxonomies_cache' );

		return $data ? $data : [];
	}
}

if ( ! function_exists( 'sassly_elementor_post_single_layout_json' ) ) {
	function sassly_elementor_post_single_layout_json() {

		include_once( SASSLY_ESSENTIAL_DIR_PATH . 'inc/blog/single.elementor.php' );
	}
}


if ( ! function_exists( 'sassly_elementor_blog__layout_json' ) ) {
	function sassly_elementor_blog__layout_json() {
		include_once( SASSLY_ESSENTIAL_DIR_PATH . 'inc/blog/blog.elementor.php' );
	}
}

if ( ! function_exists( 'sassly_elementor_search__layout_json' ) ) {
	function sassly_elementor_search__layout_json() {
		include_once( SASSLY_ESSENTIAL_DIR_PATH . 'inc/blog/search.elementor.php' );
	}
}

if ( ! function_exists( 'sassly_elementor_error__layout_json' ) ) {
	function sassly_elementor_error__layout_json() {
		include_once( SASSLY_ESSENTIAL_DIR_PATH . 'inc/blog/error.elementor.php' );
	}
}


if ( ! function_exists( 'sassly_header_footer__custom_ele_type' ) ) {

	function sassly_header_footer__custom_ele_type( $type = 'header' ) {
		$default = [ '' => esc_html__( 'Select a option', 'sassly-essential' ) ];
		$args    = [
			'post_type'      => 'wcf-hf-tpl',// , elementor-hf
			'order'          => 'ASC',
			'posts_per_page' => 50,
			'meta_query'     => [
				'relation' => 'OR',
				[
					'key'     => 'wcf_hf_options',
					'value'   => $type,
					'compare' => 'like',
				],
				[
					'key'     => 'wcf_hf_template_type',
					'value'   => $type,
					'compare' => '==',
				],
			],
		];

		$template = get_posts( $args );
		if ( is_wp_error( $template ) ) {
			return $default;
		}

		if ( is_array( $template ) && count( $template ) ) {

			foreach ( $template as $item ) {

				$return_arr[ $item->ID ] = wp_kses_post( $item->post_title );
			}

			$return_arr[''] = esc_html__( 'Select a option', 'sassly-essential' );

			return array_reverse( $return_arr, true );
		}

		return $default;
	}

}

if ( ! function_exists( 'sassly_get_cache_post_types' ) ) {

	function sassly_get_cache_post_types() {
		$data = get_option( 'sassly_get_post_types_cache' );

		return $data ? $data : [];
	}

}
/**
 * Theme option
 */

/* return the specific value from theme options  */
if ( ! function_exists( 'sassly_option' ) ) {
	function sassly_option( $option = '', $default = '', $parent = 'sassly_settings' ) {

		if ( $option ) {
			$options = get_option( $parent );

			return ( isset( $options[ $option ] ) ) ? $options[ $option ] : $default;
		}

		return $default;
	}
}

// return the specific value from metabox
// ----------------------------------------------------------------------------------------
if ( ! function_exists( 'sassly_meta_option' ) ) {
	function sassly_meta_option( $postid, $key, $default_value = '', $parent_key = 'sassly_post_options' ) {

		$post_key = $parent_key;
		// page meta
		if ( get_post_type() == 'page' ) {
			$post_key = 'sassly_page_options';
		}
		// post meta
		if ( get_post_type() == 'post' ) {
			$post_key = 'sassly_post_options';
		}
		// custom post meta

		if ( class_exists( 'CSF' ) ) {
			$options = get_post_meta( get_the_ID(), $post_key, true );

			return ( isset( $options[ $key ] ) ) ? $options[ $key ] : $default_value;

		}

		return $default_value;
	}
}

if ( ! function_exists( 'sassly_social_share_list' ) ):
	function sassly_social_share_list() {

		$data = array(
			''              => '---',
			'facebook'      => esc_html__( 'Facebook', 'sassly-essential' ),
			'twitter'       => esc_html__( 'twitter', 'sassly-essential' ),
			'linkedin'      => esc_html__( 'linkedin', 'sassly-essential' ),
			'pinterest'     => esc_html__( 'pinterest ', 'sassly-essential' ),
			'digg'          => esc_html__( 'digg', 'sassly-essential' ),
			'tumblr'        => esc_html__( 'tumblr', 'sassly-essential' ),
			'blogger'       => esc_html__( 'blogger', 'sassly-essential' ),
			'reddit'        => esc_html__( 'reddit', 'sassly-essential' ),
			'delicious'     => esc_html__( 'delicious', 'sassly-essential' ),
			'flipboard'     => esc_html__( 'flipboard', 'sassly-essential' ),
			'vkontakte'     => esc_html__( 'vkontakte', 'sassly-essential' ),
			'odnoklassniki' => esc_html__( 'odnoklassniki', 'sassly-essential' ),
			'moimir'        => esc_html__( 'moimir', 'sassly-essential' ),
			'livejournal'   => esc_html__( 'livejournal', 'sassly-essential' ),
			'blogger'       => esc_html__( 'blogger', 'sassly-essential' ),
			'evernote'      => esc_html__( 'evernote', 'sassly-essential' ),
			'flipboard'     => esc_html__( 'flipboard', 'sassly-essential' ),
			'mix'           => esc_html__( 'mix', 'sassly-essential' ),
			'meneame'       => esc_html__( 'meneame ', 'sassly-essential' ),
			'pocket'        => esc_html__( 'pocket ', 'sassly-essential' ),
			'surfingbird'   => esc_html__( 'surfingbird ', 'sassly-essential' ),
			'liveinternet'  => esc_html__( 'liveinternet ', 'sassly-essential' ),
			'buffer'        => esc_html__( 'buffer ', 'sassly-essential' ),
			'instapaper'    => esc_html__( 'instapaper ', 'sassly-essential' ),
			'xing'          => esc_html__( 'xing ', 'sassly-essential' ),
			'wordpres'      => esc_html__( 'wordpres ', 'sassly-essential' ),
			'baidu'         => esc_html__( 'baidu ', 'sassly-essential' ),
			'renren'        => esc_html__( 'renren ', 'sassly-essential' ),
			'weibo'         => esc_html__( 'weibo ', 'sassly-essential' ),


		);

		return $data;
	}
endif;

if ( ! function_exists( 'sassly_get_dir_file_list' ) ) {

	function sassly_get_dir_file_list( $dir = 'dir', $ext = 'php' ) {

		if ( ! is_dir( $dir ) ) {
			return [];
		}

		$files = [];

		foreach ( glob( "$dir/*.$ext" ) as $filename ) {
			$files[ basename( dirname( $filename ) ) . '-' . basename( $filename, '.' . $ext ) ] = $filename;
		}

		return $files;

	}

}

if ( ! function_exists( 'sassly_get_class_from_file' ) ) {
	function sassly_get_class_from_file( $file ) {
		$fp    = fopen( $file, 'r' );
		$class = $namespace = $buffer = '';
		$i     = 0;
		while ( ! $class ) {
			if ( feof( $fp ) ) {
				break;
			}

			$buffer .= fread( $fp, 512 );
			ob_start();
			$tokens = token_get_all( $buffer );
			$err    = ob_get_clean();

			if ( strpos( $buffer, '{' ) === false ) {
				continue;
			}

			for ( ; $i < count( $tokens ); $i ++ ) {
				if ( $tokens[ $i ][0] === T_NAMESPACE ) {
					for ( $j = $i + 1; $j < count( $tokens ); $j ++ ) {
						if ( $tokens[ $j ][0] === T_STRING ) {
							$namespace .= '\\' . $tokens[ $j ][1];
						} else if ( $tokens[ $j ] === '{' || $tokens[ $j ] === ';' ) {
							break;
						}
					}
				}

				if ( $tokens[ $i ][0] === T_CLASS ) {
					for ( $j = $i + 1; $j < count( $tokens ); $j ++ ) {
						if ( $tokens[ $j ] === '{' ) {
							$class = $tokens[ $i + 2 ][1];
						}
					}
				}
			}
		}
		if ( $class == '' ) {
			return '';
		}

		return $namespace . '\\' . $class;

	}
}

if ( ! function_exists( 'wcf_elementor_widget_concat_prefix' ) ) {

	/**
	 * Retrieve the widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 *
	 */
	function wcf_elementor_widget_concat_prefix( $widget_name ) {
		return __( 'WCF ' . $widget_name, 'sassly-essential' );
	}
}


if ( ! function_exists( 'wcf_elementor_widget_option_tags' ) ) {
	function wcf_elementor_widget_option_tags( $extra_tag = null ) {

		$options = [

			'h1'   => 'H1',
			'h2'   => 'H2',
			'h3'   => 'H3',
			'h4'   => 'H4',
			'h5'   => 'H5',
			'h6'   => 'H6',
			'p'    => 'P',
			'div'  => 'DIV',
			'span' => 'span',
		];

		if ( is_array( $extra_tag ) ) {
			$options = array_merge( $$options, $extra_tag );
		}

		return $options;
	}
}


function sassly_get_attachment_image_html( $settings, $image_size_key = 'image', $image_key = null, $attr = [] ) {

	if ( ! $image_key ) {
		$image_key = $image_size_key;
	}

	$image = $settings[ $image_key ];

	// Old version of image settings.
	if ( ! isset( $settings[ $image_size_key . '_size' ] ) ) {
		$settings[ $image_size_key . '_size' ] = '';
	}

	$size = $settings[ $image_size_key . '_size' ];

	$image_class = ! empty( $settings['hover_animation'] ) ? 'elementor-animation-' . $settings['hover_animation'] : '';

	$html = '';

	// If is the new version - with image size.
	$image_sizes = get_intermediate_image_sizes();

	$image_sizes[] = 'full';

	if ( ! empty( $image['id'] ) && ! wp_attachment_is_image( $image['id'] ) ) {
		$image['id'] = '';
	}


	// On static mode don't use WP responsive images.
	if ( ! empty( $image['id'] ) && in_array( $size, $image_sizes ) ) {
		if ( ! isset( $attr['class'] ) ) {
			$attr['class'] = '';
		}
		$html .= wp_get_attachment_image( $image['id'], $size, false, $attr );
	} else {
		$image_src = '';
		if ( isset( $image['url'] ) ) {
			$image_src = $image['url'];
		}

		if ( ! empty( $image_src ) ) {
			$attr['src']   = esc_attr( $image_src );
			$attr['title'] = Elementor\Control_Media::get_image_title( $image );
			$attr['alt']   = Elementor\Control_Media::get_image_alt( $image );
			$html          .= sassly_html_tag( 'img', $attr );

		}
	}

	return $html;
}

if ( ! function_exists( 'sassly_get_image_sizes' ) ) {

	function sassly_get_image_sizes( $size = '' ) {
		global $_wp_additional_image_sizes;

		$sizes                        = array();
		$get_intermediate_image_sizes = get_intermediate_image_sizes();

		// Create the full array with sizes and crop info
		foreach ( $get_intermediate_image_sizes as $_size ) {
			if ( in_array( $_size, array( 'thumbnail', 'medium', 'large' ) ) ) {
				$sizes[ $_size ] = $_size;

			} elseif ( isset( $_wp_additional_image_sizes[ $_size ] ) ) {
				$sizes[ $_size ] = $_size;
			}
		}

		// Get only 1 size if found
		if ( $size ) {
			if ( isset( $sizes[ $size ] ) ) {
				return $sizes[ $size ];
			} else {
				return false;
			}
		}

		return $sizes;
	}

}

function sassly_menu_list() {

	$return_menus = [];
	$menus        = wp_get_nav_menus();
	if ( is_array( $menus ) ) {
		foreach ( $menus as $menu ) {
			$return_menus[ $menu->term_id ] = esc_html( $menu->name );
		}
	}

	return $return_menus;
}


if ( ! function_exists( 'sassly_render_elementor_icons' ) ) {

	function sassly_render_elementor_icons( $content = array(), $class = '' ) {

		if ( ! is_array( $content ) ) {
			return false;
		}

		if ( is_array( $content['value'] ) ) {
			$svg_icon = $content['value']['url'];
		} else {
			$font_icon = $content['value'];
		}

		if ( ! is_array( $content['value'] ) && $font_icon ) {
			if ( $class ) {
				return '<i class="' . $class . ' ' . esc_attr( $font_icon ) . '"></i>';
			} else {
				return '<i class="' . esc_attr( $font_icon ) . '"></i>';
			}
		}

		if ( $content['library'] == 'svg' && isset( $content['value']['id'] ) ) {
			return Svg::get_inline_svg( $content['value']['id'] );
		}
	}
}


function sassly_html_tag( $tag, $attr = array(), $end = false ) {
	$html = '<' . $tag . ' ' . sassly_attr_to_html( $attr );

	if ( $end === true ) {
		# <script></script>
		$html .= '></' . $tag . '>';
	} else if ( $end === false ) {
		# <br/>
		$html .= '/>';
	} else {
		# <div>content</div>
		$html .= '>' . $end . '</' . $tag . '>';
	}

	return $html;
}

function sassly_attr_to_html( array $attr_array ) {
	$html_attr = '';

	foreach ( $attr_array as $attr_name => $attr_val ) {

		if ( $attr_val === false ) {
			continue;
		}

		$html_attr .= $attr_name . '="' . $attr_val . '" ';
	}

	return $html_attr;
}

if ( ! function_exists( 'sassly_get_config_value_by_name' ) ) {
	function sassly_get_config_value_by_name( $name ) {
		static $sassly_configs = [];
		if ( isset( $sassly_configs[ $name ] ) ) {
			return $sassly_configs[ $name ];
		}
		$file_path = SASSLY_ESSENTIAL_DIR_PATH . 'inc/configs/' . $name . '.php';
		if ( file_exists( $file_path ) ) {
			$sassly_configs[ $name ] = include( $file_path );

			return $sassly_configs[ $name ];
		}

		return [];
	}
}


if ( ! function_exists( 'sassly_locate_tpl' ) ) {

	/**
	 * Locate template.
	 *
	 * Locate the called template.
	 * Search Order:
	 * 1. /themes/theme/woo-ready/$template_name
	 * 2. /templates/$template_name.
	 *
	 * @param string $template_name Template to load.
	 * @param string $string $template_path  Path to templates.
	 * @param string $default_path Default path to template files.
	 *
	 * @return  string                          Path to the template file.
	 */
	function sassly_locate_tpl( $template_name, $template_path = '', $default_path = '' ) {


		if ( ! $template_path ):
			$template_path = 'sassly/';
		endif;


		if ( ! $default_path ):
			$default_path = SASSLY_ESSENTIAL_DIR_PATH . 'templates/';
		endif;


		$template = locate_template(
			array(
				$template_path . $template_name,
				$template_name
			)
		);


		if ( ! $template ):
			$template = $default_path . $template_name;
		endif;

		return apply_filters( 'sassly_locate_tpl', $template, $template_name, $template_path, $default_path );

	}
}

if ( ! function_exists( 'sassly_get_template' ) ) {

	/**
	 * Search for the template and include the file.
	 *
	 * @param string $template_name Template to load.
	 * @param array $args Args passed for the template file.
	 * @param string $string $template_path  Path to templates.
	 * @param string $default_path Default path to template files.
	 */
	function sassly_get_template( $template_name, $args = array(), $tempate_path = '', $default_path = '' ) {

		if ( is_array( $args ) && isset( $args ) ):
			extract( $args );
		endif;

		$template_file = sassly_locate_tpl( $template_name, $tempate_path, $default_path );

		if ( ! file_exists( $template_file ) ):
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $template_file ), '1.0.0' );

			return;
		endif;

		include $template_file;

	}
}
if ( ! function_exists( 'sassly_remote_sideloader' ) ) {
	function sassly_remote_sideloader( $url, $post_id = 0, $desc = null, $post_data = array() ) {
		// URL Validation
		if ( ! wp_http_validate_url( $url ) ) {
			return new WP_Error( 'invalid_url', 'File URL is invalid', array( 'status' => 400 ) );
		}

		// Gives us access to the download_url() and media_handle_sideload() functions.
		if ( ! function_exists( 'download_url' ) || ! function_exists( 'media_handle_sideload' ) ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';
		}

		// Download file to temp dir.
		$temp_file = download_url( $url );

		// if the file was not able to be downloaded
		if ( is_wp_error( $temp_file ) ) {
			return $temp_file;
		}

		// An array similar to that of a PHP `$_FILES` POST array
		$file_url_path = parse_url( $url, PHP_URL_PATH );
		$file_info     = wp_check_filetype( $file_url_path );
		$file          = array(
			'tmp_name' => $temp_file,
			'type'     => $file_info['type'],
			'name'     => basename( $file_url_path ),
			'size'     => filesize( $temp_file ),
		);

		if ( empty( $post_data ) ) {
			$post_data = array();
		}

		// Move the temporary file into the uploads directory.
		$attachment_id = media_handle_sideload( $file, $post_id, $desc, $post_data );


		@unlink( $temp_file );

		return $attachment_id;
	}
}

if ( ! function_exists( 'sassly_get_blog_elementor_id_by_meta' ) ) {
	/**
	 * Get Single Blog Elementor template id
	 *
	 * @param string $meta_key
	 */
	function sassly_get_blog_elementor_id_by_meta( $meta_key = 'wcf-blog-single-post' ) {

		static $the_post_id = null;

		if ( $the_post_id ) {
			return $the_post_id;
		}

		$args = array(
			'numberposts' => 1,
			'post_type'   => 'nothing',
			'meta_query'  => array(
				array(
					'key'     => $meta_key,
					'value'   => array( '' ),
					'compare' => 'NOT IN'
				)
			)
		);

		$latest_posts = get_posts( $args );

		if ( ! empty( $latest_posts ) && isset( $latest_posts[0] ) ) {
			$the_post_id = $latest_posts[0]->ID;
		}

		return $the_post_id;
	}
}

if ( ! function_exists( 'wcf_get_breadcrumbs' ) ) {

	function wcf_get_breadcrumbs( $seperator = '/', $word = '30' ) {

		$general_custom_post_type = sassly_option( 'general_custom_post_type' );
		$schema                   = '';
		echo '<ul class="default-breadcrumb__list">';

		if ( ! is_home() ) {
			echo '<li><a href="';
			echo esc_url( get_home_url( '/' ) );
			echo '">';
			echo esc_html__( 'Home', 'sassly-essential' ) . wp_kses_post( $seperator );
			echo "</a></li> ";

			if ( is_singular( 'post' ) ) {

				$category = get_the_category();
				// Sub category
				if ( isset( $category[0] ) && isset( $category[0]->parent ) ) {
					$parent_term = get_term( $category[0]->parent, $category[0]->taxonomy );
					if ( isset( $parent_term->term_id ) ) {
						echo '<li><a href=' . get_category_link( $parent_term->term_id ) . '>' . $parent_term->name . wp_kses_post( $seperator ) . '</a> </li>';
					}

				}
				if ( is_array( $category ) && isset( $category[0] ) ) {
					echo '<li><a href=' . get_category_link( $category[0]->term_id ) . '>' . $category[0]->name . wp_kses_post( $seperator ) . '</a> </li>';
				}

				echo '<li class="active">';
				echo esc_html( $word ) != '' ? wp_trim_words( get_the_title(), $word ) : get_the_title();
				echo '</li>';

			} elseif ( is_category() ) {

				$category = get_queried_object();
				echo '<li class="active">';
				echo esc_html( $word ) != '' ? wp_trim_words( $category->name, $word ) : $category->name;
				echo '</li>';

			} elseif ( is_page() ) {

				echo '<li class="active">';
				echo esc_html( $word ) != '' ? wp_trim_words( get_the_title(), $word ) : get_the_title();
				echo '</li>';

			} elseif ( get_post_type_object( get_post_type( get_queried_object() ) ) && is_single() ) {

				$postType     = get_post_type_object( get_post_type( get_queried_object() ) );
				$args         = array(
					'object_type' => array( get_post_type() ),
					'public'      => true,
					'show_ui'     => true,
				);
				$has_taxonomy = false;
				$taxonomies   = get_taxonomies( $args, 'name' );
				if ( is_array( $general_custom_post_type ) ) {

					$has_taxonomy = array_filter( $general_custom_post_type, function ( $var ) {
						return $var['cpt'] == get_post_type() && $var['cpt_primary_tax'] != '';
					} );
				}

				if ( is_array( $has_taxonomy ) && count( $has_taxonomy ) ) {

					$tax        = $has_taxonomy[0];
					$term_names = wp_get_post_terms( get_queried_object_id(), $tax['cpt_primary_tax'] );

					if ( isset( $term_names[0] ) && isset( $term_names[0]->parent ) ) {
						$parent_term = get_term( $term_names[0]->parent, $term_names[0]->taxonomy );
						echo '<li><a href=' . get_category_link( $parent_term->term_id ) . '>' . $parent_term->name . wp_kses_post( $seperator ) . '</a> </li>';
					}

					if ( isset( $term_names[0] ) ) {

						echo '<li><a href=' . get_category_link( $term_names[0]->term_id ) . '>' . $term_names[0]->name . wp_kses_post( $seperator ) . '</a> </li>';
					}

				}

				echo '<li class="active">';
				echo esc_html( $word ) != '' ? wp_trim_words( get_the_title(), $word ) : get_the_title();
				echo '</li>';

			}
		}
		if ( is_tag() ) {

			echo '<li class="active">';
			single_tag_title();
			echo '</li>';

		} elseif ( is_day() ) {

			echo "<li class='active'>" . esc_html__( 'Blogs for', 'sassly-essential' ) . " ";
			the_time( 'F jS, Y' );
			echo '</li>';

		} elseif ( is_month() ) {

			echo "<li class='active'>" . esc_html__( 'Blogs for', 'sassly-essential' ) . " ";
			the_time( 'F, Y' );
			echo '</li>';

		} elseif ( is_year() ) {

			echo "<li class='active'>" . esc_html__( 'Blogs for', 'sassly-essential' ) . " ";
			the_time( 'Y' );
			echo '</li>';

		} elseif ( is_author() ) {

			echo "<li class='active'>" . esc_html__( 'Author Blogs', 'sassly-essential' );
			echo '</li>';

		} elseif ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) {

			echo "<li class='active'>" . esc_html__( 'Blogs', 'sassly-essential' );
			echo '</li>';

		} elseif ( is_search() ) {

			echo "<li class='active'>" . esc_html__( 'Search Result', 'sassly-essential' );
			echo '</li>';

		} elseif ( is_404() ) {
			$_404_banner_title = esc_html__( '404 Error', 'sassly-essential' );
			$settings          = sassly_option( 'opt-tabbed-banner' );
			if ( isset( $settings['404_banner_page_title'] ) && $settings['404_banner_page_title'] != '' ) {
				$_404_banner_title = $settings['404_banner_page_title'];
			}
			echo sprintf( "<li>%s</li>", esc_html( $_404_banner_title ) );
		}

		echo '</ul>';

		?>
        <script type="application/ld+json">
            {
                "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                "itemListElement": [
                    {
                        "@type": "ListItem",
                        "position": 1,
                        "name": "Books",
                        "item": "https://example.com/books"
                    },
                    {
                        "@type": "ListItem",
                        "position": 2,
                        "name": "Science Fiction",
                        "item": "https://example.com/books/sciencefiction"
                    },
                    {
                        "@type": "ListItem",
                        "position": 3,
                        "name": "Award Winners"
                    }
                ]
            }
        </script>

		<?php
	}

}


if ( ! function_exists( 'SASSLY_ESSENTIAL_get_post_types' ) ) {

	function SASSLY_ESSENTIAL_get_post_types() {
		global $wp_post_types;
		$posts = array();

		foreach ( $wp_post_types as $post_type ) {
			$skip_posts_type = [
				'custom_css',
				'wp_navigation',
				'wp_global_styles',
				'wp_template_part',
				'wp_template',
				'wp_block',
				'user_request',
				'oembed_cache',
				'customize_changeset',
				'revision',
				'attachment',
				'elementor_library'
			];

			if ( ! in_array( $post_type->name, $skip_posts_type ) ) {
				$posts[ $post_type->name ] = $post_type->labels->singular_name;
			}

		}

		return $posts;
	}

}

if ( ! function_exists( 'wcf_custom_font_demo_review_callback' ) ) {

	function wcf_custom_font_demo_review_callback() {

		if ( ! is_admin() ) {
			return;
		}

		if ( get_post_type( get_the_id() ) != 'wcf-custom-font' ) {
			return;
		}

		$variation  = get_post_meta( get_the_id(), 'sassly_custom_fonts_options', true );
		$arr        = [];
		$custom_css = '
		  .wcf-demo-font-family-row {
			display: grid;
			grid-template-columns: 20% 15% 65%;
			border: 1px solid #e1e1e1;
			padding: 25px 10px;
			align-items: center;
		  }
		';
		if ( is_array( $variation ) && isset( $variation['wcf_font_variation'] ) ) {
			$variation = $variation['wcf_font_variation'];

			foreach ( $variation as $font ) {
				if ( $font['font_weight'] !== '' ) {
					if ( isset( $font['ttf_file'] ) && $font['ttf_file'] != '' ) {
						$arr[ get_the_title() ][] = [
							'weight' => $font['font_weight'],
							'style'  => $font['font_style'],
							'src'    => $font['ttf_file']
						];
					}
					if ( isset( $font['eot_file'] ) && $font['eot_file'] != '' ) {
						$arr[ get_the_title() ][] = [
							'weight' => $font['font_weight'],
							'style'  => $font['font_style'],
							'src'    => $font['eot_file']
						];
					}
					if ( isset( $font['woff2_file'] ) && $font['woff2_file'] != '' ) {
						$arr[ get_the_title() ][] = [
							'style'  => $font['font_style'],
							'weight' => $font['font_weight'],
							'src'    => $font['woff2_file']
						];
					}
					if ( isset( $font['woff_file'] ) && $font['woff_file'] != '' ) {
						$arr[ get_the_title() ][] = [
							'weight' => $font['font_weight'],
							'style'  => $font['font_style'],
							'src'    => $font['woff_file']
						];
					}
				}
			}

			foreach ( $arr as $font_family => $fonts ) {
				foreach ( $fonts as $font ) {
					//echo sprintf('<h2 style="font-family:%s;font-weight: %s; font-size: 22px; font-style:%s;"> The quick brown fox jumps over the lazy dog </h2>', get_the_title(),$font['weight'], $font['style']);
					echo sprintf( '<div class="wcf-demo-font-family-row">
				    <div> %s </div>
				    <div> %s </div>				    
				    <div style="font-family:%s;font-weight: %s; font-size: 24px; font-style:%s;"> The quick brown fox jumps over the lazy dog</div>
				    </div>', $font_family, $font['weight'], get_the_title(), $font['weight'], $font['style'] );
					$custom_css .= sprintf( '
							@font-face {
							  font-family: %s;
							  src: url(%s);
							  font-weight: %s;
							}', $font_family, $font['src'], $font['weight'] );
				}
			}
			echo '<style>' . $custom_css . '</style>';
		}

	}
}

if ( ! function_exists( 'sassly_custom_taxonomy_used_by_meta' ) ) {
	/**
	 * Get Single Blog Elementor template id
	 *
	 * @param string $meta_key
	 */
	function sassly_custom_taxonomy_used_by_meta( $id = '', $meta_key = 'wcf_blog_archive_type' ) {

		static $meta_values = null;

		if ( $meta_values ) {
			return $meta_values;
		}

		$args = array(
			'numberposts'  => 80,
			'post_type'    => 'wcf-blog-tpl',
			'post__not_in' => array( $id ),
			'meta_query'   => array(
				array(
					'key' => $meta_key,
				)
			)
		);

		$latest_posts = get_posts( $args );

		if ( ! empty( $latest_posts ) && isset( $latest_posts[0] ) ) {
			foreach ( $latest_posts as $item ) {
				$meta_values[] = get_post_meta( $item->ID, $meta_key, true );
			}
		}

		return $meta_values;
	}
}

if ( ! function_exists( 'sassly_content_estimated_reading_time' ) ) {

	function sassly_content_estimated_reading_time( $content = '', $wpm = 200 ) {

		if ( $content == '' ) {
			$content = get_the_content();
		}

		$clean_content = esc_html( $content );
		$word_count    = str_word_count( $clean_content );
		$time          = ceil( $word_count / $wpm );

		return $time;
	}

}

if ( ! function_exists( 'wcf__theme__update__html' ) ) {

	function wcf__theme__update__html() {
		echo '<div id="wcf--theme-update-container"> 		
		</div>
		';
	}

}


function SASSLY_ESSENTIAL_get_background_patterns( $key = null ) {

	$all = [
		'custom' => SASSLY_ESSENTIAL_ASSETS_URL . 'images/patterns/custom.svg',
		'bg-1'   => SASSLY_ESSENTIAL_ASSETS_URL . 'images/patterns/bg-1.png',
	];

	if ( ! is_null( $key ) ) {
		if ( isset( $all[ $key ] ) ) {
			return $all[ $key ];
		} else {
			return false;
		}
	}

	return $all;
}

if ( ! function_exists( 'sassly_theme_service_pass' ) ) {

	function sassly_theme_service_pass() {

		static $cache = null;
		static $pass = false;
		return true;
		if ( is_null( $cache ) ) {
			$varify    = get_option( 'sassly_lic_Key', false );
			$user_data = get_user_meta( 1, 'sassly_theme_data', true );
			if ( $user_data && isset( $user_data['lic'] ) && isset( $user_data['code'] ) && ( ! $varify || $varify != '' ) ) {
				$licenseCode  = sanitize_text_field( wp_unslash( $user_data['lic'] ) );
				$licenseEmail = sanitize_text_field( wp_unslash( $user_data['email'] ) );
				update_option( "sassly_lic_Key", $licenseCode ) || add_option( "sassly_lic_Key", $licenseCode );
				update_option( "sassly_lic_email", $licenseEmail ) || add_option( "sassly_lic_email", $licenseEmail );
			}
			$cache = true;
			if ( $varify && $varify != '' ) {
				$pass = true;
			} else {
				$pass = false;
			}

			return $pass;
		} else {
			return $pass;
		}

	}
}

