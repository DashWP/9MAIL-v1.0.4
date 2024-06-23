<?php

namespace EmTmpl\Inc;

defined( 'ABSPATH' ) || exit;

class Email_Samples {

	protected static $instance = null;

	private function __construct() {
	}

	public static function init() {
		if ( null == self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public static function default_subject() {
		$subjects = [
			'default'                                => esc_html__( 'WordPress email template', '9mail-wordpress-email-templates-designer' ),
			'wp_new_user_notification_email'         => esc_html__( '[{site_title}] Login Details', '9mail-wordpress-email-templates-designer' ),
			'new_user_email_content'                 => esc_html__( '[{site_title}] Email Change Request', '9mail-wordpress-email-templates-designer' ),
			'password_change_email'                  => esc_html__( '[{site_title}] Password Changed', '9mail-wordpress-email-templates-designer' ),
			'retrieve_password_message'              => esc_html__( '[{site_title}] Password Reset', '9mail-wordpress-email-templates-designer' ),
			'email_change_email'                     => esc_html__( '[{site_title}] Email Changed', '9mail-wordpress-email-templates-designer' ),
			'comment_notification_text'              => esc_html__( '[{site_title}] Comment: {post_title}', '9mail-wordpress-email-templates-designer' ),
			'user_request_action_email_content'      => esc_html__( '[{site_title}] {request_type}', '9mail-wordpress-email-templates-designer' ),
			'wp_privacy_personal_data_email_content' => esc_html__( '[{site_title}] Personal Data Export', '9mail-wordpress-email-templates-designer' ),
			'wp_new_user_notification_email_admin'   => esc_html__( '[{site_title}] New User Registration', '9mail-wordpress-email-templates-designer' ),
			'wp_password_change_notification_email'  => esc_html__( "[{site_title}] Password Changed", '9mail-wordpress-email-templates-designer' ),
			'new_admin_email_content'                => esc_html__( '[{site_title}] New Admin Email Address', '9mail-wordpress-email-templates-designer' ),
			'comment_moderation_text'                => esc_html__( '[{site_title}] Please moderate: {post_title}', '9mail-wordpress-email-templates-designer' ),
			'user_request_confirmed_email_content'   => esc_html__( '[{site_title}] Action Confirmed: {request_type}', '9mail-wordpress-email-templates-designer' ),
		];

		if ( class_exists( 'WPForms' ) ||class_exists('\WPForms\WPForms')) {
			$subjects['wpforms_email_message'] = esc_html__( '[{site_title}] New Entry:', '9mail-wordpress-email-templates-designer' );
		}

		return apply_filters( 'emtmpl_sample_subjects', $subjects );
	}

	public static function origin_sample_templates() {
		return apply_filters( 'emtmpl_sample_templates', [
			'default' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/recover_content","style":{"width":"530px"},"content":{},"attrs":{},"childStyle":{"p":{"font-family":"arial, helvetica, sans-serif","font-size":"16px","font-weight":"400","line-height":"22px","color":"#444444","background-color":"transparent"},"a":{"font-family":"arial, helvetica, sans-serif","font-size":"16px","font-weight":"400","line-height":"22px","color":"#278de7"}}}}}}},"2":"footer_block"}}'
				],
			],

			'wp_new_user_notification_email_admin' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+TmV3IHVzZXIgcmVnaXN0cmF0aW9uIG9uIHlvdXIgc2l0ZSB7c2l0ZV90aXRsZX06PC9wPgo8cD4mbmJzcDs8L3A+CjxwPlVzZXJuYW1lOiB7dXNlcl9uYW1lfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5FLW1haWw6IHt1c2VyX2VtYWlsfTwvcD4="},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'wp_password_change_notification_email' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+UGFzc3dvcmQgY2hhbmdlZCBmb3IgdXNlcjoge3VzZXJfbmFtZX08L3A+"},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'new_admin_email_content' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+SG93ZHkge3VzZXJfbmFtZX0sPC9wPgo8cD4mbmJzcDs8L3A+CjxwPlNvbWVvbmUgd2l0aCBhZG1pbmlzdHJhdG9yIGNhcGFiaWxpdGllcyByZWNlbnRseSByZXF1ZXN0ZWQgdG8gaGF2ZSB0aGU8L3A+CjxwPmFkbWluaXN0cmF0aW9uIGVtYWlsIGFkZHJlc3MgY2hhbmdlZCBvbiB0aGlzIHNpdGU6PC9wPgo8cD57c2l0ZV91cmx9PC9wPgo8cD4mbmJzcDs8L3A+CjxwPlRvIGNvbmZpcm0gdGhpcyBjaGFuZ2UsIHBsZWFzZSBjbGljayBvbiB0aGUgZm9sbG93aW5nIGxpbms6PC9wPgo8cD57YWRtaW5fdXJsfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5Zb3UgY2FuIHNhZmVseSBpZ25vcmUgYW5kIGRlbGV0ZSB0aGlzIGVtYWlsIGlmIHlvdSBkbyBub3Qgd2FudCB0bzwvcD4KPHA+dGFrZSB0aGlzIGFjdGlvbi48L3A+CjxwPiZuYnNwOzwvcD4KPHA+VGhpcyBlbWFpbCBoYXMgYmVlbiBzZW50IHRvIHt1c2VyX2VtYWlsfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5SZWdhcmRzLDwvcD4KPHA+QWxsIGF0IHtzaXRlX3RpdGxlfTwvcD4KPHA+e3NpdGVfdXJsfTwvcD4="},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'comment_moderation_text' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+QSBuZXcgY29tbWVudCBvbiB0aGUgcG9zdCAie3Bvc3RfdGl0bGV9IiBpcyB3YWl0aW5nIGZvciB5b3VyIGFwcHJvdmFsPC9wPgo8cD57cG9zdF91cmx9PC9wPgo8cD4mbmJzcDs8L3A+CjxwPkF1dGhvcjoge2NvbW1lbnRfYXV0aG9yfSAoSVAgYWRkcmVzczoge2NvbW1lbnRfYXV0aG9yX0lQfSwge2NvbW1lbnRfYXV0aG9yX2RvbWFpbn0pPC9wPgo8cD5FbWFpbDoge2NvbW1lbnRfYXV0aG9yX2VtYWlsfTwvcD4KPHA+VVJMOiZuYnNwOyB7Y29tbWVudF9hdXRob3JfdXJsfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5Db21tZW50OiB7Y29tbWVudF9jb250ZW50fTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5BcHByb3ZlIGl0OiB7YXBwcm92ZV9jb21tZW50X3VybH08L3A+CjxwPiZuYnNwOzwvcD4KPHA+VHJhc2ggaXQ6IHt0cmFzaF9jb21tZW50X3VybH08L3A+CjxwPiZuYnNwOzwvcD4KPHA+RGVsZXRlIGl0OiB7ZGVsZXRlX2NvbW1lbnRfdXJsfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5TcGFtIGl0OiB7c3BhbV9jb21tZW50X3VybH08L3A+CjxwPiZuYnNwOzwvcD4KPHA+Q3VycmVudGx5IHtjb21tZW50X2NvdW50fSBjb21tZW50IGlzIHdhaXRpbmcgZm9yIGFwcHJvdmFsLjwvcD4KPHA+UGxlYXNlIHZpc2l0IHRoZSBtb2RlcmF0aW9uIHBhbmVsOiB7bW9kZXJhdGlvbl9wYW5lbF91cmx9PC9wPg=="},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'user_request_confirmed_email_content' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+SG93ZHksPC9wPgo8cD4mbmJzcDs8L3A+CjxwPkEgdXNlciBkYXRhIHByaXZhY3kgcmVxdWVzdCBoYXMgYmVlbiBjb25maXJtZWQgb24ge3NpdGVfdGl0bGV9OjwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5Vc2VyOiB7dXNlcl9lbWFpbH08L3A+CjxwPlJlcXVlc3Q6IHtyZXF1ZXN0X3R5cGV9PC9wPgo8cD4mbmJzcDs8L3A+CjxwPllvdSBjYW4gdmlldyBhbmQgbWFuYWdlIHRoZXNlIGRhdGEgcHJpdmFjeSByZXF1ZXN0cyBoZXJlOjwvcD4KPHA+Jm5ic3A7PC9wPgo8cD57bWFuYWdlX3VybH08L3A+CjxwPiZuYnNwOzwvcD4KPHA+UmVnYXJkcyw8L3A+CjxwPkFsbCBhdCB7c2l0ZV90aXRsZX08L3A+CjxwPntzaXRlX3VybH08L3A+"},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'wp_new_user_notification_email' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+VXNlcm5hbWU6IHt1c2VyX25hbWV9PC9wPgo8cD4mbmJzcDs8L3A+CjxwPlRvIHNldCB5b3VyIHBhc3N3b3JkLCB2aXNpdCB0aGUgZm9sbG93aW5nIGFkZHJlc3M6PC9wPgo8cD4mbmJzcDs8L3A+CjxwPntwYXNzd29yZF91cmx9Jm5ic3A7PC9wPgo8cD4mbmJzcDs8L3A+CjxwPntsb2dpbl91cmx9PC9wPg=="},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'new_user_email_content' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+SG93ZHkge3VzZXJfbmFtZX0sPC9wPgo8cD4mbmJzcDs8L3A+CjxwPllvdSByZWNlbnRseSByZXF1ZXN0ZWQgdG8gaGF2ZSB0aGUgZW1haWwgYWRkcmVzcyBvbiB5b3VyIGFjY291bnQgY2hhbmdlZC48L3A+CjxwPiZuYnNwOzwvcD4KPHA+SWYgdGhpcyBpcyBjb3JyZWN0LCBwbGVhc2UgY2xpY2sgb24gdGhlIGZvbGxvd2luZyBsaW5rIHRvIGNoYW5nZSBpdDo8L3A+CjxwPnthZG1pbl91cmx9PC9wPgo8cD4mbmJzcDs8L3A+CjxwPllvdSBjYW4gc2FmZWx5IGlnbm9yZSBhbmQgZGVsZXRlIHRoaXMgZW1haWwgaWYgeW91IGRvIG5vdCB3YW50IHRvPC9wPgo8cD50YWtlIHRoaXMgYWN0aW9uLjwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5UaGlzIGVtYWlsIGhhcyBiZWVuIHNlbnQgdG8ge25ld19lbWFpbH08L3A+CjxwPiZuYnNwOzwvcD4KPHA+UmVnYXJkcyw8L3A+CjxwPkFsbCBhdCB7c2l0ZV90aXRsZX08L3A+CjxwPntzaXRlX3VybH08L3A+"},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],
			//--------------

