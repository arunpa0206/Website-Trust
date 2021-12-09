<?php

defined('ABSPATH') || exit;

/**
* BigHearts Theme Support
*
*
* @class        BigHearts_Theme_Support
* @version      1.0
* @category     Class
* @author       WebGeniusLab
*/

if (! class_exists('BigHearts_Theme_Support')) {
    class BigHearts_Theme_Support
    {

        private static $instance = null;

        public static function get_instance()
        {
            if (null == self::$instance) {
                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct()
        {
            if (function_exists('add_theme_support')) {
                add_theme_support('post-thumbnails', ['post', 'page', 'port', 'team', 'testimonials', 'product', 'gallery']);
                add_theme_support('automatic-feed-links');
                add_theme_support('revisions');
                add_theme_support('post-formats', ['gallery', 'video', 'quote', 'audio', 'link']);
            }

            // Register Nav Menu
            add_action('init', [$this, 'register_my_menus']);
            // Add translation file
            add_action('init', [$this, 'enqueue_translation_files']);
            // Add widget support
            add_action('widgets_init', [$this, 'sidebar_register']);
        }

        public function register_my_menus()
        {
            register_nav_menus( [
                'main_menu' => esc_html__('Main menu', 'bighearts')
            ] );
        }

        public function enqueue_translation_files()
        {
            load_theme_textdomain('bighearts', get_template_directory() . '/languages/');
        }

        public function sidebar_register()
        {
            // Get List of registered sidebar
            $custom_sidebars = BigHearts_Theme_Helper::get_option('sidebars');

            // Default wrapper for widget and title
            $wrapper_before = '<div id="%1$s" class="widget bighearts_widget %2$s">';
            $wrapper_after = '</div>';
            $title_before = '<div class="title-wrapper"><span class="title">';
            $title_after = '</span><span class="title__line"></span></div>';

            // Register custom sidebars
            if (!empty($custom_sidebars)) {
                foreach ($custom_sidebars as $single) {
                    register_sidebar([
                        'name' => esc_attr($single),
                        'id' => 'sidebar_' . esc_attr(strtolower(preg_replace('/[^A-Za-z0-9\-]/', '', str_replace(' ', '-', $single)))),
                        'description' => esc_html__('Add widget here to appear it in custom sidebar.', 'bighearts'),
                        'before_widget' => $wrapper_before,
                        'after_widget' => $wrapper_after,
                        'before_title' => $title_before,
                        'after_title' => $title_after,
                    ]);
                }
            }

            // Register Footer Sidebar
            $footer_columns = [
                [
                    'name' => esc_html__('Footer Column 1', 'bighearts'),
                    'id' => 'footer_column_1'
                ], [
                    'name' => esc_html__('Footer Column 2', 'bighearts'),
                    'id' => 'footer_column_2'
                ], [
                    'name' => esc_html__('Footer Column 3', 'bighearts'),
                    'id' => 'footer_column_3'
                ], [
                    'name' => esc_html__('Footer Column 4', 'bighearts'),
                    'id' => 'footer_column_4'
                ],
            ];

            foreach ($footer_columns as $footer_column) {
                register_sidebar( [
                    'name' => $footer_column['name'],
                    'id' => $footer_column['id'],
                    'description' => esc_html__('This area will display in footer like a column. Add widget here to appear it in footer column.', 'bighearts'),
                    'before_widget' => $wrapper_before,
                    'after_widget' => $wrapper_after,
                    'before_title' => $title_before,
                    'after_title' => $title_after,
                ] );
            }
            if (class_exists('WooCommerce')) {
                $shop_sidebars = [
                    [
                        'name' => esc_html__('Shop Products', 'bighearts'),
                        'id' => 'shop_products'
                    ], [
                        'name' => esc_html__('Shop Single', 'bighearts'),
                        'id' => 'shop_single'
                    ], [
                        'name' => esc_html__('Shop Filter', 'bighearts'),
                        'id' => 'shop_filter'
                    ]
                ];
                foreach ($shop_sidebars as $shop_sidebar) {
                    register_sidebar( [
                        'name' => $shop_sidebar['name'],
                        'id' => $shop_sidebar['id'],
                        'description' => esc_html__('This sidebar will display in WooCommerce Pages.', 'bighearts'),
                        'before_widget' => $wrapper_before,
                        'after_widget' => $wrapper_after,
                        'before_title' => $title_before,
                        'after_title' => $title_after,
                    ] );
                }
            }

            register_sidebar( [
                'name' => esc_html__('Side Panel', 'bighearts'),
                'id' => 'side_panel',
                'before_widget' => $wrapper_before,
                'after_widget' => $wrapper_after,
                'before_title' => $title_before,
                'after_title' => $title_after,
            ] );
        }
    }

    new BigHearts_Theme_Support();

}
