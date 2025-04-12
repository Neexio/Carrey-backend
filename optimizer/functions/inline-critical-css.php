<?php
function carrey_inline_critical_css() {
  if (is_front_page()) {
    echo '<style>';
    readfile(get_template_directory() . '/optimizer/critical/critical-home.css');
    echo '</style>';
    echo '<link rel="preload" href="/wp-content/themes/yourtheme/style.css" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">';
  }
}
add_action('wp_head', 'carrey_inline_critical_css');