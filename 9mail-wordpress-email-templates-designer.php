<?php
/**
 * Plugin Name: 9MAIL - WordPress Email Templates Designer Premium
 * Plugin URI: https://villatheme.com/extensions/9mail-wordpress-email-templates-designer/
 * Description: Make your WordPress emails become professional.
 * Version: 1.0.4
 * Author: VillaTheme
 * Author URI: https://villatheme.com
 * Text Domain: 9mail-wordpress-email-templates-designer
 * Domain Path: /languages
 * Copyright 2022 - 2024 VillaTheme.com. All rights reserved.
 * Requires at least: 5.0
 * Tested up to: 6.5
 * Requires PHP: 7.0
 **/

namespace EmTmpl;

use EmTmpl\Inc\Admin;
use EmTmpl\Inc\Email_Builder;
use EmTmpl\Inc\Email_Samples;
use EmTmpl\Inc\Email_Trigger;
use EmTmpl\Inc\Enqueue;
use EmTmpl\Inc\Utils;

defined( 'ABSPATH' ) || exit;

define( 'EMTMPL_PR_CONST', [
	'version'     => '1.0.4',
	'plugin_name' => '9MAIL - WordPress Email Templates Designer',
	'slug'        => '9mail-wordpress-email-templates-designer',
	'assets_slug' => 'emtmpl-',
	'file'        => __FILE__,
	'basename'    => plugin_basename( __FILE__ ),
	'plugin_dir'  => plugin_dir_path( __FILE__ ),
	'css_url'     => plugins_url( 'assets/css/', __FILE__ ),
	'js_url'      => plugins_url( 'assets/js/', __FILE__ ),
	'libs_url'    => plugins_url( 'assets/libs/', __FILE__ ),
	'img_url'     => plugins_url( 'assets/img/', __FILE__ ),
] );

require_once EMTMPL_PR_CONST['plugin_dir'] . 'autoload.php';

if ( ! class_exists( 'EMTMPL_Email_Templates_Designer' ) ) {
	class EMTMPL_Email_Templates_Designer {

		public function __construct() {
			add_action( 'plugins_loaded', [ $this, 'init' ], 9 );
			register_activation_hook( __FILE__, array( $this, 'activate' ) );
		}

		public function init() {
			if ( ! class_exists( 'VillaTheme_Require_Environment' ) ) {
				include_once EMTMPL_PR_CONST['plugin_dir'] . 'support/support.php';
			}

			$environment = new \VillaTheme_Require_Environment( [
					'plugin_name' => EMTMPL_PR_CONST['plugin_name'],
					'php_version' => '7.0',
					'wp_version'  => '5.0',
				]
			);

			if ( $environment->has_error() ) {
				return;
			}

			add_filter( 'plugin_action_links_' . EMTMPL_PR_CONST['basename'], [ $this, 'setting_link' ] );
			$this->load_text_domain();
			$this->load_classes();
		}

		public function load_text_domain() {
			$locale   = determine_locale();
			$locale   = apply_filters( 'plugin_locale', $locale, '9mail-wordpress-email-templates-designer' );
			$basename = plugin_basename( dirname( __FILE__ ) );

			unload_textdomain( '9mail-wordpress-email-templates-designer' );

			load_textdomain( '9mail-wordpress-email-templates-designer', WP_LANG_DIR . "/{$basename}/{$basename}-{$locale}.mo" );
			load_plugin_textdomain( '9mail-wordpress-email-templates-designer', false, $basename . '/languages' );
		}

		public function load_classes() {
			require_once EMTMPL_PR_CONST['plugin_dir'] . 'inc/functions.php';

			Enqueue::instance();
			Email_Builder::instance();
			Email_Trigger::instance();
			Admin::instance();

			if ( is_admin() && ! wp_doing_ajax() ) {
				$this->support();
			}
		}

		public function support() {
			if ( ! class_exists( 'VillaTheme_Plugin_Check_Update' ) ) {
				include_once EMTMPL_PR_CONST['plugin_dir'] . 'support/check_update.php';
			}

			if ( ! class_exists( 'VillaTheme_Plugin_Updater' ) ) {
				include_once EMTMPL_PR_CONST['plugin_dir'] . 'support/update.php';
			}

			if ( ! class_exists( 'VillaTheme_Support_Pro' ) ) {
				include_once EMTMPL_PR_CONST['plugin_dir'] . 'support/support.php';
			}

			new \VillaTheme_Support_Pro(
				array(
					'support'   => 'https://villatheme.com/supports/forum/plugins/9mail-wordpress-email-template-designer',
					'docs'      => 'https://docs.villatheme.com/?item=9mail-wordpress-email-template-designer',
					'review'    => 'https://codecanyon.net/downloads',
					'css'       => EMTMPL_PR_CONST['css_url'],
					'image'     => EMTMPL_PR_CONST['img_url'],
					'slug'      => EMTMPL_PR_CONST['slug'],
					'menu_slug' => 'edit.php?post_type=emtmpl',
					'version'   => EMTMPL_PR_CONST['version']
				)
			);

			$key         = get_option( 'emtmpl_auto_update_key' );
			$setting_url = admin_url( 'admin.php?page=wvr-settings' );

			new \VillaTheme_Plugin_Check_Update (
				EMTMPL_PR_CONST['version'],                    // current version
				'https://villatheme.com/wp-json/downloads/v3',  // update path
				EMTMPL_PR_CONST['basename'],                  // plugin file slug
				EMTMPL_PR_CONST['slug'],
				120635, //Pro id on VillaTheme
				$key,
				$setting_url
			);

			new \VillaTheme_Plugin_Updater( EMTMPL_PR_CONST['basename'], EMTMPL_PR_CONST['slug'], $setting_url );
		}

		public function activate() {
			$check_exist = get_posts( [ 'post_type' => 'emtmpl', 'numberposts' => 1 ] );

			if ( empty( $check_exist ) ) {
				$default_subject = Email_Samples::default_subject();

				$header = Email_Samples::sample_header();
				$footer = Email_Samples::sample_footer();

				$header_id = Utils::insert_block( $header, 'Header' );
				$footer_id = Utils::insert_block( $footer, 'Footer' );

				$templates = Email_Samples::sample_templates( $header_id, $footer_id );

				foreach ( $templates as $key => $template ) {
					if ( $key = 'wpforms_email_message' ) {
						continue;
					}
					$args = [
						'post_title'  => $default_subject[ $key ] ?? '',
						'post_status' => 'publish',
						'post_type'   => 'emtmpl',
					];

					$post_id  = wp_insert_post( $args );
					$template = $template['basic']['data'];
					$template = str_replace( '\\', '\\\\', $template );

					update_post_meta( $post_id, 'emtmpl_settings_type', $key );
					update_post_meta( $post_id, 'emtmpl_email_structure', $template );
				}
			}
		}

		public function setting_link( $links ) {
			return array_merge(
				[
					sprintf( "<a href='%1s' >%2s</a>", esc_url( admin_url( 'edit.php?post_type=emtmpl' ) ),
						esc_html__( 'Settings', '9mail-wordpress-email-templates-designer' ) )
				],
				$links );
		}

	}

	new EMTMPL_Email_Templates_Designer();
}