<?php

namespace EmTmpl\Inc;

defined( 'ABSPATH' ) || exit;

class Enqueue {
	protected static $instance = null;
	protected $slug;
	protected $cache_posts = [];

	public function __construct() {
		$this->slug = EMTMPL_PR_CONST['assets_slug'];
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ], 20 );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_run_script' ], PHP_INT_MAX );
	}

	public static function instance() {
		return self::$instance == null ? self::$instance = new self() : self::$instance;
	}

	public function register_scripts() {
		$suffix = WP_DEBUG ? '' : '.min';

		$lib_styles = [
			'button',
			'tab',
			'input',
			'icon',
			'segment',
			'image',
			'modal',
			'dimmer',
			'transition',
			'menu',
			'grid',
			'search',
			'message',
			'loader',
			'label',
			'select2',
			'header',
			'accordion',
			'dropdown',
			'checkbox',
			'form',
			'table',
		];

		foreach ( $lib_styles as $style ) {
			wp_register_style( $this->slug . $style, EMTMPL_PR_CONST['libs_url'] . $style . '.min.css', '', EMTMPL_PR_CONST['version'] );
		}

		//*************************************//
		$styles = [ 'admin', 'email-builder' ];

		foreach ( $styles as $style ) {
			wp_register_style( $this->slug . $style, EMTMPL_PR_CONST['css_url'] . $style . $suffix . '.css', '', EMTMPL_PR_CONST['version'] );
		}

		//*************************************//

		$lib_scripts = [ 'select2', 'transition', 'dimmer', 'accordion', 'tab', 'modal' ];

		foreach ( $lib_scripts as $script ) {
			wp_register_script( $this->slug . $script, EMTMPL_PR_CONST['libs_url'] . $script . '.min.js', [ 'jquery' ], EMTMPL_PR_CONST['version'] );
		}

		//*************************************//

		$scripts = [
			'email-builder'  => [ 'jquery', 'jquery-ui-sortable', 'jquery-ui-draggable' ],
			'components'     => [],
			'properties'     => [],
			'inputs'         => [],
			'run'            => [],
			'get-update-key' => [ 'jquery' ]
		];
		foreach ( $scripts as $script => $depend ) {
			wp_register_script( $this->slug . $script, EMTMPL_PR_CONST['js_url'] . $script . $suffix . '.js', $depend, EMTMPL_PR_CONST['version'] );
		}
	}

	public function enqueue_scripts() {
		$screen_id = get_current_screen()->id;

		if ( ! in_array( $screen_id, [ 'emtmpl', 'edit-emtmpl', 'emtmpl_block', 'emtmpl_page_emtmpl_settings', 'viwec_template_page_emtmpl_settings' ] ) ) {
			return;
		}

		global $post;

		$this->register_scripts();

		$enqueue_scripts = $enqueue_styles = [];
		$localize_script = $inline_handle = $css = '';
		$params          = [ 'ajaxUrl' => admin_url( 'admin-ajax.php' ), 'nonce' => wp_create_nonce( 'emtmpl_nonce' ) ];

		switch ( $screen_id ) {
			case 'emtmpl':
			case 'emtmpl_block':
				wp_enqueue_editor();
				wp_enqueue_media();
				wp_enqueue_script( 'iris' );

				$enqueue_styles  = [ 'tab', 'menu', 'accordion', 'select2', 'dimmer', 'transition', 'modal', 'button', 'email-builder' ];
				$enqueue_scripts = [ 'select2', 'transition', 'dimmer', 'accordion', 'tab', 'modal', 'jqColorPicker', 'inputs', 'email-builder', 'properties', 'components' ];
				$localize_script = $inline_handle = 'email-builder';

				$header = Utils::parse_block( Email_Samples::sample_header() );
				$footer = Utils::parse_block( Email_Samples::sample_footer() );

				$samples = Email_Samples::sample_templates( $header, $footer );

				$params = array_merge( $params, [
					'post'                         => EMTMPL_PR_CONST['img_url'] . 'post.png',
					'placeholder'                  => EMTMPL_PR_CONST['img_url'] . 'placeholder.jpg',
					'adminBarStt'                  => Utils::get_admin_bar_stt(),
					'homeUrl'                      => home_url(),
					'siteUrl'                      => site_url(),
					'adminEmail'                   => get_bloginfo( 'admin_email' ),
					'commonShortcode'              => apply_filters( 'emtmpl_shortcode_for_editor', Utils::common_shortcodes() ),
					'shortcode'                    => Utils::email_type_shortcodes(),
					'shortcode_for_replace'        => Utils::shortcode_for_editor_replace(),
					'emailTypes'                   => [ 'admin' => Utils::get_admin_emails(), 'user' => Utils::get_user_emails(), ],
					'sc_3rd_party'                 => Utils::get_register_shortcode_for_builder(),
					'sc_3rd_party_for_text_editor' => Utils::get_register_shortcode_for_text_editor(),
					'samples'                      => $samples,
					'subjects'                     => Email_Samples::default_subject(),
					'i18n'                         => I18n::init(),
					'post_categories'              => $this->get_categories( 'category' )
				] );

				$images_map = include_once plugin_dir_path( __FILE__ ) . 'images.php';

				foreach ( $images_map['social_icons'] as $type => $data ) {
					foreach ( $data as $key => $text ) {
						$url = $key ? EMTMPL_PR_CONST['img_url'] . $key . '.png' : '';

						$params['social_icons'][ $type ][] = [ 'id' => $url, 'text' => $text, 'slug' => $key ];
						$css                               .= ".mce-i-{$key}{background: url('{$url}') !important; background-size: cover !important;}";

					}
				}

				foreach ( $images_map['infor_icons'] as $type => $data ) {
					foreach ( $data as $key => $text ) {
						$url                              = $key ? EMTMPL_PR_CONST['img_url'] . $key . '.png' : '';
						$params['infor_icons'][ $type ][] = [ 'id' => $url, 'text' => $text, 'slug' => $key ];
						$css                              .= ".mce-i-{$key}{background: url('{$url}') !important; background-size: cover !important;}";
					}
				}

				$email_structure = get_post_meta( $post->ID, 'emtmpl_email_structure', true );

				if ( ! empty( $email_structure ) ) {
					$json_decode_email_structure = json_decode( html_entity_decode( $email_structure ), true );

					if ( is_array( $json_decode_email_structure ) ) {
						array_walk_recursive( $json_decode_email_structure, function ( $value, $key ) {

							if ( in_array( $key, [ 'data-include-post-id', 'data-exclude-post-id' ], true ) ) {

								if ( $value ) {
									$value             = explode( ',', $value );
									$this->cache_posts = array_merge( $this->cache_posts, $value );
								}
							}
						} );
					}

					$posts_temp = [];

					if ( ! empty( $this->cache_posts ) ) {
						$this->cache_posts = array_values( array_unique( $this->cache_posts ) );

						$posts = get_posts( [ 'numberposts' => 5, 'include' => $this->cache_posts ] );
						if ( ! empty( $posts ) ) {
							foreach ( $posts as $p ) {
								$posts_temp[] = [ 'id' => $p->ID, 'text' => $p->post_title, 'content' => do_shortcode( $p->post_content ) ];
							}
						}
					}

					wp_localize_script( $this->slug . $localize_script, 'viWecCachePosts', $posts_temp );
					wp_localize_script( $this->slug . $localize_script, 'viWecLoadTemplate', [ $email_structure ] );
				}

				if ( $screen_id === 'emtmpl' ) {
					$block_data = [];
					$blocks     = get_posts( [ 'post_type' => 'emtmpl_block', 'post_status' => 'publish', 'numberposts' => - 1 ] );
					if ( ! empty( $blocks ) ) {
						foreach ( $blocks as $block ) {
							$id              = $block->ID;
							$email_structure = get_post_meta( $id, 'emtmpl_email_structure', true );
							$email_structure = html_entity_decode( $email_structure );
							$email_structure = json_decode( $email_structure, true );

							$block_data[] = [ 'id' => $id, 'text' => $block->post_title, 'data' => $email_structure ];
						}
					}
					$params['templateBlocks'] = $block_data;
				}
				$params['postType'] = $screen_id;

				break;

			case 'edit-emtmpl':
				$enqueue_styles = [ 'form', 'segment', 'button', 'icon' ];
				wp_add_inline_style( 'villatheme-support', '#emtmpl-in-all-email-page{margin-left:160px;padding:0 20px 40px}.folded #emtmpl-in-all-email-page{margin-left:35px}' );
				break;

			case 'emtmpl_page_emtmpl_settings':
			case 'viwec_template_page_emtmpl_settings':
				$enqueue_styles  = [ 'form', 'input', 'icon', 'segment', 'button' ];
				$enqueue_scripts = [ 'get-update-key' ];

				break;
		}

		foreach ( $enqueue_scripts as $script ) {
			wp_enqueue_script( $this->slug . $script );
		}

		foreach ( $enqueue_styles as $style ) {
			wp_enqueue_style( $this->slug . $style );
		}

		if ( $localize_script ) {
			wp_localize_script( $this->slug . $localize_script, 'viWecParams', $params );
		}
		if ( $inline_handle ) {
			wp_add_inline_style( $this->slug . $inline_handle, $css );
		}
	}

	public function enqueue_run_script() {
		$screen_id = get_current_screen()->id;
		if ( $screen_id === 'emtmpl' || $screen_id === 'emtmpl_block' ) {
			wp_enqueue_script( $this->slug . 'run' );
		}
	}

	public function get_categories( $type ) {
		$cats       = [];
		$categories = get_terms( $type, 'orderby=name&hide_empty=0' );
		if ( ! empty( $categories ) ) {
			foreach ( $categories as $cat ) {
				$cats[] = [ 'id' => $cat->term_id, 'text' => $cat->name ];
			}
		}

		return $cats;
	}
}
