<?php defined( 'ABSPATH' ) || exit; ?>
<div>
    <div class="emtmpl-setting-row">
        <div class="emtmpl-option-label">
			<?php esc_html_e( 'Email type', '9mail-wordpress-email-templates-designer' ); ?>
        </div>
        <select class="emtmpl-input emtmpl-set-email-type" name="emtmpl_settings_type" required>
			<?php
			if ( ! empty( $common_emails ) && is_array( $common_emails ) ) {
				foreach ( $common_emails as $id => $title ) {
					printf( "<option value='%s' %s>%s</option>", esc_attr( $id ), selected( $type_selected, $id, false ), esc_html( $title ) );
				}
			}

			if ( ! empty( $admin_emails ) && is_array( $admin_emails ) ) {
				?>
                <optgroup label="<?php esc_attr_e( 'Admin', '9mail-wordpress-email-templates-designer' ); ?>">
					<?php
					foreach ( $admin_emails as $id => $title ) {
						printf( "<option value='%s' %s>%s</option>", esc_attr( $id ), selected( $type_selected, $id, false ), esc_html( $title ) );
					}
					?>
                </optgroup>
				<?php
			}

			if ( ! empty( $user_emails ) && is_array( $user_emails ) ) {
				?>
                <optgroup label="<?php esc_attr_e( 'User', '9mail-wordpress-email-templates-designer' ); ?>">
					<?php
					foreach ( $user_emails as $id => $title ) {
						printf( "<option value='%s' %s>%s</option>", esc_attr( $id ), selected( $type_selected, $id, false ), esc_html( $title ) );
					}
					?>
                </optgroup>
				<?php
			}

			do_action( 'emtmpl_select_email_types', $type_selected );
			?>
        </select>
    </div>
	<?php do_action( 'emtmpl_setting_options', $type_selected ); ?>
    <div>
        <div class="emtmpl-option-label">
			<?php esc_html_e( 'Direction', '9mail-wordpress-email-templates-designer' ); ?>
        </div>

		<?php
		$directions = [
			'ltr' => esc_html__( 'Left to right', '9mail-wordpress-email-templates-designer' ),
			'rtl' => esc_html__( 'Right to left', '9mail-wordpress-email-templates-designer' )
		];
		?>
        <select class="emtmpl-settings-direction" name="emtmpl_settings_direction">
			<?php
			foreach ( $directions as $value => $text ) {
				printf( '<option value="%s" %s>%s</option>', esc_attr( $value ), selected( $direction_selected, $value, false ), esc_html( $text ) );
			}
			?>
        </select>
    </div>

    <div class="emtmpl-setting-row" data-attr="country">
		<?php
		if ( function_exists( 'icl_get_languages' ) ) {
			$languages = icl_get_languages();
			?>
            <div class="emtmpl-option-label"><?php esc_html_e( 'Apply to languages', 'emtmpl-email-template-customizer' ) ?></div>
            <select name="emtmpl_setting_languages[]" class="emtmpl-select2 emtmpl-input" multiple data-placeholder="All languages">
				<?php
				foreach ( $languages as $data ) {
					$selected = in_array( $data['language_code'], $languages_selected ) ? 'selected' : '';
					printf( '<option value="%s" %s>%s</option>', esc_attr( $data['language_code'] ), esc_attr( $selected ), esc_html( $data['native_name'] ) );
				}
				?>
            </select>
			<?php
		}
		?>
    </div>

    <div class="emtmpl-setting-row">
        <div class="emtmpl-option-label"><?php esc_html_e( 'Attachment files', 'emtmpl-email-template-customizer' ) ?></div>
        <div class="emtmpl-attachments-list">
			<?php
			$files = get_post_meta( $post->ID, 'emtmpl_attachments', true );
			if ( ! empty( $files ) && is_array( $files ) ) {
				foreach ( $files as $file_id ) {
					$file = get_post( $file_id );
					if ( ! $file ) {
						continue;
					}
					$href = admin_url( "upload.php?item={$file->ID}" );
					?>
                    <div class="emtmpl-attachment-el vi-ui button tiny">
                        <a href="<?php echo esc_url( $href ) ?>" target="_blank"><?php echo esc_html( $file->post_title ) ?></a>
                        <input type="hidden" name="emtmpl_attachments[]" value="<?php echo esc_attr( $file->ID ) ?>">
                        <i class="emtmpl-remove-attachment dashicons dashicons-no-alt"> </i>
                    </div>
					<?php
				}
			}
			?>
        </div>
        <div>
        <span class="vi-ui button tiny emtmpl-add-attachment-file">
            <?php esc_html_e( 'Add file', '9mail-wordpress-email-templates-designer' ); ?>
        </span>
        </div>
    </div>

</div>
