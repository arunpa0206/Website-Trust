<?php
/**
 * This template can be overridden by copying it to `bighearts[-child]/bighearts-core/elementor/widgets/wgl-blog-hero.php`.
 */
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography,
    Group_Control_Background
};
use WglAddons\{
    BigHearts_Global_Variables as BigHearts_Globals,
    Includes\Wgl_Loop_Settings,
    Templates\WglBlogHero
};

class Wgl_Blog_Hero extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-blog-hero';
    }

    public function get_title()
    {
        return esc_html__('WGL Blog Hero', 'bighearts-core');
    }

    public function get_icon()
    {
        return 'wgl-blog-hero';
    }

    public function get_script_depends()
    {
        return [
            'slick',
            'jarallax',
            'jarallax-video',
            'imagesloaded',
            'wgl-elementor-extensions-widgets',
        ];
    }

    public function get_categories()
    {
        return ['wgl-extensions'];
    }


    protected function register_controls()
    {
        $this->start_controls_section(
            'wgl_blog_section',
            ['label' => esc_html__('Settings', 'bighearts-core') ]
        );

        $this->add_control(
            'blog_title',
            [
                'label' => esc_html__('Title', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'blog_subtitle',
            [
                'label' => esc_html__('Sub Title', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'dynamic' => ['active' => true],
            ]
        );

        $this->add_control(
            'blog_columns',
            [
                'label' => esc_html__('Grid Columns Amount', 'bighearts-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '12' => esc_html__('1 (one)', 'bighearts-core'),
                    '6' => esc_html__('2 (two)', 'bighearts-core'),
                    '4' => esc_html__('3 (three)', 'bighearts-core'),
                    '3' =>esc_html__('4 (four)', 'bighearts-core')
                ],
                'default' => '4',
                'tablet_default' => 'inherit',
                'mobile_default' => '12',
                'frontend_available' => true,
                'label_block' => true,
            ]
        );

        $this->add_control(
            'blog_layout',
            [
                'label' => esc_html__('Layout', 'bighearts-core'),
                'type' => 'wgl-radio-image',
                'options' => [
                    'grid' => [
                        'title' => esc_html__('Grid', 'bighearts-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_grid.png',
                    ],
                    'masonry' => [
                        'title' => esc_html__('Masonry', 'bighearts-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_masonry.png',
                    ],
                    'carousel' => [
                        'title' => esc_html__('Carousel', 'bighearts-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/layout_carousel.png',
                    ],
                ],
                'default' => 'grid',
            ]
        );

        $this->add_control(
            'navigation_type',
            [
                'label' => esc_html__('Navigation Type', 'bighearts-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['blog_layout' => ['grid', 'masonry'] ],
                'options' => [
                    'none' => esc_html__('None', 'bighearts-core'),
                    'pagination' => esc_html__('Pagination', 'bighearts-core'),
                    'load_more' => esc_html__('Load More', 'bighearts-core'),
                ],
                'default' => 'none',
            ]
        );

        $this->add_control(
            'navigation_align',
            [
                'label' => esc_html__('Navigation\'s Alignment', 'bighearts-core'),
                'type' => Controls_Manager::SELECT,
                'condition' => ['navigation_type' => 'pagination'],
                'options' => [
                    'left' => esc_html__('Left', 'bighearts-core'),
                    'center' => esc_html__('Center', 'bighearts-core'),
                    'right' => esc_html__('Right', 'bighearts-core'),
                ],
                'default' => 'left',
            ]
        );

        $this->add_control(
            'items_load',
            [
                'label' => esc_html__('Items to be loaded', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'navigation_type' => 'load_more',
                    'blog_layout' => ['grid', 'masonry']
                ],
                'dynamic' => ['active' => true],
                'default' => esc_html__('4', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'load_more_text',
            [
                'label' => esc_html__('Button Text', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => [
                    'navigation_type' => 'load_more',
                    'blog_layout' => ['grid', 'masonry']
                ],
                'dynamic' => ['active' => true],
                'default' => esc_html__('Load More', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'spacer_load_more',
            [
                'label' => esc_html__('Button Spacer Top', 'bighearts-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => -20, 'max' => 200 ],
                ],
                'size_units' => ['px', 'em', 'rem', 'vw'],
                'condition' => [
                    'navigation_type' => 'load_more',
                    'blog_layout' => ['grid', 'masonry']
                ],
                'default' => ['size' => '30', 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .load_more_wrapper' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'display_section',
            ['label' => esc_html__('Display', 'bighearts-core') ]
        );

        $this->add_control(
            'hide_media',
            [
                'label' => esc_html__('Hide Media?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'hide_blog_title',
            [
                'label' => esc_html__('Hide Title?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'hide_content',
            [
                'label' => esc_html__('Hide Content?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'hide_all_meta',
            [
                'label' => esc_html__('Hide all post-meta?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'meta_author',
            [
                'label' => esc_html__('Hide post-meta author?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta!' => 'yes'],
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'meta_comments',
            [
                'label' => esc_html__('Hide post-meta comments?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta!' => 'yes'],
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'meta_categories',
            [
                'label' => esc_html__('Hide post-meta categories?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta!' => 'yes'],
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'meta_date',
            [
                'label' => esc_html__('Hide post-meta date?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['hide_all_meta!' => 'yes'],
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'hide_likes',
            [
                'label' => esc_html__('Hide Likes?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'hide_share',
            [
                'label' => esc_html__('Hide Post Share?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_hide',
            [
                'label' => esc_html__('Hide \'Read More\' button?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'read_more_text',
            [
                'label' => esc_html__('Read More Text', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'condition' => ['read_more_hide' => ''],
                'dynamic' => ['active' => true],
                'default' => esc_html__('Read More', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'content_letter_count',
            [
                'label' => esc_html__('Characters Amount in Content', 'bighearts-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['hide_content' => ''],
                'min' => 1,
                'default' => '115',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wgl_carousel_section',
            [
                'label' => esc_html__('Carousel Options', 'bighearts-core'),
                'condition' => ['blog_layout' => 'carousel']
            ]
        );

        $this->add_control(
            'autoplay',
            [
                'label' => esc_html__('Autoplay', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'autoplay_speed',
            [
                'label' => esc_html__('Autoplay Speed', 'bighearts-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['autoplay' => 'yes'],
                'min' => 1,
                'default' => '3000',
            ]
        );

        $this->add_control(
            'use_pagination',
            [
                'label' => esc_html__('Add Pagination control', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'pag_type',
            [
                'label' => esc_html__('Pagination Type', 'bighearts-core'),
                'type' => 'wgl-radio-image',
                'condition' => ['use_pagination' => 'yes'],
                'options' => [
                    'circle' => [
                        'title' => esc_html__('Circle', 'bighearts-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_circle.png',
                    ],
                    'circle_border' => [
                        'title' => esc_html__('Empty Circle', 'bighearts-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_circle_border.png',
                    ],
                    'square' => [
                        'title' => esc_html__('Square', 'bighearts-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_square.png',
                    ],
                    'square_border' => [
                        'title' => esc_html__('Empty Square', 'bighearts-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_square_border.png',
                    ],
                    'line' => [
                        'title' => esc_html__('Line', 'bighearts-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_line.png',
                    ],
                    'line_circle' => [
                        'title' => esc_html__('Line - Circle', 'bighearts-core'),
                        'image' => WGL_ELEMENTOR_ADDONS_URL . 'assets/img/wgl_elementor_addon/icons/pag_line_circle.png',
                    ],
                ],
                'default' => 'line_circle',
            ]
        );

        $this->add_control(
            'pag_offset',
            [
                'label' => esc_html__('Pagination Top Offset', 'bighearts-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['use_pagination' => 'yes'],
                'min' => 1,
                'default' => 10,
                'selectors' => [
                    '{{WRAPPER}} .wgl-carousel .slick-dots' => 'margin-top: {{VALUE}}px;',
                ],
            ]
        );

        $this->add_control(
            'custom_pag_color',
            [
                'label' => esc_html__('Custom Pagination Color', 'bighearts-core'),

                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'pag_color',
            [
                'label' => esc_html__('Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_primary_color(),
                'condition' => ['custom_pag_color' => 'yes'],
            ]
        );

        $this->add_control(
            'use_navigation',
            [
                'label' => esc_html__('Add Navigation control', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
                'default' => 'yes',
            ]
        );


        $this->add_control(
            'custom_resp',
            [
                'label' => esc_html__('Customize Responsive', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'heading_desktop',
            [
                'label' => esc_html__('Desktop Settings', 'bighearts-core'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => ['custom_resp' => 'yes'],
            ]
        );

        $this->add_control(
            'resp_medium',
            [
                'label' => esc_html__('Desktop Screen Breakpoint', 'bighearts-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['custom_resp' => 'yes'],
                'min' => 500,
                'default' => '1025',
            ]
        );

        $this->add_control(
            'resp_medium_slides',
            [
                'label' => esc_html__('Slides to show', 'bighearts-core'),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'condition' => ['custom_resp' => 'yes'],
            ]
        );

        $this->add_control(
            'heading_tablet',
            [
                'label' => esc_html__('Tablet Settings', 'bighearts-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['custom_resp' => 'yes'],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'resp_tablets',
            [
                'label' => esc_html__('Tablet Screen Breakpoint', 'bighearts-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['custom_resp' => 'yes'],
                'min' => 400,
                'default' => '993',
            ]
        );

        $this->add_control(
            'resp_tablets_slides',
            [
                'label' => esc_html__('Slides to show', 'bighearts-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['custom_resp' => 'yes'],
                'min' => 1,
            ]
        );

        $this->add_control(
            'heading_mobile',
            [
                'label' => esc_html__('Mobile Settings', 'bighearts-core'),
                'type' => Controls_Manager::HEADING,
                'condition' => ['custom_resp' => 'yes'],
                'separator' => 'after',
            ]
        );

        $this->add_control(
            'resp_mobile',
            [
                'label' => esc_html__('Mobile Screen Breakpoint', 'bighearts-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['custom_resp' => 'yes'],
                'min' => 1,
                'default' => '601',
            ]
        );

        $this->add_control(
            'resp_mobile_slides',
            [
                'label' => esc_html__('Slides to show', 'bighearts-core'),
                'type' => Controls_Manager::NUMBER,
                'condition' => ['custom_resp' => 'yes'],
                'min' => 1,
            ]
        );

        $this->end_controls_section();

        /**
         * SETTINGS -> QUERY
         */

        Wgl_Loop_Settings::init(
            $this,
            ['post_type' => 'post']
        );

        /**
         * STYLE -> HEADINGS
         */

        $this->start_controls_section(
            'headings_style_section',
            [
                'label' => esc_html__('Headings', 'bighearts-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'heading_tag',
            [
                'label' => esc_html__('Heading tag', 'bighearts-core'),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'h1' => esc_html__('‹h1›', 'bighearts-core'),
                    'h2' => esc_html__('‹h2›', 'bighearts-core'),
                    'h3' => esc_html__('‹h3›', 'bighearts-core'),
                    'h4' => esc_html__('‹h4›', 'bighearts-core'),
                    'h5' => esc_html__('‹h5›', 'bighearts-core'),
                    'h6' => esc_html__('‹h6›', 'bighearts-core'),
                    'div' => esc_html__('‹div›', 'bighearts-core'),
                    'span' => esc_html__('‹span›', 'bighearts-core'),
                ],
                'default' => 'h4',
            ]
        );

        $this->add_responsive_control(
            'heading_margin',
            [
                'label' => esc_html__('Heading margin', 'bighearts-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 6,
                    'left' => 0,
                    'right' => 0,
                    'bottom' => -4,
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('headings_color');

        $this->start_controls_tab(
            'custom_headings_color_idle',
            ['label' => esc_html__('Idle', 'bighearts-core') ]
        );

        $this->add_control(
            'custom_headings_color',
            [
                'label' => esc_html__('Text Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .blog-post_title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_headings_color_hover',
            ['label' => esc_html__('Hover', 'bighearts-core') ]
        );

        $this->add_control(
            'custom_hover_headings_color',
            [
                'label' => esc_html__('Text Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .blog-post_title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_blog_headings',
                'selector' => '{{WRAPPER}} .blog-post_title, {{WRAPPER}} .blog-post_title > a',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'content_style_section',
            [
                'label' => esc_html__('Content', 'bighearts-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'content_margin',
            [
                'label' => esc_html__('Margin', 'bighearts-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 16,
                    'left' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .blog-post_text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'custom_content_color',
            [
                'label' => esc_html__('Text Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .blog-post_text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'custom_fonts_blog_content',
                'selector' => '{{WRAPPER}} .blog-post_text',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'meta_info_style_section',
            [
                'label' => esc_html__('Meta Info', 'bighearts-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'meta_info_margin',
            [
                'label' => esc_html__('Margin', 'bighearts-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default' => [
                    'top' => 0,
                    'left' => 0,
                    'right' => 0,
                    'bottom' => 0,
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .blog-post .blog-post-hero_content > .meta-data' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->start_controls_tabs('tabs_meta_info');

        $this->start_controls_tab(
            'tab_meta_info_idle',
            ['label' => esc_html__('Idle', 'bighearts-core') ]
        );

        $this->add_control(
            'meta_color_idle',
            [
                'label' => esc_html__('Text Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => '#adadad',
                'selectors' => [
                    '{{WRAPPER}} .blog-post-hero_content > .meta-data,
                     {{WRAPPER}} .blog-post-hero_content > .meta-data a,
                     {{WRAPPER}} .wgl-likes .sl-count,
                     {{WRAPPER}} .share_post-container > a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_meta_hover',
            ['label' => esc_html__('Hover', 'bighearts-core') ]
        );

        $this->add_control(
            'meta_color_hover',
            [
                'label' => esc_html__('Text Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .meta-data a:hover,
                     {{WRAPPER}} .wgl-likes:hover .sl-count' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'media_style_section',
            [
                'label' => esc_html__('Media', 'bighearts-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'custom_blog_mask',
            [
                'label' => esc_html__('Custom Image Idle Overlay', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'custom_image_mask_color',
                'label' => esc_html__('Background', 'bighearts-core'),
                'types' => ['classic', 'gradient', 'video'],
                'condition' => ['custom_blog_mask' => 'yes'],
                'default' => 'rgba( '.\BigHearts_Theme_Helper::hexToRGB(BigHearts_Globals::get_h_font_color()).',0.1)',
                'selector' => '{{WRAPPER}} .blog-post_bg_media:before',
            ]
        );

        $this->add_control(
            'custom_blog_hover_mask',
            [
                'label' => esc_html__('Custom Image Hover Overlay', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );


        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'custom_image_hover_mask_color',
                'label' => esc_html__('Background', 'bighearts-core'),
                'types' => ['classic', 'gradient', 'video'],
                'condition' => ['custom_blog_hover_mask' => 'yes'],
                'default' => 'rgba(50,50,50,1)',
                'selector' => '{{WRAPPER}} .blog-post .blog-post_bg_media:after',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'without_media_style_section',
            [
                'label' => esc_html__('Without Media', 'bighearts-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs('headings_standard_color');

        $this->start_controls_tab(
            'custom_standard_headings_color_idle',
            ['label' => esc_html__('Idle', 'bighearts-core') ]
        );

        $this->add_control(
            'custom_standard_headings_color',
            [
                'label' => esc_html__('Title Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .format-standard.format-no_featured .blog-post_title a,
                     {{WRAPPER}} .format-link.format-no_featured .blog-post_title a,
                     {{WRAPPER}} .format-video.format-no_featured .blog-post_title a,
                     {{WRAPPER}} .format-gallery.format-no_featured .blog-post_title a,
                     {{WRAPPER}} .format-quote.format-no_featured .blog-post_title a,
                     {{WRAPPER}} .format-audio.format-no_featured .blog-post_title a' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'custom_standard_headings_color_hover',
            ['label' => esc_html__('Hover', 'bighearts-core') ]
        );

        $this->add_control(
            'custom_standard_hover_headings_color',
            [
                'label' => esc_html__('Title Hover Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .format-standard.format-no_featured .blog-post_title a:hover,
                     {{WRAPPER}} .format-link.format-no_featured .blog-post_title a:hover,
                     {{WRAPPER}} .format-video.format-no_featured .blog-post_title a:hover,
                     {{WRAPPER}} .format-gallery.format-no_featured .blog-post_title a:hover,
                     {{WRAPPER}} .format-quote.format-no_featured .blog-post_title a:hover,
                     {{WRAPPER}} .format-audio.format-no_featured .blog-post_title a:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'hr_meta_color',
            ['type' => Controls_Manager::DIVIDER ]
        );

        $this->start_controls_tabs('tabs_meta_standard_info');

        $this->start_controls_tab(
            'tab_meta_standard_info_idle',
            ['label' => esc_html__('Idle', 'bighearts-core') ]
        );

        $this->add_control(
            'custom_meta_standard_color',
            [
                'label' => esc_html__('Meta Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .format-no_featured .blog-post-hero_content > .meta-data,
                     {{WRAPPER}} .format-no_featured .blog-post-hero_content > .meta-data a,
                     {{WRAPPER}} .format-no_featured .wgl-likes .sl-count,
                     {{WRAPPER}} .format-no_featured .share_post-container > a,
                     {{WRAPPER}} .format-no_featured .post_categories a' => 'color: {{VALUE}};',
                ],
            ]
        );


        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_meta_standard_hover',
            ['label' => esc_html__('Hover', 'bighearts-core') ]
        );

        $this->add_control(
            'custom_meta_standard_color_hover',
            [
                'label' => esc_html__('Meta Hover Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_secondary_color(),
                'selectors' => [
                    '{{WRAPPER}} .format-no_featured .meta-data a:hover,
                     {{WRAPPER}} .format-no_featured .post_categories a:hover,
                     {{WRAPPER}} .format-no_featured .post_categories span:hover,
                     {{WRAPPER}} .format-no_featured .wgl-likes:hover .sl-count' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
            'hr_content_color',
            ['type' => Controls_Manager::DIVIDER ]
        );

        $this->add_control(
            'custom_standard_content_color',
            [
                'label' => esc_html__('Content Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .format-link.format-no_featured .blog-post_text,
                     {{WRAPPER}} .format-video.format-no_featured .blog-post_text,
                     {{WRAPPER}} .format-quote.format-no_featured .blog-post_text,
                     {{WRAPPER}} .format-audio.format-no_featured .blog-post_text,
                     {{WRAPPER}} .format-gallery.format-no_featured .blog-post_text,
                     {{WRAPPER}} .format-standard.format-no_featured .blog-post_text' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hr_bg_color',
            ['type' => Controls_Manager::DIVIDER ]
        );

        $this->add_control(
            'custom_blog_bg_item',
            [
                'label' => esc_html__('Custom Items Background', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'custom_bg_color',
                'label' => esc_html__('Background', 'bighearts-core'),
                'types' => ['classic', 'gradient', 'video'],
                'condition' => ['custom_blog_bg_item' => 'yes'],
                'default' => 'rgba(247,247,247,1)',
                'selector' => '{{WRAPPER}} .blog-style-hero .blog-post-hero_wrapper',
            ]
        );

        $this->add_control(
            'custom_blog_bg_item_hover',
            [
                'label' => esc_html__('Custom Items Hover Background', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('On', 'bighearts-core'),
                'label_off' => esc_html__('Off', 'bighearts-core'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'custom_bg_color_hover',
                'label' => esc_html__('Hover Background', 'bighearts-core'),
                'types' => ['classic', 'gradient', 'video'],
                'condition' => ['custom_blog_bg_item_hover' => 'yes'],
                'default' => 'rgba(247,247,247,1)',
                'selector' => '{{WRAPPER}} .blog-style-hero .blog-post-hero_wrapper:hover',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $atts = $this->get_settings_for_display();

        $blog = new WglBlogHero();
        echo $blog->render($atts);
    }
}
