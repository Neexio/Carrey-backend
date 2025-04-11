<?php if ( ! defined( 'FW' ) ) die( 'Forbidden' );
/**
 * @var FW_Ext_Backups_Demo[] $demos
 */

/**
 * @var FW_Extension_Backups $backups
 */
$backups = fw_ext('backups');
$counter = 0;
if ($backups->is_disabled()) {
	$confirm = '';
} else {
	$confirm = esc_html__(
		'IMPORTANT: Installing this demo content will delete the content you currently have on your website.'
		. ' However, we create a backup of your current content in (Tools > Backup).'
		. ' You can restore the backup from there at any time in the future.',
		'fw'
	);
}
?>
<h2 class="wcf-theme--title"><?php esc_html_e('Demo Content Install', 'fw') ?></h2>

<div>
	<?php if ( !class_exists('ZipArchive') ): ?>
		<div class="error below-h2">
			<p>
				<strong><?php _e( 'Important', 'fw' ); ?></strong>:
				<?php printf(
					__( 'You need to activate %s.', 'fw' ),
					'<a href="http://php.net/manual/en/book.zip.php" target="_blank">'. __('zip extension', 'fw') .'</a>'
				); ?>
			</p>
		</div>
	<?php endif; ?>

	<?php if ($http_loopback_warning = fw_ext_backups_loopback_test()) : ?>
		<div class="error">
			<p><strong><?php _e( 'Important', 'fw' ); ?>:</strong> <?php echo $http_loopback_warning; ?></p>
		</div>
		<script type="text/javascript">var fw_ext_backups_loopback_failed = true;</script>
	<?php endif; ?>
</div>

<p></p>
<div class="theme-wcf-filter-area">	
	<div class="filter-by-cat">
		<select>
	        <option value=''><?php echo sprintf('All Categories (%s)', count($demos) ); ?></option>	        
	    </select>
	</div>
	<div class="search">
	  <div class="search-box">
	    <div class="search-field">
	      <input list="demo-items" id="wcf-demo-items-search-js" placeholder="Search..." class="input" type="text">	     
	      <div class="search-box-icon">
	        <button class="btn-icon-content">
	          <i class="search-icon">
	            <svg xmlns="://www.w3.org/2000/svg" version="1.1" viewBox="0 0 512 512"><path d="M416 208c0 45.9-14.9 88.3-40 122.7L502.6 457.4c12.5 12.5 12.5 32.8 0 45.3s-32.8 12.5-45.3 0L330.7 376c-34.4 25.2-76.8 40-122.7 40C93.1 416 0 322.9 0 208S93.1 0 208 0S416 93.1 416 208zM208 352a144 144 0 1 0 0-288 144 144 0 1 0 0 288z" fill="#fff"></path></svg>
	          </i>
	        </button>
	      </div>
	    </div>
	  </div>
	</div>		
</div>
<div class="theme-browser rendered wcf-theme-browser" id="fw-ext-backups-demo-list">
<?php foreach ($demos as $demo): $counter++; ?>
	<div <?php echo $counter > 15 ? 'style="display:none"' : ''; ?> data-cat="<?php echo $demo->get_category(); ?>" class="theme fw-ext-backups-demo-item wcf-demo-item" id="demo-<?php echo esc_attr($demo->get_id()) ?>">
		<div class="theme-screenshot">
			<img src="<?php echo esc_attr($demo->get_screenshot()); ?>" alt="Screenshot" />
		</div>
		<?php if ($demo->get_preview_link()): ?>
			<a class="more-details" target="_blank" href="<?php echo esc_attr($demo->get_preview_link()) ?>">
				<?php esc_html_e('Live Preview', 'fw') ?>
			</a>
		<?php endif; ?>
        <div class="theme-actions-wrapper">
            <h3 class="wcf-theme-name wcf-demo-tpl-name"><?php echo esc_html($demo->get_title()); ?></h3>
            <div class="theme-actions">
                <a class="button button-primary"
                   href="#" onclick="return false;"
                   data-confirm="<?php echo esc_attr($confirm); ?>"
                   data-install="<?php echo esc_attr($demo->get_id()) ?>"><?php esc_html_e('Install', 'fw'); ?></a>
            </div>
		</div>
	</div>
<?php endforeach; ?>
</div>
<?php if($counter > 15){ ?>
	<div class="wcf-demo-loadmore-wrapper">
		<a class="loadmore-wcf-demo" href="javascript:void(0)">
			Loadmore
		</a>
	</div>
<?php } ?>
