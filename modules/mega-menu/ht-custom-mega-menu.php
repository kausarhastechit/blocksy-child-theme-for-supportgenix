<?php
/**
 * Plugin Name: HT Mega Menu with Block Patterns
 * Description: Adds mega menu functionality using Gutenberg block patterns
 * Version: 1.0.0
 * Author: HasThemes
 * Author URI: https://hasthemes.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ht-custom-mega-menu
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class HT_MegaMenuPlugin {
    
    public function __construct() {
        add_action('wp_loaded', array($this, 'ht_init'));
    }
    
    public function ht_init() {
        // Admin hooks
        add_action('wp_nav_menu_item_custom_fields', array($this, 'ht_add_custom_fields'), 10, 4);
        add_action('wp_update_nav_menu_item', array($this, 'ht_save_custom_fields'), 10, 3);
        
        // Frontend hooks
        add_filter('wp_nav_menu_objects', array($this, 'ht_modify_menu_items'), 10, 2);
        add_action('wp_enqueue_scripts', array($this, 'ht_enqueue_frontend_assets'));
        
        // Admin assets
        add_action('admin_enqueue_scripts', array($this, 'ht_enqueue_admin_assets'));
    }
    
    /**
     * Add custom fields to menu items in admin
     */
    public function ht_add_custom_fields($item_id, $item, $depth, $args) {
        $is_mega_menu = get_post_meta($item_id, 'ht_is_mega_menu', true);
        $pattern_id = get_post_meta($item_id, 'ht_pattern_id', true);
        $page_id = get_post_meta($item_id, 'ht_page_id', true);
        $content_type = get_post_meta($item_id, 'ht_content_type', true);
        
        // New options
        $menu_width = get_post_meta($item_id, 'ht_menu_width', true) ?: 'default';
        $custom_width = get_post_meta($item_id, 'ht_custom_width', true) ?: '1200';
        $menu_position = get_post_meta($item_id, 'ht_menu_position', true) ?: 'center';
        
        // Custom CSS options
        $bg_color = get_post_meta($item_id, 'ht_bg_color', true);
        $border_radius = get_post_meta($item_id, 'ht_border_radius', true) ?: '8';
        $border_width = get_post_meta($item_id, 'ht_border_width', true) ?: '1';
        $border_color = get_post_meta($item_id, 'ht_border_color', true) ?: '#e0e0e0';
        $padding = get_post_meta($item_id, 'ht_padding', true) ?: '30';
        $margin_top = get_post_meta($item_id, 'ht_margin_top', true) ?: '10';
        
        // Default content type
        if (empty($content_type)) {
            $content_type = 'pattern';
        }
        ?>
        <div class="ht-mega-menu-fields ht-mega-menu-item-<?php echo $item_id; ?>" style="margin-top: 10px; background: #f5f5f5; padding: 10px; border-radius: 4px;">
            <p class="description">
                <label>
                    <input type="checkbox" 
                           name="ht_is_mega_menu[<?php echo $item_id; ?>]" 
                           value="1" 
                           <?php checked($is_mega_menu, '1'); ?> 
                           class="ht-mega-menu-checkbox"
                           onchange="htMegaMenuToggle(this, <?php echo $item_id; ?>)">
                    <strong>Enable Mega Menu</strong>
                </label>
            </p>
            
            <div class="ht-mega-menu-content-options ht-content-options-<?php echo $item_id; ?>" style="<?php echo $is_mega_menu ? 'margin-left: 20px; margin-top: 10px;' : 'display:none; margin-left: 20px; margin-top: 10px;'; ?>">
                
                <!-- Content Settings -->
                <div style="background: #fff; padding: 10px; margin-bottom: 10px; border-radius: 4px;">
                    <h4 style="margin: 0 0 10px 0; font-size: 13px; color: #23282d;">Content Settings</h4>
                    
                    <p class="description">
                        <label>
                            <strong>Content Type:</strong>
                            <select name="ht_content_type[<?php echo $item_id; ?>]" 
                                    class="ht-content-type-selector"
                                    onchange="htContentTypeChange(this, <?php echo $item_id; ?>)">
                                <option value="pattern" <?php selected($content_type, 'pattern'); ?>>Block Pattern</option>
                                <option value="page" <?php selected($content_type, 'page'); ?>>Page Content</option>
                            </select>
                        </label>
                    </p>
                    
                    <!-- Block Pattern Field -->
                    <div class="ht-mega-menu-pattern-field ht-pattern-field-<?php echo $item_id; ?> ht-content-field" style="<?php echo ($content_type === 'pattern') ? '' : 'display:none;'; ?>">
                        <p class="description">
                            <label>
                                <strong>Block Pattern ID:</strong><br>
                                <input type="text" name="ht_pattern_id[<?php echo $item_id; ?>]" value="<?php echo esc_attr($pattern_id); ?>" placeholder="e.g., 1026" style="width: 200px; margin-top: 5px;">
                            </label>
                        </p>
                    </div>
                    
                    <!-- Page Selection Field -->
                    <div class="ht-mega-menu-page-field ht-page-field-<?php echo $item_id; ?> ht-content-field" style="<?php echo ($content_type === 'page') ? '' : 'display:none;'; ?>">
                        <p class="description">
                            <label>
                                <strong>Select Page:</strong><br>
                                <select name="ht_page_id[<?php echo $item_id; ?>]" class="ht-page-select" style="width: 200px; margin-top: 5px;">
                                    <option value="">-- Select Page --</option>
                                    <?php
                                    $pages = get_pages(array(
                                        'post_status' => 'publish',
                                        'sort_order' => 'ASC',
                                        'sort_column' => 'post_title',
                                    ));
                                    foreach ($pages as $page) {
                                        echo '<option value="' . $page->ID . '" ' . selected($page_id, $page->ID, false) . '>' . 
                                             esc_html($page->post_title) . ' (ID: ' . $page->ID . ')</option>';
                                    }
                                    ?>
                                </select>
                                <span style="display: block; font-size: 11px; color: #666; margin-top: 3px;">
                                    Or enter Page ID manually: 
                                    <input type="text" name="ht_page_id_manual[<?php echo $item_id; ?>]" class="ht-page-id-manual" value="<?php echo esc_attr($page_id); ?>" placeholder="123" style="width: 60px;">
                                </span>
                            </label>
                        </p>
                    </div>
                </div>
                
                <!-- Layout Settings -->
                <div style="background: #fff; padding: 10px; margin-bottom: 10px; border-radius: 4px;">
                    <h4 style="margin: 0 0 10px 0; font-size: 13px; color: #23282d;">Layout Settings</h4>
                    
                    <p class="description">
                        <label>
                            <strong>Menu Width:</strong>
                            <select name="ht_menu_width[<?php echo $item_id; ?>]" 
                                    class="ht-menu-width-selector"
                                    onchange="htMenuWidthChange(this, <?php echo $item_id; ?>)">
                                <option value="default" <?php selected($menu_width, 'default'); ?>>Default (800px)</option>
                                <option value="full" <?php selected($menu_width, 'full'); ?>>Full Width</option>
                                <option value="custom" <?php selected($menu_width, 'custom'); ?>>Custom Width</option>
                            </select>
                        </label>
                    </p>
                    
                    <div class="ht-custom-width-field ht-custom-width-<?php echo $item_id; ?>" style="<?php echo ($menu_width === 'custom') ? '' : 'display:none;'; ?>">
                        <p class="description">
                            <label>
                                <strong>Custom Width (px):</strong><br>
                                <input type="number" name="ht_custom_width[<?php echo $item_id; ?>]" value="<?php echo esc_attr($custom_width); ?>" placeholder="1200" style="width: 100px;">
                            </label>
                        </p>
                    </div>
                    
                    <p class="description">
                        <label>
                            <strong>Menu Position:</strong>
                            <select name="ht_menu_position[<?php echo $item_id; ?>]">
                                <option value="left" <?php selected($menu_position, 'left'); ?>>Left Aligned</option>
                                <option value="center" <?php selected($menu_position, 'center'); ?>>Center (Default)</option>
                                <option value="right" <?php selected($menu_position, 'right'); ?>>Right Aligned</option>
                            </select>
                        </label>
                    </p>
                </div>
                
                <!-- Style Settings -->
                <div style="background: #fff; padding: 10px; border-radius: 4px;">
                    <h4 style="margin: 0 0 10px 0; font-size: 13px; color: #23282d;">Style Settings</h4>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                        <p class="description">
                            <label>
                                <strong>Background Color:</strong><br>
                                <input type="text" name="ht_bg_color[<?php echo $item_id; ?>]" value="<?php echo esc_attr($bg_color); ?>" placeholder="#ffffff" class="ht-color-picker" style="width: 100%;">
                            </label>
                        </p>
                        
                        <p class="description">
                            <label>
                                <strong>Border Color:</strong><br>
                                <input type="text" name="ht_border_color[<?php echo $item_id; ?>]" value="<?php echo esc_attr($border_color); ?>" placeholder="#e0e0e0" class="ht-color-picker" style="width: 100%;">
                            </label>
                        </p>
                        
                        <p class="description">
                            <label>
                                <strong>Border Radius (px):</strong><br>
                                <input type="number" name="ht_border_radius[<?php echo $item_id; ?>]" value="<?php echo esc_attr($border_radius); ?>" placeholder="8" style="width: 100%;">
                            </label>
                        </p>
                        
                        <p class="description">
                            <label>
                                <strong>Border Width (px):</strong><br>
                                <input type="number" name="ht_border_width[<?php echo $item_id; ?>]" value="<?php echo esc_attr($border_width); ?>" placeholder="1" style="width: 100%;">
                            </label>
                        </p>
                        
                        <p class="description">
                            <label>
                                <strong>Padding (px):</strong><br>
                                <input type="number" name="ht_padding[<?php echo $item_id; ?>]" value="<?php echo esc_attr($padding); ?>" placeholder="30" style="width: 100%;">
                            </label>
                        </p>
                        
                        <p class="description">
                            <label>
                                <strong>Margin Top (px):</strong><br>
                                <input type="number" name="ht_margin_top[<?php echo $item_id; ?>]" value="<?php echo esc_attr($margin_top); ?>" placeholder="10" style="width: 100%;">
                            </label>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        // Inline functions for immediate execution
        function htMegaMenuToggle(checkbox, itemId) {
            var contentOptions = document.querySelector('.ht-content-options-' + itemId);
            if (checkbox.checked) {
                contentOptions.style.display = 'block';
                // Trigger content type change
                var selector = contentOptions.querySelector('.ht-content-type-selector');
                if (selector) {
                    htContentTypeChange(selector, itemId);
                }
            } else {
                contentOptions.style.display = 'none';
            }
        }
        
        function htContentTypeChange(selector, itemId) {
            var contentType = selector.value;
            var patternField = document.querySelector('.ht-pattern-field-' + itemId);
            var pageField = document.querySelector('.ht-page-field-' + itemId);
            
            if (patternField) patternField.style.display = 'none';
            if (pageField) pageField.style.display = 'none';
            
            if (contentType === 'pattern' && patternField) {
                patternField.style.display = 'block';
            } else if (contentType === 'page' && pageField) {
                pageField.style.display = 'block';
            }
        }
        
        function htMenuWidthChange(selector, itemId) {
            var widthType = selector.value;
            var customField = document.querySelector('.ht-custom-width-' + itemId);
            
            if (customField) {
                if (widthType === 'custom') {
                    customField.style.display = 'block';
                } else {
                    customField.style.display = 'none';
                }
            }
        }
        </script>
        <?php
    }
    
    /**
     * Save custom fields
     */
    public function ht_save_custom_fields($menu_id, $menu_item_db_id, $args) {
        // Save mega menu checkbox
        if (isset($_POST['ht_is_mega_menu'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_is_mega_menu', '1');
        } else {
            delete_post_meta($menu_item_db_id, 'ht_is_mega_menu');
        }
        
        // Save content type
        if (isset($_POST['ht_content_type'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_content_type', sanitize_text_field($_POST['ht_content_type'][$menu_item_db_id]));
        }
        
        // Save pattern ID
        if (isset($_POST['ht_pattern_id'][$menu_item_db_id])) {
            $pattern_id = sanitize_text_field($_POST['ht_pattern_id'][$menu_item_db_id]);
            if (!empty($pattern_id)) {
                update_post_meta($menu_item_db_id, 'ht_pattern_id', $pattern_id);
            } else {
                delete_post_meta($menu_item_db_id, 'ht_pattern_id');
            }
        }
        
        // Save page ID (from select or manual input)
        $page_id = '';
        if (isset($_POST['ht_page_id'][$menu_item_db_id]) && !empty($_POST['ht_page_id'][$menu_item_db_id])) {
            $page_id = sanitize_text_field($_POST['ht_page_id'][$menu_item_db_id]);
        } elseif (isset($_POST['ht_page_id_manual'][$menu_item_db_id]) && !empty($_POST['ht_page_id_manual'][$menu_item_db_id])) {
            $page_id = sanitize_text_field($_POST['ht_page_id_manual'][$menu_item_db_id]);
        }
        
        if (!empty($page_id)) {
            update_post_meta($menu_item_db_id, 'ht_page_id', $page_id);
        } else {
            delete_post_meta($menu_item_db_id, 'ht_page_id');
        }
        
        // Save layout settings
        if (isset($_POST['ht_menu_width'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_menu_width', sanitize_text_field($_POST['ht_menu_width'][$menu_item_db_id]));
        }
        
        if (isset($_POST['ht_custom_width'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_custom_width', sanitize_text_field($_POST['ht_custom_width'][$menu_item_db_id]));
        }
        
        if (isset($_POST['ht_menu_position'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_menu_position', sanitize_text_field($_POST['ht_menu_position'][$menu_item_db_id]));
        }
        
        // Save style settings
        if (isset($_POST['ht_bg_color'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_bg_color', sanitize_hex_color($_POST['ht_bg_color'][$menu_item_db_id]));
        }
        
        if (isset($_POST['ht_border_color'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_border_color', sanitize_hex_color($_POST['ht_border_color'][$menu_item_db_id]));
        }
        
        if (isset($_POST['ht_border_radius'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_border_radius', absint($_POST['ht_border_radius'][$menu_item_db_id]));
        }
        
        if (isset($_POST['ht_border_width'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_border_width', absint($_POST['ht_border_width'][$menu_item_db_id]));
        }
        
        if (isset($_POST['ht_padding'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_padding', absint($_POST['ht_padding'][$menu_item_db_id]));
        }
        
        if (isset($_POST['ht_margin_top'][$menu_item_db_id])) {
            update_post_meta($menu_item_db_id, 'ht_margin_top', absint($_POST['ht_margin_top'][$menu_item_db_id]));
        }
    }
    
    /**
     * Modify menu items to add mega menu content
     */
    public function ht_modify_menu_items($items, $args) {
        foreach ($items as $item) {
            $is_mega_menu = get_post_meta($item->ID, 'ht_is_mega_menu', true);
            
            if ($is_mega_menu) {
                $content_type = get_post_meta($item->ID, 'ht_content_type', true);
                $mega_content = false;
                
                // Get content based on type
                if ($content_type === 'page') {
                    $page_id = get_post_meta($item->ID, 'ht_page_id', true);
                    if ($page_id) {
                        $mega_content = $this->ht_get_page_content($page_id);
                    }
                } else {
                    // Default to pattern
                    $pattern_id = get_post_meta($item->ID, 'ht_pattern_id', true);
                    if ($pattern_id) {
                        $mega_content = $this->ht_get_block_pattern_content($pattern_id);
                    }
                }
                
                if ($mega_content) {
                    // Add mega menu class and content
                    $item->classes[] = 'ht-has-mega-menu';
                    
                    // Add custom classes for layout
                    $menu_width = get_post_meta($item->ID, 'ht_menu_width', true) ?: 'default';
                    $menu_position = get_post_meta($item->ID, 'ht_menu_position', true) ?: 'center';
                    
                    $item->classes[] = 'ht-mega-width-' . $menu_width;
                    $item->classes[] = 'ht-mega-position-' . $menu_position;
                    
                    // Store all custom data
                    $item->ht_mega_menu_content = $mega_content;
                    $item->ht_menu_width = $menu_width;
                    $item->ht_custom_width = get_post_meta($item->ID, 'ht_custom_width', true) ?: '1200';
                    $item->ht_menu_position = $menu_position;
                    
                    // Style data
                    $item->ht_bg_color = get_post_meta($item->ID, 'ht_bg_color', true);
                    $item->ht_border_radius = get_post_meta($item->ID, 'ht_border_radius', true) ?: '8';
                    $item->ht_border_width = get_post_meta($item->ID, 'ht_border_width', true) ?: '1';
                    $item->ht_border_color = get_post_meta($item->ID, 'ht_border_color', true) ?: '#e0e0e0';
                    $item->ht_padding = get_post_meta($item->ID, 'ht_padding', true) ?: '30';
                    $item->ht_margin_top = get_post_meta($item->ID, 'ht_margin_top', true) ?: '10';
                }
            }
        }
        
        return $items;
    }
    
    /**
     * Get block pattern content by ID
     */
    private function ht_get_block_pattern_content($pattern_id) {
        $pattern_post = get_post($pattern_id);
        
        if (!$pattern_post || $pattern_post->post_type !== 'wp_block') {
            return false;
        }
        
        // Parse and render the blocks
        $blocks = parse_blocks($pattern_post->post_content);
        $rendered_content = '';
        
        foreach ($blocks as $block) {
            $rendered_content .= render_block($block);
        }
        
        return $rendered_content;
    }
    
    /**
     * Get page content by ID
     */
    private function ht_get_page_content($page_id) {
        $page = get_post($page_id);
        
        if (!$page || $page->post_type !== 'page' || $page->post_status !== 'publish') {
            return false;
        }
        
        // Get the page content
        $content = $page->post_content;
        
        // If using Gutenberg blocks
        if (has_blocks($content)) {
            $blocks = parse_blocks($content);
            $rendered_content = '';
            
            foreach ($blocks as $block) {
                $rendered_content .= render_block($block);
            }
            
            return $rendered_content;
        } else {
            // For classic editor or page builders
            // Apply content filters to process shortcodes, etc.
            return apply_filters('the_content', $content);
        }
    }
    
    /**
     * Enqueue admin assets
     */
    public function ht_enqueue_admin_assets($hook) {
        if ($hook === 'nav-menus.php') {
            ?>
            <script>
            jQuery(document).ready(function($) {
                // Function to handle mega menu toggle
                function handleMegaMenuToggle($checkbox) {
                    var $container = $checkbox.closest('.ht-mega-menu-fields');
                    var $contentOptions = $container.find('.ht-mega-menu-content-options');
                    
                    if ($checkbox.is(':checked')) {
                        $contentOptions.slideDown(200);
                        // Trigger content type change
                        handleContentTypeChange($container.find('.ht-content-type-selector'));
                    } else {
                        $contentOptions.slideUp(200);
                    }
                }
                
                // Function to handle content type change
                function handleContentTypeChange($selector) {
                    if (!$selector.length) return;
                    
                    var contentType = $selector.val();
                    var $container = $selector.closest('.ht-mega-menu-fields');
                    var $patternField = $container.find('.ht-mega-menu-pattern-field');
                    var $pageField = $container.find('.ht-mega-menu-page-field');
                    
                    // Hide all first with no animation
                    $patternField.hide();
                    $pageField.hide();
                    
                    // Show the selected one
                    if (contentType === 'pattern') {
                        $patternField.fadeIn(200);
                    } else if (contentType === 'page') {
                        $pageField.fadeIn(200);
                    }
                }
                
                // Bind events using event delegation
                $(document).on('change', '.ht-mega-menu-checkbox', function(e) {
                    e.stopPropagation();
                    handleMegaMenuToggle($(this));
                });
                
                $(document).on('change', '.ht-content-type-selector', function(e) {
                    e.stopPropagation();
                    handleContentTypeChange($(this));
                });
                
                // Page ID syncing
                $(document).on('input change', '.ht-page-id-manual', function() {
                    var pageId = $(this).val();
                    var $select = $(this).closest('.ht-mega-menu-page-field').find('.ht-page-select');
                    $select.val(pageId);
                });
                
                $(document).on('change', '.ht-page-select', function() {
                    var pageId = $(this).val();
                    var $input = $(this).closest('.ht-mega-menu-page-field').find('.ht-page-id-manual');
                    $input.val(pageId);
                });
                
                // Initialize function
                function initializeAllFields() {
                    $('.ht-mega-menu-checkbox').each(function() {
                        var $checkbox = $(this);
                        if ($checkbox.is(':checked')) {
                            var $container = $checkbox.closest('.ht-mega-menu-fields');
                            $container.find('.ht-mega-menu-content-options').show();
                            handleContentTypeChange($container.find('.ht-content-type-selector'));
                        }
                    });
                }
                
                // Initialize on load
                initializeAllFields();
                
                // Re-initialize when menu items are added or modified
                if (typeof wpNavMenu !== 'undefined') {
                    var originalAddMenuItemToBottom = wpNavMenu.addMenuItemToBottom;
                    wpNavMenu.addMenuItemToBottom = function() {
                        originalAddMenuItemToBottom.apply(this, arguments);
                        setTimeout(initializeAllFields, 500);
                    };
                }
                
                // Re-initialize when menu item is clicked
                $(document).on('click', '.menu-item-bar', function() {
                    setTimeout(initializeAllFields, 100);
                });
                
                // Watch for WordPress admin ajax complete
                $(document).ajaxComplete(function(event, xhr, settings) {
                    if (settings.data && settings.data.indexOf('action=add-menu-item') !== -1) {
                        setTimeout(initializeAllFields, 500);
                    }
                });
                
                // Force re-initialization periodically for dynamic content
                setInterval(function() {
                    $('.ht-mega-menu-checkbox:visible').each(function() {
                        var $checkbox = $(this);
                        var $container = $checkbox.closest('.ht-mega-menu-fields');
                        var $contentOptions = $container.find('.ht-mega-menu-content-options');
                        
                        // Check if state is correct
                        if ($checkbox.is(':checked') && !$contentOptions.is(':visible')) {
                            $contentOptions.show();
                            handleContentTypeChange($container.find('.ht-content-type-selector'));
                        }
                    });
                }, 1000);
            });
            </script>
            <style>
            .ht-mega-menu-fields {
                border: 1px solid #ddd;
            }
            .ht-mega-menu-fields label {
                font-weight: normal;
            }
            .ht-mega-menu-fields strong {
                font-weight: 600;
            }
            .ht-mega-menu-content-options {
                border-left: 3px solid #0073aa;
                padding-left: 10px;
            }
            .ht-content-field {
                transition: all 0.2s ease;
            }
            /* Ensure fields are properly hidden initially */
            .ht-mega-menu-pattern-field,
            .ht-mega-menu-page-field {
                display: none;
            }
            </style>
            <?php
        }
    }
    
    /**
     * Enqueue frontend assets
     */
    public function ht_enqueue_frontend_assets() {
        ?>
        <style>
        /* Blocksy Theme Specific Arrow Icon for Mega Menu */
        
        /* Force arrow icon for Blocksy theme mega menu items */
        .ht-has-mega-menu > a {
            position: relative !important;
        }
        
        /* Add SVG arrow icon that matches Blocksy's style */
        .ht-has-mega-menu > a .ht-dropdown-arrow {
            display: inline-block;
            margin-left: 5px;
            width: 14px;
            height: 14px;
            vertical-align: middle;
            transition: transform 0.3s ease;
        }
        
        /* Rotate arrow on hover */
        .ht-has-mega-menu:hover > a .ht-dropdown-arrow {
            transform: rotate(180deg);
        }
        
        /* For Blocksy header specifically */
        header[data-id="header"] .ht-has-mega-menu > a,
        .site-header .ht-has-mega-menu > a,
        .ct-header .ht-has-mega-menu > a {
            display: flex !important;
            align-items: center !important;
            gap: 5px;
        }
        
        /* CSS arrow fallback if SVG doesn't work */
        .ht-has-mega-menu > a::after {
            content: '';
            display: inline-block;
            width: 0;
            height: 0;
            margin-left: 8px;
            vertical-align: middle;
            border-top: 4px solid currentColor;
            border-right: 4px solid transparent;
            border-left: 4px solid transparent;
            transition: transform 0.3s ease;
        }
        
        /* Hide CSS arrow if SVG is present */
        .ht-has-mega-menu > a:has(.ht-dropdown-arrow)::after {
            display: none;
        }
        
        /* Ensure arrow color matches menu text */
        .ht-has-mega-menu > a .ht-dropdown-arrow svg {
            fill: currentColor;
        }
        
        /* Visual indicator - badge style */
        .ht-has-mega-menu > a .ht-mega-indicator {
            display: inline-block;
            background: #ff6900;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 3px;
            margin-left: 5px;
            text-transform: uppercase;
            font-weight: 600;
            vertical-align: middle;
        }
        
        .ht-has-mega-menu {
            position: relative;
        }
        
        .ht-mega-menu-dropdown {
            position: absolute;
            top: 100%;
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            z-index: 9999;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            padding: 30px;
            margin-top: 10px;
            min-width: 800px;
            max-width: 1200px;
        }
        
        /* Position variations */
        .ht-mega-menu-dropdown.ht-mega-position-left {
            left: 0;
            transform: translateY(-10px);
        }
        
        .ht-mega-menu-dropdown.ht-mega-position-center {
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
        }
        
        .ht-mega-menu-dropdown.ht-mega-position-right {
            right: 0;
            left: auto;
            transform: translateY(-10px);
        }
        
        /* Width variations */
        .ht-mega-menu-dropdown.ht-mega-width-full {
            left: 0;
            right: 0;
            width: 100vw;
            max-width: none;
            position: fixed;
            transform: translateY(-10px);
        }
        
        .ht-mega-menu-dropdown.ht-mega-width-custom {
            /* Custom width will be set inline */
        }
        
        /* Arrow pointing to parent menu */
        .ht-mega-menu-dropdown::before {
            content: '';
            position: absolute;
            top: -10px;
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 10px solid #e0e0e0;
        }
        
        .ht-mega-menu-dropdown::after {
            content: '';
            position: absolute;
            top: -9px;
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 10px solid #fff;
        }
        
        /* Arrow position based on alignment */
        .ht-mega-menu-dropdown.ht-mega-position-left::before,
        .ht-mega-menu-dropdown.ht-mega-position-left::after {
            left: 20px;
        }
        
        .ht-mega-menu-dropdown.ht-mega-position-center::before,
        .ht-mega-menu-dropdown.ht-mega-position-center::after {
            left: 50%;
            transform: translateX(-50%);
        }
        
        .ht-mega-menu-dropdown.ht-mega-position-right::before,
        .ht-mega-menu-dropdown.ht-mega-position-right::after {
            right: 20px;
            left: auto;
        }
        
        /* Hover states */
        .ht-has-mega-menu:hover .ht-mega-menu-dropdown {
            opacity: 1;
            visibility: visible;
        }
        
        .ht-has-mega-menu:hover .ht-mega-menu-dropdown.ht-mega-position-left,
        .ht-has-mega-menu:hover .ht-mega-menu-dropdown.ht-mega-position-right {
            transform: translateY(0);
        }
        
        .ht-has-mega-menu:hover .ht-mega-menu-dropdown.ht-mega-position-center {
            transform: translateX(-50%) translateY(0);
        }
        
        .ht-has-mega-menu:hover .ht-mega-menu-dropdown.ht-mega-width-full {
            transform: translateY(0);
        }
        
        /* Prevent dropdown from closing when hovering over it */
        .ht-mega-menu-dropdown:hover {
            opacity: 1;
            visibility: visible;
        }
        
        /* Adjust for different themes */
        .site-header .ht-has-mega-menu .ht-mega-menu-dropdown,
        .main-navigation .ht-has-mega-menu .ht-mega-menu-dropdown {
            position: absolute;
            top: 100%;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .ht-mega-menu-dropdown {
                position: static !important;
                min-width: auto;
                width: 100% !important;
                opacity: 1;
                visibility: visible;
                transform: none !important;
                box-shadow: none;
                border: none;
                padding: 15px;
                margin-top: 0;
                border-radius: 0;
                left: 0 !important;
                right: 0 !important;
            }
            
            .ht-mega-menu-dropdown::before,
            .ht-mega-menu-dropdown::after {
                display: none;
            }
            
            .ht-has-mega-menu > a::after,
            .ht-has-mega-menu > a:not(.has-fa)::after {
                float: right;
            }
        }
        </style>
        
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add mega menu dropdowns to menu items
            var htMegaMenuItems = document.querySelectorAll('.ht-has-mega-menu');
            
            htMegaMenuItems.forEach(function(item) {
                var link = item.querySelector('a');
                if (link && link.dataset.htMegaMenuContent) {
                    // Create dropdown
                    var dropdown = document.createElement('div');
                    dropdown.className = 'ht-mega-menu-dropdown';
                    
                    // Apply custom styles from data attributes
                    var menuWidth = link.dataset.menuWidth || 'default';
                    var customWidth = link.dataset.customWidth || '1200';
                    var menuPosition = link.dataset.menuPosition || 'center';
                    var bgColor = link.dataset.bgColor || '#ffffff';
                    var borderRadius = link.dataset.borderRadius || '8';
                    var borderWidth = link.dataset.borderWidth || '1';
                    var borderColor = link.dataset.borderColor || '#e0e0e0';
                    var padding = link.dataset.padding || '30';
                    var marginTop = link.dataset.marginTop || '10';
                    
                    // Add position class
                    dropdown.classList.add('ht-mega-position-' + menuPosition);
                    dropdown.classList.add('ht-mega-width-' + menuWidth);
                    
                    // Apply inline styles
                    dropdown.style.backgroundColor = bgColor;
                    dropdown.style.borderRadius = borderRadius + 'px';
                    dropdown.style.borderWidth = borderWidth + 'px';
                    dropdown.style.borderColor = borderColor;
                    dropdown.style.borderStyle = 'solid';
                    dropdown.style.padding = padding + 'px';
                    dropdown.style.marginTop = marginTop + 'px';
                    
                    // Apply width based on setting
                    if (menuWidth === 'full') {
                        // Full width is handled by CSS class
                    } else if (menuWidth === 'custom') {
                        dropdown.style.width = customWidth + 'px';
                        dropdown.style.maxWidth = customWidth + 'px';
                        dropdown.style.minWidth = customWidth + 'px';
                    }
                    
                    // Set content
                    dropdown.innerHTML = link.dataset.htMegaMenuContent;
                    item.appendChild(dropdown);
                    
                    // Adjust position for edge cases
                    setTimeout(function() {
                        adjustDropdownPosition(dropdown, menuPosition);
                    }, 100);
                }
            });
            
            // Function to adjust dropdown position if it goes off-screen
            function adjustDropdownPosition(dropdown, position) {
                var rect = dropdown.getBoundingClientRect();
                var windowWidth = window.innerWidth;
                
                // Check if dropdown goes off-screen and adjust
                if (position === 'left' && rect.right > windowWidth) {
                    dropdown.style.left = 'auto';
                    dropdown.style.right = '0';
                } else if (position === 'right' && rect.left < 0) {
                    dropdown.style.right = 'auto';
                    dropdown.style.left = '0';
                }
            }
            
            // Handle keyboard navigation
            var megaLinks = document.querySelectorAll('.ht-has-mega-menu > a');
            megaLinks.forEach(function(link) {
                link.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        var dropdown = this.nextElementSibling;
                        if (dropdown && dropdown.classList.contains('ht-mega-menu-dropdown')) {
                            dropdown.style.opacity = dropdown.style.opacity === '1' ? '0' : '1';
                            dropdown.style.visibility = dropdown.style.visibility === 'visible' ? 'hidden' : 'visible';
                        }
                    }
                });
            });
            
            // Reposition on window resize
            var resizeTimer;
            window.addEventListener('resize', function() {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function() {
                    document.querySelectorAll('.ht-mega-menu-dropdown').forEach(function(dropdown) {
                        var link = dropdown.previousElementSibling;
                        if (link) {
                            var position = link.dataset.menuPosition || 'center';
                            adjustDropdownPosition(dropdown, position);
                        }
                    });
                }, 250);
            });
        });
        </script>
        <?php
    }
}

// Initialize the plugin
new HT_MegaMenuPlugin();

// Hook into wp_nav_menu to add mega menu content to menu items
add_filter('walker_nav_menu_start_el', 'ht_add_mega_menu_content', 10, 4);

function ht_add_mega_menu_content($item_output, $item, $depth, $args) {
    // Only process top-level items
    if ($depth === 0 && in_array('ht-has-mega-menu', $item->classes)) {
        // Add mega menu content as data attribute for JavaScript to handle
        if (isset($item->ht_mega_menu_content)) {
            $item_output = str_replace('<a ', '<a data-ht-mega-menu-content="' . esc_attr($item->ht_mega_menu_content) . '" ', $item_output);
            
            // Add custom style data attributes
            $custom_attrs = '';
            if (isset($item->ht_menu_width)) {
                $custom_attrs .= ' data-menu-width="' . esc_attr($item->ht_menu_width) . '"';
            }
            if (isset($item->ht_custom_width)) {
                $custom_attrs .= ' data-custom-width="' . esc_attr($item->ht_custom_width) . '"';
            }
            if (isset($item->ht_menu_position)) {
                $custom_attrs .= ' data-menu-position="' . esc_attr($item->ht_menu_position) . '"';
            }
            if (isset($item->ht_bg_color) && !empty($item->ht_bg_color)) {
                $custom_attrs .= ' data-bg-color="' . esc_attr($item->ht_bg_color) . '"';
            }
            if (isset($item->ht_border_radius)) {
                $custom_attrs .= ' data-border-radius="' . esc_attr($item->ht_border_radius) . '"';
            }
            if (isset($item->ht_border_width)) {
                $custom_attrs .= ' data-border-width="' . esc_attr($item->ht_border_width) . '"';
            }
            if (isset($item->ht_border_color)) {
                $custom_attrs .= ' data-border-color="' . esc_attr($item->ht_border_color) . '"';
            }
            if (isset($item->ht_padding)) {
                $custom_attrs .= ' data-padding="' . esc_attr($item->ht_padding) . '"';
            }
            if (isset($item->ht_margin_top)) {
                $custom_attrs .= ' data-margin-top="' . esc_attr($item->ht_margin_top) . '"';
            }
            
            $item_output = str_replace('<a ', '<a' . $custom_attrs . ' ', $item_output);
        }
        
        // Check if arrow already exists to avoid duplication
        if (strpos($item_output, 'ht-dropdown-arrow') === false) {
            // Add arrow icon to the menu item
            $arrow_svg = '<span class="ht-dropdown-arrow"><svg viewBox="0 0 12 12" fill="currentColor"><path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg></span>';
            
            // Insert arrow before closing </a> tag
            $item_output = str_replace('</a>', $arrow_svg . '</a>', $item_output);
        }
    }
    
    return $item_output;
}

// Remove the duplicate filter - commenting it out
// add_filter('nav_menu_item_title', 'ht_add_arrow_to_mega_menu_title', 10, 4);
?>