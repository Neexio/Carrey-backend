<?php
function carrey_remove_unused_assets() {
  if (is_front_page()) {
    wp_dequeue_script('jquery-migrate');
    wp_dequeue_style('wcf-addons-pro');
    wp_dequeue_style('bootstrap');
    wp_dequeue_script('mixitup');
  }
}
add_action('wp_enqueue_scripts', 'carrey_remove_unused_assets', 100);