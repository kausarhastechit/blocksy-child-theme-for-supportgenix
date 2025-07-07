<?php

if (! defined('WP_DEBUG')) {
    die( 'Direct access forbidden.' );
}
define( 'CHILD_THEME_BLOCKSY_VERSION', '2.0.0' );

add_action( 'wp_enqueue_scripts', function () {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    sgenix_theme_conditional_scripts();

});

/**
 * [sgenix_theme_conditional_scripts] Manage Conditional scripts
 * @return [void]
 */
function sgenix_theme_conditional_scripts(){
      if( get_page_template_slug() === 'price-page.php' ){
        wp_enqueue_style('sgenix-price-style-min', get_stylesheet_directory_uri() . '/assets/css/price-style.css');
        wp_enqueue_script('support-genix-plugin-min', get_stylesheet_directory_uri() . '/assets/js/plugins-min.js', array('jquery'), CHILD_THEME_BLOCKSY_VERSION, true );
        wp_enqueue_script('support-genix-price-min', get_stylesheet_directory_uri() . '/assets/js/price.active.js', array('jquery'), CHILD_THEME_BLOCKSY_VERSION, true );
    }

}

// Blog Breadcrumbs customisation
function support_genix_blog_breadcrumbs() {
    if (is_home() || is_archive() || is_category() || is_tag()) {
        echo '<div class="custom-hero-element">';
        // Insert FluentFroms shortcode
        echo do_shortcode('[fluentform id="6"]');
        echo '</div>';
    }
}

// WebP image Support Script
function allow_webp_upload($mimes) {
$mimes['webp'] = 'image/webp';
return $mimes;
}
add_filter('upload_mimes', 'allow_webp_upload');
function enable_webp_preview($result, $path, $image_mime) {
if ($image_mime === 'image/webp') {
$result = true;
}
return $result;
}
add_filter('file_is_displayable_image', 'enable_webp_preview', 10, 3);


$hero_element_id = 'custom_description';
add_action('blocksy:hero:' . $hero_element_id . ':after', 'support_genix_blog_breadcrumbs');



// Mega Menu Module Load FUnction
function load_child_theme_modules() {
    
    $modules_dir = get_stylesheet_directory() . '/modules/';
    
    $mega_menu_module = $modules_dir . 'mega-menu/ht-custom-mega-menu.php';
    
    if (file_exists($mega_menu_module)) {
        require_once $mega_menu_module;
    }
}
add_action('after_setup_theme', 'load_child_theme_modules', 20);