<?php
function preload_lcp_image() {
  echo '<link rel="preload" as="image" href="https://carrey.ai/path-to-hero-image.webp">';
}
add_action('wp_head', 'preload_lcp_image');