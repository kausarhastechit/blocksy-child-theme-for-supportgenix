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
        ?>
        <div class="ht-mega-menu-fields" style="margin-top: 10px;">
            <p class="description">
                <label>
                    <input type="checkbox" name="ht_is_mega_menu[<?php echo $item_id; ?>]" value="1" <?php checked($is_mega_menu, '1'); ?> class="ht-mega-menu-checkbox" data-item-id="<?php echo $item_id; ?>">
                    Enable Mega Menu
                </label>
            </p>
            <p class="description ht-mega-menu-pattern-field" style="<?php echo $is_mega_menu ? '' : 'display:none;'; ?>">
                <label>
                    Block Pattern ID:
                    <input type="text" name="ht_pattern_id[<?php echo $item_id; ?>]" value="<?php echo esc_attr($pattern_id); ?>" placeholder="e.g., 1026" style="width: 100px;">
                </label>
            </p>
        </div>
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
        
        // Save pattern ID
        if (isset($_POST['ht_pattern_id'][$menu_item_db_id])) {
            $pattern_id = sanitize_text_field($_POST['ht_pattern_id'][$menu_item_db_id]);
            if (!empty($pattern_id)) {
                update_post_meta($menu_item_db_id, 'ht_pattern_id', $pattern_id);
            } else {
                delete_post_meta($menu_item_db_id, 'ht_pattern_id');
            }
        }
    }
    
    /**
     * Modify menu items to add mega menu content
     */
    public function ht_modify_menu_items($items, $args) {
        foreach ($items as $item) {
            $is_mega_menu = get_post_meta($item->ID, 'ht_is_mega_menu', true);
            $pattern_id = get_post_meta($item->ID, 'ht_pattern_id', true);
            
            if ($is_mega_menu && $pattern_id) {
                // Get block pattern content
                $pattern_content = $this->ht_get_block_pattern_content($pattern_id);
                if ($pattern_content) {
                    // Add mega menu class and content
                    $item->classes[] = 'ht-has-mega-menu';
                    $item->ht_mega_menu_content = $pattern_content;
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
     * Enqueue admin assets
     */
    public function ht_enqueue_admin_assets($hook) {
        if ($hook === 'nav-menus.php') {
            ?>
            <script>
            jQuery(document).ready(function($) {
                // Toggle pattern ID field based on checkbox
                $(document).on('change', '.ht-mega-menu-checkbox', function() {
                    var $this = $(this);
                    var $patternField = $this.closest('.ht-mega-menu-fields').find('.ht-mega-menu-pattern-field');
                    
                    if ($this.is(':checked')) {
                        $patternField.show();
                    } else {
                        $patternField.hide();
                    }
                });
            });
            </script>
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
            left: 50%;
            transform: translateX(-50%) translateY(-10px);
            min-width: 800px;
            max-width: 1200px;
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
        }
        
        /* Arrow pointing to parent menu */
        .ht-mega-menu-dropdown::before {
            content: '';
            position: absolute;
            top: -10px;
            left: 50%;
            transform: translateX(-50%);
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
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 10px solid transparent;
            border-right: 10px solid transparent;
            border-bottom: 10px solid #fff;
        }
        
        .ht-has-mega-menu:hover .ht-mega-menu-dropdown {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        
        /* Prevent dropdown from closing when hovering over it */
        .ht-mega-menu-dropdown:hover {
            opacity: 1;
            visibility: visible;
            transform: translateX(-50%) translateY(0);
        }
        
        /* Adjust for different themes */
        .site-header .ht-has-mega-menu .ht-mega-menu-dropdown,
        .main-navigation .ht-has-mega-menu .ht-mega-menu-dropdown {
            position: absolute;
            top: 100%;
        }
        
        /* Full width option */
        .ht-mega-menu-dropdown.full-width {
            left: 0;
            right: 0;
            transform: translateY(-10px);
            width: 100vw;
            max-width: none;
            position: fixed;
        }
        
        .ht-mega-menu-dropdown.full-width:hover {
            transform: translateY(0);
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .ht-mega-menu-dropdown {
                position: static;
                min-width: auto;
                width: 100%;
                opacity: 1;
                visibility: visible;
                transform: none;
                box-shadow: none;
                border: none;
                padding: 15px;
                margin-top: 0;
                border-radius: 0;
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
                    // Add arrow icon similar to Blocksy's dropdown arrow
                    if (!link.querySelector('.ht-dropdown-arrow')) {
                        var arrowSpan = document.createElement('span');
                        arrowSpan.className = 'ht-dropdown-arrow';
                        
                        // Create SVG arrow that matches Blocksy's style
                        arrowSpan.innerHTML = '<svg viewBox="0 0 15 15" fill="currentColor"><path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>';
                        
                        // Insert arrow after the link text
                        link.appendChild(arrowSpan);
                    }
                    
                    // Create dropdown
                    var dropdown = document.createElement('div');
                    dropdown.className = 'ht-mega-menu-dropdown';
                    dropdown.innerHTML = link.dataset.htMegaMenuContent;
                    item.appendChild(dropdown);
                }
            });
            
            // For Blocksy theme compatibility - inject arrows into menu structure
            function injectBlocksyArrows() {
                // Target Blocksy menu items
                var blocksyMenuItems = document.querySelectorAll('.ct-menu-item.ht-has-mega-menu > a, .menu-item.ht-has-mega-menu > a');
                
                blocksyMenuItems.forEach(function(link) {
                    if (!link.querySelector('.ht-dropdown-arrow') && !link.querySelector('svg')) {
                        var arrowSpan = document.createElement('span');
                        arrowSpan.className = 'ht-dropdown-arrow';
                        arrowSpan.innerHTML = '<svg viewBox="0 0 15 15" fill="currentColor"><path d="M2.5 4.5L6 8L9.5 4.5" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>';
                        link.appendChild(arrowSpan);
                    }
                });
            }
            
            // Run arrow injection
            injectBlocksyArrows();
            
            // Also run after a slight delay for dynamic menus
            setTimeout(injectBlocksyArrows, 100);
            
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
        }
    }
    
    return $item_output;
}
?>