<?php
/**
 * This template can be overridden by copying it to `bighearts[-child]/bighearts-core/elementor/templates/wgl-blog-hero.php`.
 */
namespace WglAddons\Templates;

defined('ABSPATH') || exit; // Abort, if called directly.

use WglAddons\Includes\{
    Wgl_Loop_Settings,
    Wgl_Carousel_Settings
};

/**
 * WGL Elementor Blog Hero Template
 *
 *
 * @package bighearts-core\includes\elementor
 * @author WebGeniusLab <webgeniuslab@gmail.com>
 * @since 1.0.0
 */
class WglBlogHero
{
    private static $instance;

    public static function get_instance()
    {
        if ( null == self::$instance ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function render($atts)
    {
        extract($atts);

        list($query_args) = Wgl_Loop_Settings::buildQuery($atts);

        //* Add Page to Query
        global $paged;
        if (empty($paged)) {
            $paged = (get_query_var('page')) ? get_query_var('page') : 1;
        }
        $query_args['paged'] = $paged;

        //* Add Optimized SQL
        if ($navigation_type == 'none') {
            $query_args['no_found_rows'] = true;
        }

        $query_args['update_post_term_cache'] = false; //* don't retrieve post terms
        $query_args['update_post_meta_cache'] = false; //* don't retrieve post meta

        $query = Wgl_Loop_Settings::cache_query($query_args);

        //* Render Items blog
        $wgl_def_atts = [
            'query' => $query,
            //* General
            'blog_layout' => '',
            'blog_title' => '',
            'blog_subtitle' => '',
            //* Content
            'blog_columns' => '',
            'hide_media' => '',
            'hide_content' => '',
            'hide_blog_title' => '',
            'hide_share' => $hide_share,
            'hide_views' => true,
            'hide_all_meta' => '',
            'meta_author' => '',
            'meta_comments' => '',
            'meta_categories' => '',
            'meta_date' => '',
            'hide_likes' => $hide_likes,
            'read_more_hide' => $read_more_hide,
            'read_more_text' => '',
            'content_letter_count' => '',
            'heading_tag' => '',
            'items_load' => $items_load,
            'load_more_text' => $load_more_text,
            'blog_style' => 'hero',
        ];

        global $wgl_blog_atts;
        $wgl_blog_atts = array_merge($wgl_def_atts, array_intersect_key($atts, $wgl_def_atts));
        ob_start();

        get_template_part('templates/post/post', 'hero');

        $blog_items = ob_get_clean();

        // Render row class
        $row_class = '';

        wp_enqueue_script('imagesloaded');
        if ($blog_layout == 'masonry') {
            // Call Wordpress Isotope
            wp_enqueue_script('isotope', WGL_ELEMENTOR_ADDONS_URL . 'assets/js/isotope.pkgd.min.js');
            $row_class .= 'blog_masonry';
        }

        $kses_allowed_html = [
            'a' => [
                'id'  => true, 'class' => true, 'style' => true,
                'href' => true, 'title' => true,
                'rel' => true, 'target' => true,
            ],
            'br' => ['id'  => true, 'class' => true, 'style' => true],
            'em' => ['id'  => true, 'class' => true, 'style' => true],
            'strong' => ['id'  => true, 'class' => true, 'style' => true],
        ];

        // Options for carousel
        if ($blog_layout == 'carousel') {
            switch ($blog_columns) {
                case '6':  $item_grid = 2; break;
                case '3':  $item_grid = 4; break;
                case '4':  $item_grid = 3; break;
                case '12': $item_grid = 1; break;
                default:   $item_grid = 6; break;
            }

            $carousel_options_arr = [
                'slide_to_show' => $item_grid,
                'autoplay' => $autoplay,
                'autoplay_speed' => $autoplay_speed,
                'use_pagination' => $use_pagination,
                'use_navigation' => $use_navigation,
                'use_prev_next' => $use_navigation ? true : false,
                'pag_type' => $pag_type,
                'pag_offset' => $pag_offset,
                'custom_pag_color' => $custom_pag_color,
                'pag_color' => $pag_color,
                'custom_resp' => $custom_resp,
                'resp_medium' => $resp_medium,
                'resp_medium_slides' => $resp_medium_slides,
                'resp_tablets' => $resp_tablets,
                'resp_tablets_slides' => $resp_tablets_slides,
                'resp_mobile' => $resp_mobile,
                'resp_mobile_slides' => $resp_mobile_slides,
                'adaptive_height' => true,
            ];

            $blog_items = Wgl_Carousel_Settings::init($carousel_options_arr, $blog_items, false);

            $row_class = ' blog_carousel';
            if(!empty($blog_title) || !empty($blog_title)) {
                $row_class .= ' blog_carousel_title-arrow';
            }
        }

        // Row class for grid and massonry
        if ( in_array($blog_layout, ['grid', 'masonry']) ) {

            switch ( $blog_columns ) {
                case '12': $row_class .= ' blog_columns-1'; break;
                case '6':  $row_class .= ' blog_columns-2'; break;
                case '4':  $row_class .= ' blog_columns-3'; break;
                case '3':  $row_class .= ' blog_columns-4'; break;
            }
            $row_class .= ' '.$blog_layout;
        }
        $row_class .= " blog-style-hero";
        // Render wraper
        if ($query->have_posts()): ?>
            <section class="wgl_cpt_section">
                <div class="blog-posts">
                    <?php
                    if(!empty($blog_title) || !empty($blog_subtitle)) {
                        echo '<div class="wgl_module_title item_title">';
                        if (!empty($blog_title)) echo '<h3 class="bighearts_module_title blog_title">'.wp_kses( $blog_title, $kses_allowed_html ).'</h3>';
                        if (!empty($blog_subtitle)) echo '<p class="blog_subtitle">'.wp_kses( $blog_subtitle, $kses_allowed_html ).'</p>';

                        if ($blog_layout == 'carousel' && (bool) $use_navigation) {
                            echo '<div class="carousel_arrows"><span class="left_slick_arrow"><span></span></span><span class="right_slick_arrow"><span></span></span></div>';
                        }
                        echo '</div>';
                    }
                    echo '<div class="container-grid row '. esc_attr($row_class) .'">';
                        echo \BigHearts_Theme_Helper::render_html($blog_items);
                    echo '</div>';
                    ?>
                </div>
        <?php

        if ($navigation_type == 'pagination') {
            echo \BigHearts_Theme_Helper::pagination($query, $navigation_align);
        }

        if ($navigation_type == 'load_more') {
            $wgl_blog_atts['post_count'] = $query->post_count;
            $wgl_blog_atts['query_args'] = $query_args;
            $wgl_blog_atts['atts'] = $atts;
            echo \BigHearts_Theme_Helper::load_more($wgl_blog_atts, $load_more_text);
        }
            echo '</section>';
        endif;

        // Clear global var
        unset($wgl_blog_atts);
    }

}