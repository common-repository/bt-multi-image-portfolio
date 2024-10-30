<?php

/*
  Plugin Name: BT Multi-image Portfolio
  Plugin URI: https://plugin.bayatree.com/
  Version: 1.1.1
  Author: Bayatree
  Author URI: https://bayatree.com/
  Description: BT Multi-image Portfolio plugin allows you to add  portfolio items in multiple categories. Each item can have multiple images and URLs.
 */

if (!defined('ABSPATH'))
    exit;
define('BAYATREE_PORTFOLIO_DIR', plugin_dir_url(__FILE__));

class btPortfolio {

    function __construct() {
        //Register  portfolio_category_register  handler ...
        add_action('init', array($this, 'portfolio_category_register'));
        //Register  Portfolio Category
        add_action('init', array($this, 'portfolio_register'));
        //Modify listing page columns header 
        add_filter('manage_bayatree-portfolio_posts_columns', array($this, 'modify_columns_head'));
        //Modify listing page columns data 
        add_action('manage_bayatree-portfolio_posts_custom_column', array($this, 'modify_columns_content'));
        add_action('admin_enqueue_scripts', array(&$this, 'admin_script'));
        //Include gallery meta box ..
        add_action('admin_init', array(&$this, 'add_gallery_meta_box'));
        //Save portfolio images ...
        add_action('save_post', array(&$this, 'admin_add_update_portfolio_images'));
        //Include project url meta box ..
        add_action('admin_init', array(&$this, 'add_project_url_meta_box'));
        //save portfolio url 
        add_action('save_post', array(&$this, 'admin_add_update_project_url'));
        //Add custom css file         
        add_action('wp_enqueue_scripts', array(&$this, 'add_script'));
        add_action('admin_menu', array($this, 'add_setting_page'));
        add_action('admin_init', array($this, 'add_custom_setting_register'));
        add_action("wp_head", array($this, 'update_custom_setting'));
        //Create plugin short code to display portfolio on front end
        add_shortcode('BT_MULTI_IMAGES_PORTFOLIO', array(&$this, 'display_portfolio'));
    }

    /**
     * @usage Display portfolio on front end using short code(BT_PORTFOLIO)
     * @param null
     * @return null
     */
    function display_portfolio($atts) {
        $portfolioArr = [];
        $args = array('post_type' => 'bayatree-portfolio',
            'nopaging' => true, 'order' => 'ASC');
        $loop = new WP_Query($args);
        while ($loop->have_posts()) : $loop->the_post();
            //get portfolio catagories
            $catArr = get_the_terms($loop->post->ID, 'portfolio-category');
                $singlePortfolioArr = [];
                $singlePortfolioArr['title'] = $loop->post->post_title;
                $singlePortfolioArr['description'] = $loop->post->post_content;
                $singlePortfolioArr['thumbnail'] = [];
                $singlePortfolioArr['large'] = [];
                $singlePortfolioArr['tags'] = [];
                $singlePortfolioArr['project'] = [];
                //Get the portfolio images. 
                $images = get_post_meta($loop->post->ID, 'gallery_meta_box', true);
                if (!empty($images)) {
                    foreach ($images['image_url'] as $key => $image) {
                        $attachment_id = $this->get_attachment_id($image);
                        if ($attachment_id) {
                            //Get Thumbnial Image by attachment id
                            $thumbnail = wp_get_attachment_image_src($attachment_id, $size = 'thumbnail', $icon = false);
                            $singlePortfolioArr['thumbnail'][] = $thumbnail[0];
                        } else {
                            $singlePortfolioArr['thumbnail'][] = $image;
                        }
                        $singlePortfolioArr['large'][] = $image;
                    }
                }
                //If portfolio does not contain any image than display default image on front end.
                if (empty($singlePortfolioArr['large'])) {
                    $singlePortfolioArr['large'][] = plugins_url('/img/no-image.jpg', __FILE__);
                    $singlePortfolioArr['thumbnail'][] = plugins_url('/img/no-image-150x150.jpg', __FILE__);
                }
                //get project URL 
                $project_urls = get_post_meta($loop->post->ID, 'project_url_meta_box', true);
                if (!empty($project_urls)) {
                    $singlePortfolioArr['project'] = $project_urls;
                }
                if (!empty($catArr)) {
                //Display portfolio category
                foreach ($catArr as $key => $cat) {
                    $singlePortfolioArr['tags'][] = $cat->name;
                }
                }
                $portfolioArr[] = $singlePortfolioArr;
        endwhile;

        $portfolioJson = json_encode($portfolioArr);
        include ('tpl/template.php');
    }

