<?php
/*
Plugin Name: Products grid view - Noorani
Description: An elementor widget for show products in grid view 
Version: 1.0.0.0
Author: M.Amin Noorani
Author URI: https://amin-noorani.ir/cv
Text Domain: mn_rtl
Domain Path: /languages
Requires Plugins: woocommerce
*/
namespace MN\RTL;

if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( "\MN\RTL\Init" ) ) {
	class Init {
		public static function init() {
			self::constants();
			self::i18n();

			include( MN_RTL_DIR . 'Includes.php' );
		}

		private static function constants() {
			if( ! defined( 'MN_RTL_DIR' ) ) {
				define( 'MN_RTL_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
			}

			if( ! defined( 'MN_RTL_URI' ) ) {
				define( 'MN_RTL_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
			}

			if( ! defined( 'MN_RTL_VERSION' ) ) {
				define( 'MN_RTL_VERSION', '1.0.0.0' );
			}

			if( ! defined( 'MN_RTL_DEV' ) ) {
				define( 'MN_RTL_DEV', true );
			}
		}

		private static function i18n() {
			// Load languages
			load_plugin_textdomain( 'mn_rtl', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
		}

		public static function elementor_widget_categories( $elements_manager ) {
			$elements_manager->add_category(
				'mn-rtl',
				[
					'title' => esc_html__( 'MN Widgets', 'mn_rtl' ),
					'icon' => 'fa fa-plug',
				]
			);
		}

		public static function register_elementor_widgets( $widgets_manager ) {
			include( MN_RTL_DIR . "Elementor-addon/GridProducts.php" );
			$widgets_manager->register( new \MN\RTL\Elementor\GridProducts() );
		}

		public static function frontend_styles() {
			if( !wp_style_is( 'mn_bootstrap', 'enqueued' ) ) {
				wp_enqueue_style( 'mn_bootstrap', MN_RTL_URI . "assets/css/bootstrap.min.css", [], MN_RTL_VERSION );
			}
			if( !wp_style_is( 'mn_elementor_widgets', 'enqueued' ) ) {
				wp_enqueue_style( 'mn_elementor_widgets', MN_RTL_URI . "assets/css/main.css", [], MN_RTL_VERSION );
			}
		}
	}
	add_action( 'init', [Init::class, 'init'], 1 );
	add_action( 'elementor/elements/categories_registered', [Init::class, 'elementor_widget_categories'] );
	add_action( 'elementor/widgets/register', [Init::class, 'register_elementor_widgets'] );
	add_action( 'elementor/frontend/after_enqueue_styles', [Init::class, 'frontend_styles'] );
}