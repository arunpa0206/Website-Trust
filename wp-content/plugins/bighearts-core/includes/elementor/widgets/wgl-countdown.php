<?php
/**
 * This template can be overridden by copying it to `bighearts[-child]/bighearts-core/elementor/widgets/wgl-countdown.php`.
 */
namespace WglAddons\Widgets;

defined('ABSPATH') || exit; // Abort, if called directly.

use Elementor\{
    Widget_Base,
    Controls_Manager,
    Group_Control_Typography
};
use WglAddons\{
    BigHearts_Global_Variables as BigHearts_Globals,
    Templates\WGL_CountDown as Countdown_Template
};

class Wgl_CountDown extends Widget_Base
{
    public function get_name()
    {
        return 'wgl-countdown';
    }

    public function get_title()
    {
        return esc_html__('WGL Countdown Timer', 'bighearts-core');
    }

    public function get_icon()
    {
        return 'wgl-countdown';
    }

    public function get_categories()
    {
        return ['wgl-extensions'];
    }

    public function get_script_depends()
    {
        return [
            'jquery-countdown',
            'wgl-elementor-extensions-widgets',
        ];
    }

    protected function register_controls()
    {
        /**
         * CONTENT -> GENERAL
         */

        $this->start_controls_section(
            'section_content_general',
            ['label' => esc_html__('General', 'bighearts-core')]
        );

        $this->add_control(
            'h_tip',
            [
                'label' => esc_html__('Choose the specific date:', 'littledino-core'),
                'type' => Controls_Manager::HEADING,
            ]
        );

        $this->add_control(
            'countdown_year',
            [
                'label' => esc_html__('Year', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Example: 2020', 'bighearts-core'),
                'default' => esc_html__('2020', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'countdown_month',
            [
                'label' => esc_html__('Month', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Example: 12', 'bighearts-core'),
                'default' => esc_html__('12', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'countdown_day',
            [
                'label' => esc_html__('Day', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Example: 31', 'bighearts-core'),
                'default' => esc_html__('31', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'countdown_hours',
            [
                'label' => esc_html__('Hours', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Example: 24', 'bighearts-core'),
                'default' => esc_html__('24', 'bighearts-core'),
            ]
        );

        $this->add_control(
            'countdown_min',
            [
                'label' => esc_html__('Minutes', 'bighearts-core'),
                'type' => Controls_Manager::TEXT,
                'dynamic' => ['active' => true],
                'placeholder' => esc_attr__('Example: 59', 'bighearts-core'),
                'default' => esc_html__('59', 'bighearts-core'),
            ]
        );

        $this->add_responsive_control(
            'alignment',
            [
                'label' => esc_html__( 'Alignment', 'bighearts-core' ),
                'type' => Controls_Manager::CHOOSE,
                'separator' => 'before',
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'bighearts-core' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'bighearts-core' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'bighearts-core' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Full Width', 'bighearts-core'),
                        'icon' => 'eicon-text-align-justify',
                    ],
                ],
                'default' => 'center',
                'prefix_class' => 'a%s',
            ]
        );

        $this->end_controls_section();

        /**
         * CONTENT -> CONTENT
         */

        $this->start_controls_section(
            'section_content_content',
            ['label' => esc_html__('Content', 'bighearts-core')]
        );

        $this->add_control(
            'show_value_names',
            [
                'label' => esc_html__('Show Title?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'show_title_',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_separating',
            [
                'label' => esc_html__('Show Separating Dots?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:before,
                    {{WRAPPER}} .countdown-section:not(:last-child) .countdown-amount:after' => 'visibility: visible;'
                ]
            ]
        );

        $this->add_control(
            'hide_day',
            [
                'label' => esc_html__('Hide Days?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_hours',
            [
                'label' => esc_html__('Hide Hours?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_minutes',
            [
                'label' => esc_html__('Hide Minutes?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->add_control(
            'hide_seconds',
            [
                'label' => esc_html__('Hide Seconds?', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> NUMBERS
         */

        $this->start_controls_section(
            'countdown_style_numbers',
            [
                'label' => esc_html__('Numbers', 'bighearts-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'bighearts-core'),
                'name' => 'custom_fonts_number',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                    'font_weight' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_weight']],
                ],
                'selector' => '{{WRAPPER}} .countdown-amount',
            ]
        );

        $this->add_control(
            'number_color_idle',
            [
                'label' => esc_html__('Text Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_h_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .countdown-amount' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'number_bg_idle',
            [
                'label' => esc_html__('Background Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'selectors' => [
                    '{{WRAPPER}} .countdown-amount span' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_padding',
            [
                'label' => esc_html__('Padding', 'bighearts-core'),
                'type' => Controls_Manager::DIMENSIONS,
                'separator' => 'before',
                'size_units' => 'px',
                'default' => [
                    'top' => '2',
                    'right' => '15',
                    'bottom' => '2',
                    'left' => '15',
                    'unit' => 'px',
                    'isLinked' => false
                ],
                'selectors' => [
                    '{{WRAPPER}} .countdown-amount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'number_width',
            [
                'label' => esc_html__('Min Width', 'bighearts-core'),
                'type' => Controls_Manager::SLIDER,
                'condition' => ['alignment!' => 'justify'],
                'range' => [
                    'px' => ['min' => 20, 'max' => 400],
                ],
                'desktop_default' => ['size' => 172],
                'tablet_default' => ['size' => 150],
                'mobile_default' => ['size' => 50],
                'selectors' => [
                    '{{WRAPPER}} .countdown-amount' => 'min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        /**
         * STYLE -> TITLES
         */

        $this->start_controls_section(
            'section_style_titles',
            [
                'label' => esc_html__('Titles', 'bighearts-core'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'label' => esc_html__('Typography', 'bighearts-core'),
                'name' => 'custom_fonts_text',
                'fields_options' => [
                    'typography' => ['default' => 'yes'],
                    'font_family' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_family']],
                    'font_weight' => ['default' => \Wgl_Addons_Elementor::$typography_1['font_weight']],
                ],
                'selector' => '{{WRAPPER}} .countdown-period',
            ]
        );

        $this->add_control(
            'titles_color_idle',
            [
                'label' => esc_html__('Text Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_main_font_color(),
                'selectors' => [
                    '{{WRAPPER}} .countdown-period' => 'color: {{VALUE}};',
                ],
            ]
        );

	    $this->add_responsive_control(
		    'titles_padding',
		    [
			    'label' => esc_html__('Padding', 'bighearts-core'),
			    'type' => Controls_Manager::DIMENSIONS,
			    'separator' => 'before',
			    'size_units' => 'px',
			    'default' => [
				    'top' => '2',
				    'right' => '5',
				    'bottom' => '2',
				    'left' => '5',
				    'unit' => 'px',
				    'isLinked' => false
			    ],
			    'selectors' => [
				    '{{WRAPPER}} .countdown-period' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			    ],
		    ]
	    );

        $this->end_controls_section();

        /**
         * STYLE -> DOTS
         */

        $this->start_controls_section(
            'section_style_dots',
            [
                'label' => esc_html__('Dots', 'bighearts-core'),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => ['show_separating!' => ''],
            ]
        );

        $this->add_control(
            'dots_color_idle',
            [
                'label' => esc_html__('Dots Color', 'bighearts-core'),
                'type' => Controls_Manager::COLOR,
                'dynamic' => ['active' => true],
                'default' => BigHearts_Globals::get_primary_color(),
                'selectors' => [
                    '{{WRAPPER}} .countdown-section .countdown-amount:before,
                     {{WRAPPER}} .countdown-section .countdown-amount:after' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'dots_shape',
            [
                'label' => esc_html__('Shape (circle or square)', 'bighearts-core'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__('Circle', 'bighearts-core'),
                'label_off' => esc_html__('Square', 'bighearts-core'),
                'default' => 'yes',
                'selectors' => [
                    '{{WRAPPER}} .countdown-section .countdown-amount:before,
                     {{WRAPPER}} .countdown-section .countdown-amount:after' => 'border-radius: 50%;',
                ],
            ]
        );

        $this->add_responsive_control(
            'dots_size',
            [
                'label' => esc_html__('Dots Size', 'bighearts-core'),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['min' => 1, 'max' => 30],
                ],
                'desktop_default' => ['size' => 10],
                'tablet_default' => ['size' => 8],
                'mobile_default' => ['size' => 5],
                'selectors' => [
                    '{{WRAPPER}} .countdown-section .countdown-amount:before,
                     {{WRAPPER}} .countdown-section .countdown-amount:after' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        $atts = $this->get_settings_for_display();

        (new Countdown_Template())->render($this, $atts);
    }
}