    /**
     * @usage Get the attachment ID from the image URL
     * @param $image_url
     * @return attachment id
     */
    function get_attachment_id($image_url) {
        global $wpdb;
        $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url));
        if (!empty($attachment)) {
            return $attachment[0];
        }
    }

    /**
     * @usage Include css and js on fornt end
     * @param null
     * @return null
     */
    function add_script() {
        wp_register_script('js-script', 'https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js');
        // For either a plugin or a theme, you can then enqueue the script:
        wp_enqueue_script('js-script');
        // Register the style like this for a plugin:
        wp_register_style('style', BAYATREE_PORTFOLIO_DIR . 'css/style.css', array(), '20160623', 'all');
        wp_enqueue_style('style');
        wp_register_style('font-awesome.min', BAYATREE_PORTFOLIO_DIR . 'css/font-awesome.min.css', array(), '20160623', 'all');
        wp_enqueue_style('font-awesome.min');
        wp_register_style('lightslider-style', BAYATREE_PORTFOLIO_DIR . 'css/lightslider.css', array(), '20160623', 'all');
        wp_enqueue_style('lightslider-style');

        wp_register_script('custom-script', BAYATREE_PORTFOLIO_DIR . '/js/custom.js', array(), '1.0', true);
        // For either a plugin or a theme, you can then enqueue the script:
        wp_enqueue_script('custom-script');
        wp_register_script('lightslider', BAYATREE_PORTFOLIO_DIR . 'js/lightslider.js', array(), '1.0', true);
        wp_enqueue_script('lightslider');
    }

    /**
     * @usage Register portfolio-category taxonomy to create Portfolio Categories
     * @Reference https://codex.wordpress.org/Function_Reference/register_taxonomy
     * @param null
     * @return null
     */
    function portfolio_category_register() {
        $labels = array(
            'name' => _('Portfolio Categories'),
            'search_items' => __('Search Portfolio Categories'),
            'all_items' => __('All Portfolio Categories'),
            'edit_item' => __('Edit Portfolio Category'),
            'update_item' => __('Update Portfolio Category'),
            'add_new_item' => __('Add Portfolio Category'),
            'new_item_name' => __('New Portfolio Category Name'),
            'menu_name' => __('Portfolio Categories'),
        );
        register_taxonomy('portfolio-category', array('bayatree-portfolio'), array(
            'hierarchical' => true,
            'labels' => $labels,
            'show_ui' => true,
            'show_admin_column' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'portfolio-category'),
        ));
    }

    /**
     * @usage Register Post Type portfolio to create portfolio projects.
     * @Reference https://codex.wordpress.org/Function_Reference/register_post_type
     * @param null
     * @return $columns
     */
    function portfolio_register() {
        $labels = array(
            'name' => _('BT Multi-image Portfolio'),
            'singular_name' => ('BT Multi-image Portfolio'),
            'menu_name' => _('BT Multi-image Portfolio'),
            'add_new' => _('Add Portfolio item'),
            'add_new_item' => __('Add Portfolio item'),
            'new_item' => __('New Portfolio'),
            'edit_item' => __('Edit Portfolio item'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'view_item' => __('View Portfolio'),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => __('Edit Portfolio item'),
            'update_item' => __('Update Portfolio'),
            'add_new_item' => __('Add Portfolio item'),
            'all_items' => __('All Portfolio'),
            'search_items' => __('Search Portfolio'),
            'not_found' => __('No Portfolio found.'),
            'not_found_in_trash' => __('No Portfolio found in Trash.')
        );

        $args = array(
            'labels' => $labels,
            'public' => false, // used for permalink
            'publicly_queryable' => false, //used for previoew option
            'show_ui' => true,
            'show_in_menu' => true,
            'query_var' => true,
            'rewrite' => array('slug' => 'bayatree_portfolio'),
            'capability_type' => 'post',
            'has_archive' => true,
            'hierarchical' => true,
            'menu_position' => null,
            'supports' => array('title', 'editor', 'thumbnail'),
            'taxonomies' => array('bayatree-portfolio-category')
        );
        register_post_type('bayatree-portfolio', $args);
    }

    /**
     * @usage Modify listing page columns head. 
     * Column Head on Listing page: Portfolio Name,Portfolio Categories,Date
     * @Reference https://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column
     * @param null
     * @return $columns
     */
    function modify_columns_head() {
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __('Portfolio Name'),
            'portfolio-category' => __('Portfolio Categories'),
            'date' => __('Date')
        );
        return $columns;
    }

    /**
     * @usage Display portfolio categories on listing page. 
     * @Reference https://codex.wordpress.org/Plugin_API/Action_Reference/manage_$post_type_posts_custom_column
     * @param $column_name
     * @return null
     */
    function modify_columns_content($column_name) {
        global $post;
        if ($column_name == 'portfolio-category') {
            //Fetch portfolio category 
            $catArr = get_the_terms($post->ID, 'portfolio-category');
            if (!empty($catArr)) {
                //Display portfolio category
                $count = 0;
                foreach ($catArr as $key => $cat) {
                    $commmaSeparator = '';
                    if ($count != (count($catArr) - 1)) {
                        $commmaSeparator = ', ';
                    }
                    echo $cat->name . $commmaSeparator;
                    $count++;
                }
            }
        }
    }

    /**
     * @usage include css and js file on wp-admin pages.
     * @Reference https://developer.wordpress.org/reference/functions/wp_enqueue_style/
     * @param null
     * @return null
     */
    function admin_script() {
        //include css file on wp-admin pages
        wp_register_style('custom_wp_admin_css', BAYATREE_PORTFOLIO_DIR . '/css/admin-style.css', false, '1.0.0');
        wp_enqueue_style('custom_wp_admin_css');
        //include js file on wp-admin pages
        wp_enqueue_script('my_custom_script', BAYATREE_PORTFOLIO_DIR . '/js/admin-js.js');
        //add wordpress default color picker in plugin setting page 
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker');
    }

    /**
     * @usage Register add_project_url_meta_box Meta box to display project url input option 
     * Call admin_project_url_meta_box_option handler to display project url and their related favicon icons or images.
     * @param null
     * @return null
     * */
    function add_project_url_meta_box() {
        add_meta_box(
                'project_url_meta_box', 'Project URL', array(&$this, 'admin_project_url_meta_box_option'), 'bayatree-portfolio', 'normal', 'core'
        );
    }

    /**
     * @usage Fetch and display project url and their related favicon icons or images.
     * Call admin_display_gallery_meta_box_option handler to display gallery images.
     * @param null
     * @return null
     * */
    function admin_project_url_meta_box_option() {
        global $post;
        //Fetch project url and their related favicon icons or images.
        $project_data = get_post_meta($post->ID, 'project_url_meta_box', true);
        //Include admin_project_url.php file to display project url and their related favicon icons or images.
        include ('tpl/admin_project_url.php');
    }

    /**
     * @usage Register gallery_meta_box Meta box to display portfolio gallery input option 
     * Call admin_display_gallery_meta_box_option handler to display gallery images.
     * @param null
     * @return null
     * */
    function add_gallery_meta_box() {
        add_meta_box(
                'gallery_meta_box', 'Portfolio Gallery Images', array(&$this, 'admin_display_gallery_meta_box_option'), 'bayatree-portfolio', 'normal', 'core'
        );
    }

    /**
     * @usage Fetch and display portfolio gallery images.
     * include tpl/admin_gallery.php file to display gallery images.
     * @param null
     * @return null
     * */
    function admin_display_gallery_meta_box_option() {
        global $post;
        //Fetch portfolio gallery images.
        $gallery_data = get_post_meta($post->ID, 'gallery_meta_box', true);
        //include admin_gallery.php file to display portfolio gallery images.
        include ('tpl/admin_gallery.php');
    }

    /**
     * @usage Add, update and delete project url
     * @param null
     * @return null
     * */
    function admin_add_update_project_url() {
        global $post;
        if (isset($_POST['project'])) {
            if ($_POST['project']) {
                // Build array for saving post meta
                $project_data = array();
                $key = 0;
                for ($i = 0; $i < count($_POST['project']['project_url']); $i++) {
                    $project_url = $_POST['project']['project_url'][$i];
                    // Validate project url before save in post meta ..
                    if (filter_var($project_url, FILTER_VALIDATE_URL)) {
                        $project_data[$key]['project_url'] = $project_url;
                        $image_url = '';
                        // Validate image url before save in post meta ..
                        if ($_POST['project']['image_url'][$i]) {
                            $imageSize = getimagesize($_POST['project']['image_url'][$i]);
                            if (!empty($imageSize)) {
                                $image_url = $_POST['project']['image_url'][$i];
                            }
                        }
                        $project_data[$key]['image_url'] = $image_url;
                        $key++;
                    }
                }
                if ($project_data) {
                    update_post_meta($post->ID, 'project_url_meta_box', $project_data);
                } else {
                    delete_post_meta($post->ID, 'project_url_meta_box');
                }
            }
            // Nothing received, all fields are empty, delete all project url.
            else {
                delete_post_meta($post->ID, 'project_url_meta_box');
            }
        }
    }

    /**
     * @usage Add, update and delete portfolio images 
     * @param null
     * @return null
     * */
    function admin_add_update_portfolio_images() {
        global $post;
        if (isset($_POST['gallery'])) {
            if ($_POST['gallery']) {
                // Build array for saving post meta
                $gallery_data = array();
                for ($i = 0; $i < count($_POST['gallery']['image_url']); $i++) {
                    // Validate image url before save in post meta.                   
                    if ($_POST['gallery']['image_url'][$i]) {
                        $imageSize = getimagesize($_POST['gallery']['image_url'][$i]);
                        if (!empty($imageSize)) {
                            $gallery_data['image_url'][] = $_POST['gallery']['image_url'][$i];
                        }
                    }
                }
                if ($gallery_data) {
                    update_post_meta($post->ID, 'gallery_meta_box', $gallery_data);
                } else {
                    delete_post_meta($post->ID, 'gallery_meta_box');
                }
            } else {
                // Nothing received, all fields are empty, delete all portfolio images.
                delete_post_meta($post->ID, 'gallery_meta_box');
            }
        }
    }

    /**
     * @usage add submenu in plugin to display setting tab
     * @param null
     * @return null
     * */
    function add_setting_page() {
        add_submenu_page('edit.php?post_type=bayatree-portfolio', 'settings', 'Settings', 'manage_options', 'portfolio_settings', array(&$this, 'settings_page')
        );
    }

    /**
     * @usage save custom settings in datatabase using register setting function
     * @param null
     * @return null
     * */
    function add_custom_setting_register() {
        add_settings_section(
                'portfolio-plugin-css-settings', 'portfolio-plugin-css-settings-group', array($this, 'portfolio-plugin-css-settings-group'), 'portfolio-plugin-css-settings'
        );
        register_setting('portfolio-plugin-css-settings-group', 'portfolio-plugin-css-theme-color');
        register_setting('portfolio-plugin-css-settings-group', 'portfolio-plugin-css-font-color');
        register_setting('portfolio-plugin-css-settings-group', 'portfolio-plugin-css-menu-color');
        register_setting('portfolio-plugin-css-settings-group', 'portfolio-plugin-css-menu-hover-color');        
        register_setting('portfolio-plugin-css-settings-group', 'bt-portfolio-settings-show-hide-filter');
        register_setting('portfolio-plugin-css-settings-group', 'bt-portfolio-settings-on-off-auto-slider');
        register_setting('portfolio-plugin-css-settings-group', 'bt-portfolio-settings-speed-auto-slider',array($this, 'bt_portfolio_validate_numeric'));
        register_setting('portfolio-plugin-css-settings-group', 'bt-portfolio-settings-pause-timing-slider',array($this, 'bt_portfolio_validate_numeric'));

          
    }
    /**
     * @usage function used to validate numeric value.
     * @param $numeric
     * @return intval( $numeric );
     * */
    function bt_portfolio_validate_numeric( $numeric ) {       
        return intval( $numeric );
    }
    /**
     * @usage display setting page in admin side
     * @param null
     * @return null
     * */
    function settings_page() {
        include('tpl/settings.php');
    }

    /**
     * @usage update custom option page setting in front end
     * @param null
     * @return null
     * */
    function update_custom_setting() {
        include('tpl/update_custom_option.php');
    }

}

new btPortfolio;