			'password_change_email' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+SGkge3VzZXJfbmFtZX0sPC9wPgo8cD4mbmJzcDs8L3A+CjxwPlRoaXMgbm90aWNlIGNvbmZpcm1zIHRoYXQgeW91ciBwYXNzd29yZCB3YXMgY2hhbmdlZCBvbiB7c2l0ZV90aXRsZX0uPC9wPgo8cD4mbmJzcDs8L3A+CjxwPklmIHlvdSBkaWQgbm90IGNoYW5nZSB5b3VyIHBhc3N3b3JkLCBwbGVhc2UgY29udGFjdCB0aGUgU2l0ZSBBZG1pbmlzdHJhdG9yIGF0PC9wPgo8cD57YWRtaW5fZW1haWx9PC9wPgo8cD4mbmJzcDs8L3A+CjxwPlRoaXMgZW1haWwgaGFzIGJlZW4gc2VudCB0byB7dXNlcl9lbWFpbH08L3A+CjxwPiZuYnNwOzwvcD4KPHA+UmVnYXJkcyw8L3A+CjxwPkFsbCBhdCB7c2l0ZV90aXRsZX08L3A+CjxwPntzaXRlX3VybH08L3A+"},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'retrieve_password_message' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+U29tZW9uZSBoYXMgcmVxdWVzdGVkIGEgcGFzc3dvcmQgcmVzZXQgZm9yIHRoZSBmb2xsb3dpbmcgYWNjb3VudDombmJzcDs8L3A+CjxwPiZuYnNwOzwvcD4KPHA+U2l0ZSBOYW1lOiB7c2l0ZV90aXRsZX08L3A+CjxwPiZuYnNwOzwvcD4KPHA+VXNlcm5hbWU6IHt1c2VyX25hbWV9PC9wPgo8cD4mbmJzcDs8L3A+CjxwPklmIHRoaXMgd2FzIGEgbWlzdGFrZSxpZ25vcmUgdGhpcyBlbWFpbCBhbmQgbm90aGluZyB3aWxsIGhhcHBlbi4mbmJzcDs8L3A+CjxwPiZuYnNwOzwvcD4KPHA+VG8gcmVzZXQgeW91ciBwYXNzd29yZCwgdmlzaXQgdGhlIGZvbGxvd2luZyBhZGRyZXNzOjwvcD4KPHA+Jm5ic3A7PC9wPgo8cD57cmVzZXRfcGFzc3dvcmRfdXJsfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5UaGlzIHBhc3N3b3JkIHJlc2V0IHJlcXVlc3Qgb3JpZ2luYXRlZCBmcm9tIHRoZSBJUCBhZGRyZXNzIHt1c2VyX0lQfTwvcD4="},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'email_change_email' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+SGkge3VzZXJfbmFtZX0sPC9wPgo8cD4mbmJzcDs8L3A+CjxwPlRoaXMgbm90aWNlIGNvbmZpcm1zIHRoYXQgeW91ciBlbWFpbCBhZGRyZXNzIG9uIHtzaXRlX3RpdGxlfSB3YXMgY2hhbmdlZCB0byB7bmV3X2VtYWlsfS48L3A+CjxwPiZuYnNwOzwvcD4KPHA+SWYgeW91IGRpZCBub3QgY2hhbmdlIHlvdXIgZW1haWwsIHBsZWFzZSBjb250YWN0IHRoZSBTaXRlIEFkbWluaXN0cmF0b3IgYXQ8L3A+CjxwPnthZG1pbl9lbWFpbH08L3A+CjxwPiZuYnNwOzwvcD4KPHA+VGhpcyBlbWFpbCBoYXMgYmVlbiBzZW50IHRvIHtjdXJyZW50X2VtYWlsfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5SZWdhcmRzLDwvcD4KPHA+QWxsIGF0IHtzaXRlX3RpdGxlfTwvcD4KPHA+e3NpdGVfdXJsfTwvcD4="},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'comment_notification_text' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+TmV3IGNvbW1lbnQgb24geW91ciBwb3N0ICJ7cG9zdF90aXRsZX0iPC9wPgo8cD4mbmJzcDs8L3A+CjxwPkF1dGhvcjoge2NvbW1lbnRfYXV0aG9yfSAoSVAgYWRkcmVzczoge2NvbW1lbnRfYXV0aG9yX0lQfSwge2NvbW1lbnRfYXV0aG9yX2RvbWFpbn0pPC9wPgo8cD5FbWFpbDoge2NvbW1lbnRfYXV0aG9yX2VtYWlsfTwvcD4KPHA+VVJMOiB7Y29tbWVudF9hdXRob3JfdXJsfTwvcD4KPHA+Q29tbWVudDoge2NvbW1lbnRfY29udGVudH08L3A+CjxwPiZuYnNwOzwvcD4KPHA+WW91IGNhbiBzZWUgYWxsIGNvbW1lbnRzIG9uIHRoaXMgcG9zdCBoZXJlOiB7cG9zdF91cmx9PC9wPgo8cD4mbmJzcDs8L3A+CjxwPlBlcm1hbGluazoge2NvbW1lbnRfdXJsfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5UcmFzaCBpdDoge3RyYXNoX2NvbW1lbnRfdXJsfTwvcD4KPHA+U3BhbSBpdDoge3NwYW1fY29tbWVudF91cmx9PC9wPgo8cD5EZWxldGUgaXQ6IHtkZWxldGVfY29tbWVudF91cmx9PC9wPg=="},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'user_request_action_email_content' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+SG93ZHksPC9wPgo8cD4mbmJzcDs8L3A+CjxwPkEgcmVxdWVzdCBoYXMgYmVlbiBtYWRlIHRvIHBlcmZvcm0gdGhlIGZvbGxvd2luZyBhY3Rpb24gb24geW91ciBhY2NvdW50OiB7cmVxdWVzdF90eXBlfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5UbyBjb25maXJtIHRoaXMsIHBsZWFzZSBjbGljayBvbiB0aGUgZm9sbG93aW5nIGxpbms6PC9wPgo8cD57Y29uZmlybV91cmx9PC9wPgo8cD4mbmJzcDs8L3A+CjxwPllvdSBjYW4gc2FmZWx5IGlnbm9yZSBhbmQgZGVsZXRlIHRoaXMgZW1haWwgaWYgeW91IGRvIG5vdCB3YW50IHRvPC9wPgo8cD50YWtlIHRoaXMgYWN0aW9uLjwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5SZWdhcmRzLDwvcD4KPHA+QWxsIGF0IHtzaXRlX3RpdGxlfTwvcD4KPHA+e3NpdGVfdXJsfTwvcD4="},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'wp_privacy_personal_data_email_content' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+SG93ZHksPC9wPgo8cD4mbmJzcDs8L3A+CjxwPllvdXIgcmVxdWVzdCBmb3IgYW4gZXhwb3J0IG9mIHBlcnNvbmFsIGRhdGEgaGFzIGJlZW4gY29tcGxldGVkLiBZb3UgbWF5PC9wPgo8cD5kb3dubG9hZCB5b3VyIHBlcnNvbmFsIGRhdGEgYnkgY2xpY2tpbmcgb24gdGhlIGxpbmsgYmVsb3cuIEZvciBwcml2YWN5PC9wPgo8cD5hbmQgc2VjdXJpdHksIHdlIHdpbGwgYXV0b21hdGljYWxseSBkZWxldGUgdGhlIGZpbGUgb24ge2V4cGlyYXRpb259LDwvcD4KPHA+c28gcGxlYXNlIGRvd25sb2FkIGl0IGJlZm9yZSB0aGVuLjwvcD4KPHA+Jm5ic3A7PC9wPgo8cD57ZXhwb3J0X2ZpbGVfdXJsfTwvcD4KPHA+Jm5ic3A7PC9wPgo8cD5SZWdhcmRzLDwvcD4KPHA+QWxsIGF0IHtzaXRlX3RpdGxlfTwvcD4KPHA+e3NpdGVfdXJsfTwvcD4="},"attrs":{},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

			'wpforms_email_message' => [
				'basic' => [
					'name' => esc_html__( 'Basic', '9mail-wordpress-email-templates-designer' ),
					'data' => '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":"header_block","1":{"props":{"style_outer":{"padding":"35px","background-image":"none","background-color":"#ffffff","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#3c434a"},"content":{"text":"PHA+e2FsbF9maWVsZHN9PC9wPg=="},"attrs":{"data-center_on_mobile":""},"childStyle":{}}}}}},"2":"footer_block"}}'
				],
			],

		] );
	}

	public static function sample_header() {
		return '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":{"props":{"style_outer":{"padding":"15px 35px","background-image":"none","background-color":"#162447","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"transparent","width":"100%"},"type":"layout/grid2cols","dataCols":"2"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"265px"}},"elements":{"0":{"type":"html/text","style":{"width":"265px","line-height":"30px","background-image":"none","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444"},"content":{"text":"PHA+PHNwYW4gc3R5bGU9ImZvbnQtc2l6ZTogMjBweDsgY29sb3I6ICNmZmZmZmY7Ij5ZT1VSIExPR088L3NwYW4+PC9wPg=="},"attrs":{},"childStyle":{}}}},"1":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"265px"}},"elements":{"0":{"type":"html/text","style":{"width":"265px","line-height":"30px","background-image":"none","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444"},"content":{"text":"PHAgc3R5bGU9InRleHQtYWxpZ246IHJpZ2h0OyI+PHNwYW4gc3R5bGU9ImNvbG9yOiAjZmZmZmZmOyI+SGVscCBDZW50ZXI8L3NwYW4+PC9wPg=="},"attrs":{},"childStyle":{}}}}}}}}';
	}

	public static function sample_footer() {
		return '{"style_container":{"background-color":"#f2f2f2","background-image":"none","width":600,"responsive":"380"},"rows":{"0":{"props":{"style_outer":{"padding":"25px 35px","background-image":"none","background-color":"#162447","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"100%"},"type":"layout/grid1cols","dataCols":"1"},"cols":{"0":{"props":{"style":{"padding":"0px","background-image":"none","background-color":"transparent","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444","width":"530px"}},"elements":{"0":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444"},"content":{"text":"PHAgc3R5bGU9InRleHQtYWxpZ246IGNlbnRlcjsiPjxzcGFuIHN0eWxlPSJjb2xvcjogI2Y1ZjVmNTsgZm9udC1zaXplOiAyMHB4OyI+R2V0IGluIFRvdWNoPC9zcGFuPjwvcD4="},"attrs":{},"childStyle":{}},"1":{"type":"html/social","style":{"width":"530px","text-align":"center","padding":"20px 0px 0px","background-image":"none","background-color":"transparent"},"content":{},"attrs":{"facebook":"' . EMTMPL_PR_CONST['img_url'] . 'fb-blue-white.png","facebook_url":"#","twitter":"' . EMTMPL_PR_CONST['img_url'] . 'twi-cyan-white.png","twitter_url":"#","instagram":"' . EMTMPL_PR_CONST['img_url'] . 'ins-white-color.png","instagram_url":"#","youtube":"' . EMTMPL_PR_CONST['img_url'] . 'yt-color-white.png","youtube_url":"","linkedin":"' . EMTMPL_PR_CONST['img_url'] . 'li-color-white.png","linkedin_url":"","whatsapp":"' . EMTMPL_PR_CONST['img_url'] . 'wa-color-white.png","whatsapp_url":"","direction":"","data-width":""},"childStyle":{}},"2":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"20px 0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444"},"content":{"text":"PHAgc3R5bGU9InRleHQtYWxpZ246IGNlbnRlcjsiPjxzcGFuIHN0eWxlPSJjb2xvcjogI2Y1ZjVmNTsgZm9udC1zaXplOiAxMnB4OyI+VGhpcyBlbWFpbCB3YXMgc2VudCBieSA6IDxzcGFuIHN0eWxlPSJjb2xvcjogI2ZmZmZmZjsiPjxhIHN0eWxlPSJjb2xvcjogI2ZmZmZmZjsiIGhyZWY9Im1haWx0bzp7YWRtaW5fZW1haWx9Ij57YWRtaW5fZW1haWx9PC9hPjwvc3Bhbj48L3NwYW4+PC9wPgo8cCBzdHlsZT0idGV4dC1hbGlnbjogY2VudGVyOyI+PHNwYW4gc3R5bGU9ImNvbG9yOiAjZjVmNWY1OyBmb250LXNpemU6IDEycHg7Ij5Gb3IgYW55IHF1ZXN0aW9ucyBwbGVhc2Ugc2VuZCBhbiBlbWFpbCB0byA8c3BhbiBzdHlsZT0iY29sb3I6ICNmZmZmZmY7Ij48YSBzdHlsZT0iY29sb3I6ICNmZmZmZmY7IiBocmVmPSJtYWlsdG86e2FkbWluX2VtYWlsfSI+e2FkbWluX2VtYWlsfTwvYT48L3NwYW4+PC9zcGFuPjwvcD4="},"attrs":{},"childStyle":{}},"3":{"type":"html/text","style":{"width":"530px","line-height":"22px","background-image":"none","background-color":"transparent","padding":"0px","border-left-width":"0px","border-top-width":"0px","border-right-width":"0px","border-bottom-width":"0px","border-radius":"0px","border-color":"#444444"},"content":{"text":"PHAgc3R5bGU9InRleHQtYWxpZ246IGNlbnRlcjsiPjxzcGFuIHN0eWxlPSJjb2xvcjogI2Y1ZjVmNTsiPjxzcGFuIHN0eWxlPSJjb2xvcjogI2Y1ZjVmNTsiPjxzcGFuIHN0eWxlPSJmb250LXNpemU6IDEycHg7Ij48YSBzdHlsZT0iY29sb3I6ICNmNWY1ZjU7IiBocmVmPSIjIj5Qcml2YWN5IFBvbGljeTwvYT4mbmJzcDsgfCZuYnNwOyA8YSBzdHlsZT0iY29sb3I6ICNmNWY1ZjU7IiBocmVmPSIjIj5IZWxwIENlbnRlcjwvYT48L3NwYW4+PC9zcGFuPjwvc3Bhbj48L3A+"},"attrs":{},"childStyle":{}}}}}}}}';
	}

	public static function sample_templates( $header, $footer ) {
		$header      = strval( $header );
		$footer      = strval( $footer );
		$all_samples = self::origin_sample_templates();

		foreach ( $all_samples as $key => $samples ) {
			foreach ( $samples as $type => $fields ) {
				$full_template = $fields['data'];
				$full_template = str_replace( [ '"header_block"', '"footer_block"' ], [ $header, $footer ], $full_template );

				$all_samples[ $key ][ $type ]['data'] = $full_template;
			}
		}

		return $all_samples;
	}

}

