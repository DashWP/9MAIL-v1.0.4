<?php

namespace EmTmpl\Inc;

defined( 'ABSPATH' ) || exit;

class Email_Render {

	protected static $instance = null;
	public $custom_css;
	public $check_rendered;
	protected $direction;
	protected $template_id;
	protected $props;
	protected $data;
	protected $preview;

	public function __construct( $data = [] ) {
		$data       = wp_parse_args( $data, [ 'template_id' => '' ] );
		$this->data = $data;

		$this->template_id = $data['template_id'];
	}

	public static function instance( $data = [] ) {
		if ( null == self::$instance ) {
			self::$instance = new self( $data );
		}

		return self::$instance;
	}

	public function set_data( $data ) {
		$this->data = $data;
	}

	public function parse_styles( $data ) {
		if ( empty( $data ) || ! is_array( $data ) ) {
			return $data;
		}

		$style      = '';
		$ignore_arr = [
			'border-top-width',
			'border-right-width',
			'border-bottom-width',
			'border-left-width',
			'border-style',
			'border-color',
			'border-top-color',
			'border-width',
		];
		foreach ( $data as $key => $value ) {
			if ( in_array( $key, $ignore_arr ) ) {
				continue;
			}

			$style .= "{$key}:{$value};";
		}

		$border_style = $data['border-style'] ?? 'solid';
		$border_color = $data['border-color'] ?? ( $data['border-top-color'] ?? 'transparent' );

		if ( isset( $data['border-top-width'] ) ) {
			foreach ( [ 'top', 'right', 'bottom', 'left' ] as $pos ) {
				$style .= ! isset( $data["border-{$pos}-width"] ) || $data["border-{$pos}-width"] === '0px'
					? "border-{$pos}:0 hidden;"
					: "border-{$pos}:{$data["border-{$pos}-width"]} {$border_style} {$border_color};";
			}
		} elseif ( isset( $data['border-width'] ) ) {
			$border_width = $data['border-width'];
			if ( $border_width !== '0px' ) {
				$style .= " border-width: {$border_width}; ";
				$style .= " border-style: {$border_style}; ";
				$style .= " border-color: {$border_color}; ";
			} else {
				$style .= " border:0 hidden; ";
			}
		}


		return esc_attr( $style );
	}

	public function render() {
		$template_id = $this->data['template_id'] ?? '';
		if ( ! $template_id ) {
			return;
		}

		$data   = get_post_meta( $template_id, 'emtmpl_email_structure', true );
		$schema = json_decode( html_entity_decode( $data ), true );

		$this->direction = get_post_meta( $template_id, 'emtmpl_settings_direction', true );

		$this->common_render( $schema );
	}

	public function preview_render() {
		if ( empty( $this->data['schema'] ) ) {
			return;
		}
		$this->direction = isset( $_POST['direction'] ) ? sanitize_text_field( wp_unslash( $_POST['direction'] ) ) : 'ltr';
		$schema          = $this->data['schema'];
		$this->preview   = true;
		$this->common_render( $schema );
	}

	public function common_render( $schema ) {
		$this->check_rendered = true;
		$bg_style             = '';
		$width                = 600;
		$responsive           = 380;

		if ( isset( $schema['style_container'] ) ) {
			$width      = ! empty( $schema['style_container']['width'] ) ? $schema['style_container']['width'] : $width;
			$responsive = ! empty( $schema['style_container']['responsive'] ) ? $schema['style_container']['responsive'] : $responsive;
			unset( $schema['style_container']['width'] );
			unset( $schema['style_container']['responsive'] );
			$bg_style = isset( $schema['style_container'] ) ? $this->parse_styles( $schema['style_container'] ) : '';
		}

		$this->email_header( $bg_style, $width, $responsive );
		$this->email_body( $schema, $width );
		$this->email_footer();
	}

	public function email_body( $schema, $width ) {
		$left_align = $this->direction == 'rtl' ? 'right' : 'left';
		?>
		<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' class="emtmpl-responsive">
			<?php
			if ( ! empty( $schema['rows'] ) && is_array( $schema['rows'] ) ) {
				foreach ( $schema['rows'] as $row ) {
					if ( ! empty( $row ) && is_array( $row ) ) {
						$this->render_row( $row );
					} else {
						$block = get_post_meta( $row, 'emtmpl_email_structure', true );
						$block = json_decode( html_entity_decode( $block ), true );
						$rows  = $block['rows'] ?? '';
						if ( ! empty( $rows ) && is_array( $rows ) ) {
							foreach ( $rows as $row ) {
								$this->render_row( $row );
							}
						}
					}
				}
			} ?>
		</table>
		<?php
	}

