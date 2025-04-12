<?php
function force_https_urls($content) {
  return str_replace('http://carrey.ai', 'https://carrey.ai', $content);
}
add_filter('the_content', 'force_https_urls');
add_filter('style_loader_src', 'force_https_urls');
add_filter('script_loader_src', 'force_https_urls');