	public function render_row( $row ) {
		$row_outer_style = ! empty( $row['props']['style_outer'] ) ? $this->parse_styles( $row['props']['style_outer'] ) : '';
		$left_align      = $this->direction == 'rtl' ? 'right' : 'left';

		?>
		<tr>
			<td valign='top' width='100%'
			    style='background-repeat: no-repeat;background-size: cover;background-position: top; <?php echo esc_attr( $row_outer_style ) ?>'>
				<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='border-collapse: collapse;margin: 0; padding:0; max-width: 100%;'>
					<tr>
						<td valign='top' width='100%' class='emtmpl-responsive-padding' border='0' cellpadding='0' cellspacing='0'
						    style='width: 100%; font-size: 0 !important;border-collapse: collapse;margin: 0; padding:0;'>

							<?php
							$end_array = array_keys( $row );
							$end_array = end( $end_array );

							if ( ! empty( $row['cols'] && is_array( $row['cols'] ) ) ) {
								$arr_key    = array_keys( $row['cols'] );
								$start      = current( $arr_key );
								$end        = end( $arr_key );
								$col_number = count( $row['cols'] );

								$width = ( 100 / $col_number ) . '%';

								foreach ( $row['cols'] as $key => $col ) {
									$col_style = ! empty( $col['props']['style'] ) ? $this->parse_styles( $col['props']['style'] ) : '';

									if ( $start == $key ) { ?>
										<!--[if mso | IE]>
										<table width="100%" role="presentation" border="0" cellpadding="0" cellspacing="0">
											<tr>
												<td valign='top' class="" style="vertical-align:top;width:<?php echo esc_attr( $width ) ?>;">
										<![endif]-->
									<?php } ?>

									<table align="<?php echo esc_attr( $left_align ) ?>" width="<?php echo esc_attr( $width ) ?>" border="0" cellpadding="0" cellspacing="0"
									       class="emtmpl-responsive" style='display:inline-block;margin:0; padding:0;border-collapse: collapse;'>
										<tr>
											<td>
												<table width='100%' align='left' border='0' cellpadding='0' cellspacing='0'
												       style='margin:0; padding:0;border-collapse: collapse;width: 100%'>
													<tr>
														<td valign='top' width='100%' style='line-height: 1.5;<?php echo esc_attr( $col_style ) ?>'>
															<?php
															if ( ! empty( $col['elements'] && is_array( $col['elements'] ) ) ) {
																foreach ( $col['elements'] as $el ) {
																	$type = isset( $el['type'] ) ? str_replace( '/', '_', $el['type'] ) : '';

																	$content_style = isset( $el['style'] ) ? $this->parse_styles( str_replace( "'", '', $el['style'] ) ) : '';
																	$el_style      = ! empty( $el['props']['style'] ) ? $this->parse_styles( str_replace( "'", '', $el['props']['style'] ) ) : '';

																	?>
																	<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'
																	       style='border-collapse: separate;'>
																		<tr>
																			<td valign='top' style='<?php echo esc_attr( $el_style ); ?>'>
																				<table check='' align='center' width='100%' border='0' cellpadding='0'
																				       cellspacing='0' style='border-collapse: separate;' class="emtmpl-<?php echo esc_attr( $type ) ?>">
																					<tr>
																						<td valign='top' dir="<?php echo esc_attr( $this->direction ) ?>"
																						    style='font-size: 15px;<?php echo esc_attr( $content_style ) ?>'>
																							<?php
																							$this->props = $el;

																							$func = 'render_' . $type;
																							if ( method_exists( $this, $func ) ) {
																								$this->$func( $el );
																							}
																							?>
																						</td>
																					</tr>
																				</table>
																			</td>
																		</tr>
																	</table>
																	<?php
																}
															}
															?>
														</td>
													</tr>
												</table>
											</td>
										</tr>
									</table>
									<?php
									if ( $end == $key ) {
										?>
										<!--[if mso | IE]></td></tr></table><![endif]-->
										<?php
									} else {
										?>
										<!--[if mso | IE]></td>
										<td valign='top' style="vertical-align:top;width:<?php echo esc_attr( $width ) ?>;">
										<![endif]-->
										<?php
									}
								}
							} ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>

		<?php
	}

	public function get_template( $template_name, $args = [], $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = '9mail-wordpress-email-templates-designer';
		}

		if ( ! $default_path ) {
			$default_path = EMTMPL_PR_CONST['plugin_dir'] . 'templates/';
		}

		$template = locate_template( array( trailingslashit( $template_path ) . $template_name ) );

		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		if ( ! empty( $args ) && is_array( $args ) ) {
			extract( $args ); // @codingStandardsIgnoreLine
		}

		include $template;
	}

	public function email_header( $bg_style, $width, $responsive ) {
		$this->get_template( 'email-header.php', [ 'bg_style' => $bg_style, 'width' => $width, 'responsive' => $responsive, 'direction' => $this->direction ] );
	}

	public function email_footer() {
		$this->get_template( 'email-footer.php' );
	}

	public function custom_style() {
		return $this->custom_css ? $this->custom_css : '';
	}

	public function replace_shortcode( $text ) {
		if ( $this->preview ) {
			$shortcodes = Utils::shortcode_for_editor_replace();
		} else {
			$shortcodes = apply_filters( 'emtmpl_common_shortcodes', Utils::common_shortcodes() );
		}

		return str_replace( array_keys( $shortcodes ), array_values( $shortcodes ), $text );
	}

	public function render_html_image( $props ) {
		$src      = isset( $props['attrs']['src'] ) ? $props['attrs']['src'] : '';
		$width    = isset( $props['childStyle']['img'] ) ? $this->parse_styles( $props['childStyle']['img'] ) : '';
		$ol_width = ! empty( $props['childStyle']['img']['width'] ) ? str_replace( 'px', '', $props['childStyle']['img']['width'] ) : '100%';
		$href     = ! empty( $props['attrs']['data-href'] ) ? $props['attrs']['data-href'] : '#';
		$alt      = ! empty( $props['attrs']['data-alt'] ) ? $props['attrs']['data-alt'] : '';
		?>
		<a href="<?php echo esc_attr( $href ) ?>" target="_blank">
			<img alt="<?php echo esc_attr( $alt ); ?>"
			     width="<?php echo esc_attr( $ol_width ) ?>"
			     src='<?php echo esc_url( $src ) ?>' max-width='100%'
			     style='max-width: 100%;vertical-align: middle;<?php echo esc_attr( $width ) ?>'/>
		</a>
		<?php
	}

	public function render_html_text( $props ) {
		$center_on_mobile = ! empty( $props['attrs']['data-center_on_mobile'] ) && $props['attrs']['data-center_on_mobile'] == 'true' ? 'emtmpl-center-on-mobile' : '';
		$content          = isset( $props['content']['text'] ) ? $props['content']['text'] : '';
		$content          = base64_decode( $content );// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
		$content          = $this->replace_shortcode( $content );
		$content          = "<div class='text-item {$center_on_mobile}'>{$content}</div>";

		echo wp_kses( do_shortcode( $content ), emtmpl_allowed_html() );
	}

	public function get_p_inherit_style( $props ) {
		$inherit_style = ! empty( $props['style'] ) ? $props['style'] : [];
		$font_weight   = $inherit_style['font-weight'] ?? 'inherit';
		$font_size     = $inherit_style['font-size'] ?? 'inherit';
		$line_height   = $inherit_style['line-height'] ?? 'inherit';
		$color         = $inherit_style['color'] ?? 'inherit';

		$p_style = [
			"font-weight:{$font_weight}",
			"font-size:{$font_size}",
			"line-height:{$line_height}",
			"color:{$color}",
		];

		return implode( ';', $p_style );
	}

	public function render_html_social( $props ) {
		$align   = $props['style']['text-align'] ?? 'left';
		$socials = [ 'facebook', 'twitter', 'instagram', 'youtube', 'linkedin', 'whatsapp' ];
		$html    = '';
		$width   = ! empty( $props['attrs']['data-width'] ) ? $props['attrs']['data-width'] : 32;

		if ( isset( $props['attrs']['direction'] ) && $props['attrs']['direction'] === 'vertical' ) {
			foreach ( $socials as $social ) {
				$link = isset( $props['attrs'][ $social . '_url' ] ) ? esc_url( $props['attrs'][ $social . '_url' ] ) : '';
				$img  = isset( $props['attrs'][ $social ] ) ? esc_url( $props['attrs'][ $social ] ) : '';
				if ( ! empty( $img ) && ! empty( $link ) ) {
					$html .= "<tr><td valign='top' ><a href='{$link}'><img style='vertical-align: middle' src='{$img}' width='{$width}'></a></td></tr>";
				}
			}
		} else {
			$html = '<tr>';
			foreach ( $socials as $social ) {
				$link = isset( $props['attrs'][ $social . '_url' ] ) ? esc_url( $props['attrs'][ $social . '_url' ] ) : '';
				$img  = isset( $props['attrs'][ $social ] ) ? esc_url( $props['attrs'][ $social ] ) : '';
				if ( ! empty( $img ) && ! empty( $link ) ) {
					$html .= "<td valign='top' style='padding: 0;'><a href='{$link}'><img src='{$img}' width='{$width}'></a></td>";
				}
			}
			$html .= '</tr>';
		}

		$html = "<table class='emtmpl-no-full-width-on-mobile' align='{$align}' border='0' cellpadding='0' cellspacing='0' >$html</table>";
		echo wp_kses( $html, emtmpl_allowed_html() );
	}

	public function render_html_button( $props ) {
		$url           = isset( $props['attrs']['href'] ) ? $this->replace_shortcode( $props['attrs']['href'] ) : '';
		$text          = isset( $props['content']['text'] ) ? $this->replace_shortcode( base64_decode( $props['content']['text'] ) ) : '';
		$text          = str_replace( [ '<p>', '</p>' ], [ '', '' ], $text );
		$align         = $props['style']['text-align'] ?? 'left';
		$style         = isset( $props['childStyle']['a'] ) ? $props['childStyle']['a'] : [];
		$padding       = ! empty( $style['padding'] ) ? $style['padding'] : '';
		$border_radius = ! empty( $style['border-radius'] ) ? $style['border-radius'] : '';
		$bg_color      = ! empty( $style['background-color'] ) ? $style['background-color'] : 'inherit';
		unset( $style['padding'] );

		$style       = $this->parse_styles( $style );
		$text_color  = $props['style']['color'] ?? 'inherit';
		$font_weight = $props['style']['font-weight'] ?? 'normal';
		$width       = $props['childStyle']['a']['width'] ?? '';

		$line_height = $props['style']['line-height'] ?? 1;
		$height      = str_replace( 'px', '', $line_height );

		$a_style = [
			"color:{$text_color} !important",
			"font-weight:{$font_weight}",
			"display:block;text-decoration:none;text-transform:none;margin:0;text-align: center;max-width: 100%",
			"background-color:{$bg_color}",
			"line-height:{$line_height}",
			"height:{$line_height}",
			"border-radius:{$border_radius}",
		];

		?>
		<table align='<?php echo esc_attr( $align ) ?>' width='<?php echo esc_attr( $width ) ?>' height="<?php echo esc_attr( $height ) ?>"
		       class='emtmpl-button-responsive' border='0' cellpadding='0' cellspacing='0' role='presentation'
		       style='border-collapse:separate;width: <?php echo esc_attr( $width ) ?>;'>
			<tr>
				<td class='emtmpl-mobile-button-padding' align='center' valign='middle' role='presentation'
				    height="<?php echo esc_attr( $height ) ?>" style='<?php echo esc_attr( $style ) ?>;'>

					<a href='<?php echo esc_attr( do_shortcode( $url ) ) ?>' target='_blank' style='<?php echo esc_attr( implode( ';', $a_style ) ) ?>'>
                          <span style='color: <?php echo esc_attr( $text_color ) ?>'>
                              <?php echo wp_kses( do_shortcode( $text ), emtmpl_allowed_html() );// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode ?>
                          </span>
					</a>

				</td>
			</tr>
		</table>

		<?php
	}

	public function render_html_menu( $props ) {
		$color       = $props['style']['color'] ?? 'inherit';
		$font_weight = $props['style']['font-weight'] ?? 'inherit';
		?>
		<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0' style='border-collapse: separate;margin: 0; padding:0'>
			<?php
			if ( isset( $props['content'] ) && is_array( $props['content'] ) ) {
				$count_text = count( array_filter( $props['content'] ) );
				$count_link = count( array_filter( $props['attrs'] ) );
				$col        = min( $count_text, $count_link ) ? 100 / min( $count_text, $count_link ) . '%' : '';

				if ( isset( $props['attrs']['direction'] ) && $props['attrs']['direction'] === 'vertical' ) {
					foreach ( $props['content'] as $key => $value ) {

						$link = isset( $props['attrs'][ $key ] ) ? $this->replace_shortcode( $props['attrs'][ $key ] ) : '';

						if ( empty( $value ) || ! $link ) {
							continue;
						} ?>
						<tr>
							<td valign='top'>
								<a href='<?php echo esc_url( $link ) ?>'
								   style='color: <?php echo esc_attr( $color ) ?>; font-weight: <?php echo esc_attr( $font_weight ) ?>;font-style:inherit;'>
									<?php echo wp_kses( base64_decode( $value ), emtmpl_allowed_html() );// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode  ?>
								</a>
							</td>
						</tr>
					<?php }
				} else { ?>
					<tr>
						<?php
						foreach ( $props['content'] as $key => $value ) {

							$link = isset( $props['attrs'][ $key ] ) ? $this->replace_shortcode( $props['attrs'][ $key ] ) : '';

							if ( empty( $value ) || ! $link ) {
								continue;
							}
							?>
							<td valign='top' width='<?php echo esc_attr( $col ) ?>'>
								<a href='<?php echo esc_url( $link ) ?>'
								   style='color: <?php echo esc_attr( $color ) ?>; font-weight: <?php echo esc_attr( $font_weight ) ?>; font-style: inherit'>
									<?php echo wp_kses( base64_decode( $value ), emtmpl_allowed_html() );// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode ?>
								</a>
							</td>
						<?php } ?>
					</tr>
				<?php }
			} ?>
		</table>
		<?php
	}

	public function render_html_divider( $props ) {
		$style = isset( $props['childStyle']['hr'] ) ? $this->parse_styles( $props['childStyle']['hr'] ) : '';
		?>
		<table width='100%' border='0' cellpadding='0' cellspacing='0' style="margin: 10px 0;">
			<tr>
				<td valign='top' style="border-width: 0;<?php echo esc_attr( $style ) ?>"></td>
			</tr>
		</table>
		<?php
	}

	public function render_html_spacer( $props ) {
		$style         = isset( $props['childStyle']['.emtmpl-spacer'] ) ? $this->parse_styles( $props['childStyle']['.emtmpl-spacer'] ) : '';
		$mobile_hidden = ! empty( $props['attrs']['mobile-hidden'] ) && $props['attrs']['mobile-hidden'] == 'true' ? 'emtmpl-mobile-hidden' : '';
		?>
		<table width='100%' border='0' cellpadding='0' cellspacing='0' style='font-size:0 !important;margin:0;' class='<?php echo esc_attr( $mobile_hidden ) ?>'>
			<tr>
				<td valign='top' style='<?php echo esc_attr( $style ) ?>'></td>
			</tr>
		</table>
		<?php
	}

	public function render_html_contact( $props ) {
		$align       = $props['style']['text-align'] ?? 'left';
		$color       = $props['style']['color'] ?? 'inherit';
		$font_size   = $props['style']['font-size'] ?? 'inherit';
		$font_weight = $props['style']['font-weight'] ?? 'inherit';
		$style       = "color: {$color};font-size: {$font_size};font-weight: $font_weight;vertical-align:sub;";
		?>
		<table align='<?php echo esc_attr( $align ) ?>'>
			<?php
			if ( ! empty( $props['attrs']['home'] ) && ! empty( $props['content']['home_text'] ) ) {
				$url  = isset( $props['attrs']['home_link'] ) ? $this->replace_shortcode( $props['attrs']['home_link'] ) : '';
				$text = isset( $props['content']['home_text'] ) ? $this->replace_shortcode( base64_decode( $props['content']['home_text'] ) ) : ''; // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode
				?>
				<tr>
					<td valign='top'><img src='<?php echo esc_url( $props['attrs']['home'] ) ?>' style='padding-right: 3px;'></td>
					<td valign='top'><a style='<?php echo esc_attr( $style ) ?>' href='<?php echo esc_url( $url ) ?>'>
							<?php echo wp_kses( $text, emtmpl_allowed_html() ) ?>
						</a>
					</td>
				</tr>
				<?php
			}

			if ( ! empty( $props['attrs']['email'] ) && ! empty( $props['attrs']['email_link'] ) ) {
				$email_url = $this->replace_shortcode( $props['attrs']['email_link'] );
				?>
				<tr>
					<td valign='top'><img src='<?php echo esc_url( $props['attrs']['email'] ) ?>' style='padding-right: 3px;'></td>
					<td valign='top'>
						<a style='<?php echo esc_attr( $style ) ?>' href='mailto:<?php echo esc_attr( $email_url ) ?>'>
							<?php echo esc_html( $email_url ) ?>
						</a>
					</td>
				</tr>
				<?php
			}

			if ( ! empty( $props['attrs']['phone'] ) && ! empty( $props['content']['phone_text'] ) ) {
				?>
				<tr>
					<td valign='top'><img src='<?php echo esc_url( $props['attrs']['phone'] ) ?>' style='padding-right: 3px;'></td>
					<td valign='top'><a style='<?php echo esc_attr( $style ) ?>' href='tel:<?php echo esc_attr( $props['content']['phone_text'] ) ?>'>
							<?php echo wp_kses( base64_decode( $props['content']['phone_text'] ), emtmpl_allowed_html() );// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_decode?>
						</a>
					</td>
				</tr>
				<?php
			}
			?>
		</table>
		<?php
	}

	public function render_html_recover_content( $props ) {
		$link_color = $props['childStyle']['a']['color'] ?? '#278de7';
		$p          = ! empty( $props['childStyle']['p'] ) ? $this->parse_styles( $props['childStyle']['p'] ) : '';
		$a          = ! empty( $props['childStyle']['a'] ) ? $this->parse_styles( $props['childStyle']['a'] ) : '';

		$this->custom_css .= $a ? "#emtmpl-transferred-content a{{$a}}" : '';
		$this->custom_css .= "#emtmpl-transferred-content a{color:{$link_color} !important;}";

		$content = $this->data['content'] ?? '';
		if ( $content ) {
			$content = nl2br( $content );
			printf( '<div id="emtmpl-transferred-content" style="%s">', esc_attr( $p ) );
			echo wp_kses( $content, emtmpl_allowed_html() ) . '</div>';
		}
	}

	public function render_html_post( $props ) {
		$cat           = ! empty( $props['attrs']['data-post-category'] ) ? $props['attrs']['data-post-category'] : '';
		$title_style   = ! empty( $props['childStyle']['.emtmpl-post-title'] ) ? $this->parse_styles( $props['childStyle']['.emtmpl-post-title'] ) : '';
		$content_style = ! empty( $props['childStyle']['.emtmpl-post-content'] ) ? $this->parse_styles( $props['childStyle']['.emtmpl-post-content'] ) : '';
		$title_limit   = ! empty( $props['attrs']['data-title-limit'] ) ? $props['attrs']['data-title-limit'] : 0;
		$content_limit = isset( $props['attrs']['data-content-limit'] ) ? $props['attrs']['data-content-limit'] : 80;
		$row           = ! empty( $props['attrs']['data-max_row'] ) ? $props['attrs']['data-max_row'] : 1;
		$col           = ! empty( $props['attrs']['data-column'] ) ? $props['attrs']['data-column'] : 2;
		$include       = ! empty( $props['attrs']['data-include-post-id'] ) ? explode( ',', $props['attrs']['data-include-post-id'] ) : [];
		$exclude       = ! empty( $props['attrs']['data-exclude-post-id'] ) ? explode( ',', $props['attrs']['data-exclude-post-id'] ) : [];

		$distance = ! empty( $props['childStyle']['.emtmpl-post-distance']['padding'] ) ? $props['childStyle']['.emtmpl-post-distance']['padding'] : 10;
		$distance = explode( ' ', $distance );
		$distance = (int) str_replace( 'px', '', end( $distance ) );

		$h_distance = ! empty( $props['childStyle']['.emtmpl-post-h-distance']['padding'] ) ?
			(int) str_replace( 'px', '', current( explode( ' ', $props['childStyle']['.emtmpl-post-h-distance']['padding'] ) ) ) : 10;

		$full_width = ! empty( $props['childStyle']['.emtmpl-post']['width'] ) ?
			str_replace( [ 'px' ], [ '' ], $props['childStyle']['.emtmpl-post']['width'] ) : 600;

		if ( $row && $col ) {
			$posts = get_posts( [
				'numberposts' => $row * $col,
				'category'    => $cat,
				'include'     => $include,
				'exclude'     => $exclude,
			] );

			if ( empty( $posts ) ) {
				return;
			}

			$count_posts = count( $posts );
			$real_row    = ceil( $count_posts / $col );

			if ( ! $real_row ) {
				return;
			}

			$real_col  = ceil( $count_posts / $real_row );
			$col_width = ( (int) $full_width - ( $distance * ( $real_col - 1 ) ) ) / (int) $real_col;

			for ( $i = 0; $i < $real_row; $i ++ ) {
				?>
				<tr>
					<td valign="top" style="font-size: 0">
						<!--[if mso | IE]>
						<table width="100%" role="presentation" border="0" cellpadding="0" cellspacing="0">
							<tr><![endif]-->
						<?php
						for ( $j = 0; $j < $real_col; $j ++ ) {
							$k = $j + $i * $real_col;

							if ( ! isset( $posts[ $k ] ) ) {
								continue;
							}

							$post         = $posts[ $k ];
							$post_title   = $post->post_title;
							$post_content = get_the_excerpt( $post );
							$link         = get_permalink( $post );
							$img_src      = get_the_post_thumbnail_url( $post, 'thumbnail' );
							$img          = $img_src ? "<img width='100%' src='{$img_src}'>" : '';

							if ( $title_limit != 0 ) {
								$post_title = $title_limit < strlen( $post_title ) ? substr( $post_title, 0, $title_limit ) . '...' : $post_title;
							}

							if ( $content_limit ) {
								$post_content = $content_limit < strlen( $post_content ) ? substr( $post_content, 0, $content_limit ) . '...' : $post_content;
							} else {
								$post_content = '';
							}

							$title_content = "<div style='{$title_style}'>{$post_title}</div><div style='{$content_style}'>{$post_content}</div>";
							$gap           = $j != 0 ? "width:{$distance}px;" : '';
							$_col_width    = $j != 0 ? $col_width + $distance : $col_width;
							?>
							<!--[if mso | IE]>
							<td valign='top' style='width:<?php echo esc_attr( $_col_width ) ?>px;'><![endif]-->
							<div class='emtmpl-responsive '
							     style='display: inline-block;vertical-align: bottom; width:<?php echo esc_attr( $_col_width ) ?>px;font-size: 15px;'>
								<table width='100%' border='0' cellpadding='0' cellspacing='0'>
									<tr>
										<td valign='top' class='emtmpl-mobile-hidden' style='<?php echo esc_attr( $gap ) ?>'></td>
										<td valign='top'>
											<a href='<?php echo esc_url( $link ) ?>'>
												<?php
												if ( $real_col == 1 ) {
													$img_width = $img ? '25%' : '0';
													$padding   = $img ? 'padding-left:10px;' : '';
													?>
													<table width='100%' border='0' cellpadding='0' cellspacing='0'>
														<tr>
															<td valign='top' style='width:<?php echo esc_attr( $img_width ) ?>;'>
																<?php echo wp_kses_post( $img ) ?>
															</td>
															<td valign='top' style='<?php echo esc_attr( $padding ) ?>'>
																<?php echo wp_kses_post( $title_content ) ?>
															</td>
														</tr>
													</table>
													<?php
												} else {
													echo wp_kses_post( $img ); ?>
													<div style='padding: 5px;'></div>
													<?php echo wp_kses_post( $title_content );
												} ?>
											</a>
										</td>
									</tr>
								</table>
								<?php
								if ( $i != $real_row ) { ?>
									<div style='padding-top:<?php echo esc_attr( $h_distance ) ?>px;'></div>
								<?php } ?>
							</div>
							<!--[if mso | IE]></td><![endif]-->
						<?php } ?>
						<!--[if mso | IE]></tr></table><![endif]--></td>
				</tr>
			<?php }
		}
	}

	public function table( $content, $style = '', $width = '100%', $attr = [] ) {
		?>
		<table width='<?php echo esc_attr( $width ) ?>' border='0' cellpadding='0' cellspacing='0' align='left'
		       style='border-collapse: collapse;<?php echo esc_attr( $style ) ?>'>
			<?php echo wp_kses( $content, emtmpl_allowed_html() ) ?>
		</table>
		<?php
	}

	public function get_style( $props, $layer1, $layer2 = '' ) {
		if ( ! $props || ! $layer1 ) {
			return '';
		}

		if ( $layer2 ) {
			$data = $props[ $layer1 ][ $layer2 ] ?? '';
		} else {
			$data = $props[ $layer1 ] ?? '';
		}

		return $this->parse_styles( $data );
	}
}

