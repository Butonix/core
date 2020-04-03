/* global WPMailSMTP, jQuery, wp_mail_smtp, wp_mail_smtp_pro */

var WPMailSMTP = window.WPMailSMTP || {};
WPMailSMTP.Admin = WPMailSMTP.Admin || {};
WPMailSMTP.Admin.Settings = WPMailSMTP.Admin.Settings || {};

/**
 * WP Mail SMTP Admin area module.
 *
 * @since 1.5.0
 */
WPMailSMTP.Admin.Settings.Pro = WPMailSMTP.Admin.Settings.Pro || (function ( document, window, $ ) {

	'use strict';

	/**
	 * Private functions and properties.
	 *
	 * @since 1.5.0
	 *
	 * @type {Object}
	 */
	var __private = {

		/**
		 * Whether the email is valid.
		 *
		 * @since 1.5.0
		 *
		 * @param {string} email
		 *
		 * @return {boolean}
		 */
		isEmailValid: function ( email ) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test( String( email ).toLowerCase() );
		},
	};

	/**
	 * Public functions and properties.
	 *
	 * @since 1.5.0
	 *
	 * @type {Object}
	 */
	var app = {

		/**
		 * Start the engine. DOM is not ready yet, use only to init something.
		 *
		 * @since 1.5.0
		 */
		init: function () {

			// Do that when DOM is ready.
			$( document ).ready( app.ready );
		},

		/**
		 * DOM is fully loaded.
		 *
		 * @since 1.5.0
		 */
		ready: function () {

			app.pageHolder = $( '.wp-mail-smtp-tab-settings' );

			app.bindActions();
		},

		/**
		 * Process all generic actions/events, mostly custom that were fired by our API.
		 *
		 * @since 1.5.0
		 */
		bindActions: function () {

			app.license.bindActions();
			app.amazonses.bindActions();
		},

		/**
		 * License management.
		 *
		 * @since 1.5.0
		 *
		 * @type {Object}
		 */
		license: {

			/**
			 * Generate a notice about performed action.
			 *
			 * @since 1.5.0
			 *
			 * @param {string} noticeType
			 * @param {string} message
			 *
			 * @return {string} Process HTML ready to be inserted into DOM.
			 */
			getNoticeHtml: function( noticeType, message ) {
				return '<div class="notice ' + noticeType + ' wp-mail-smtp-license-notice is-dismissible"><p>' + message + '</p>';
			},

			/**
			 * Process all license-related actions/events.
			 *
			 * @since 1.5.0
			 */
			bindActions: function () {

				app.pageHolder.on( 'click', '#wp-mail-smtp-setting-license-key-verify', this.verify );
				app.pageHolder.on( 'click', '#wp-mail-smtp-setting-license-key-deactivate', this.deactivate );
				app.pageHolder.on( 'click', '#wp-mail-smtp-setting-license-key-refresh', this.refresh );
			},

			/**
			 * Verify a license key. Ajaxified.
			 *
			 * @since 1.5.0
			 *
			 * @param {object} event jQuery event.
			 */
			verify: function ( event ) {

				var $btn = jQuery( event.target ),
					$row = $btn.closest( '.wp-mail-smtp-setting-row' ),
					data = {
						action: 'wp_mail_smtp_pro_license_ajax',
						task: 'license_verify',
						nonce: $( '#wp-mail-smtp-setting-license-nonce', $row ).val(),
						license: $( '#wp-mail-smtp-setting-license-key', $row ).val()
					};

				$btn.prop( 'disabled', true );

				$.post( ajaxurl, data, function ( response ) {

					var message,
						noticeType;

					if ( response.success ) {
						message    = response.data.message;
						noticeType = 'notice-success';

						$row.find( '.type, .desc, #wp-mail-smtp-setting-license-key-deactivate' ).show();
						$row.find( '.type strong' ).text( response.data.type );
					}
					else {
						message    = response.data;
						noticeType = 'notice-error';

						$row.find( '.type, .desc, #wp-mail-smtp-setting-license-key-deactivate' ).hide();
					}

					$( '.wp-mail-smtp-license-notice', app.pageHolder ).remove();

					app.pageHolder.find( 'h1' ).after( app.license.getNoticeHtml( noticeType, message ) );

					$btn.prop( 'disabled', false );

				} ).fail( function ( xhr ) {
					console.log( xhr.responseText );
				} );
			},

			/**
			 * Deactivate a license key. Ajaxified.
			 *
			 * @since 1.5.0
			 *
			 * @param {object} event jQuery event.
			 */
			deactivate: function ( event ) {

				var $btn = jQuery( event.target ),
					$row = $btn.closest( '.wp-mail-smtp-setting-row' ),
					data = {
						action: 'wp_mail_smtp_pro_license_ajax',
						task: 'license_deactivate',
						nonce: $( '#wp-mail-smtp-setting-license-nonce', $row ).val()
					};

				$btn.prop( 'disabled', true );

				$.post( ajaxurl, data, function ( response ) {

					var message = response.data,
						noticeType;

					if ( response.success ) {
						noticeType = 'notice-success';

						$row.find( '#wp-mail-smtp-setting-license-key' ).val( '' );
						$row.find( '.type strong' ).text( 'lite' );
						$row.find( '.desc, #wp-mail-smtp-setting-license-key-deactivate' ).hide();
					}
					else {
						noticeType = 'notice-error';
					}

					$( '.wp-mail-smtp-license-notice', app.pageHolder ).remove();

					app.pageHolder.find( 'h1' ).after( app.license.getNoticeHtml( noticeType, message ) );

					$btn.prop( 'disabled', false );

				} ).fail( function ( xhr ) {
					console.log( xhr.responseText );
				} );
			},

			/**
			 * Refresh a license key (get its type/status). Ajaxified.
			 *
			 * @since 1.5.0
			 *
			 * @param {object} event jQuery event.
			 */
			refresh: function ( event ) {

				event.preventDefault();

				var $btn = jQuery( event.target ),
					$row = $btn.closest( '.wp-mail-smtp-setting-row' ),
					data = {
						action: 'wp_mail_smtp_pro_license_ajax',
						task: 'license_refresh',
						nonce: $( '#wp-mail-smtp-setting-license-nonce', $row ).val()
					};

				$btn.prop( 'disabled', true );

				$.post( ajaxurl, data, function ( response ) {

					var message,
						noticeType;

					if ( response.success ) {
						message    = response.data.message;
						noticeType = 'notice-success';

						$row.find( '.type strong' ).text( response.data.type );
					}
					else {
						message    = response.data;
						noticeType = 'notice-error';

						$row.find( '.desc, #wp-mail-smtp-setting-license-key-deactivate' ).hide();
					}

					$( '.wp-mail-smtp-license-notice', app.pageHolder ).remove();

					app.pageHolder.find( 'h1' ).after( app.license.getNoticeHtml( noticeType, message ) );

					$btn.prop( 'disabled', false );

				} ).fail( function ( xhr ) {
					console.log( xhr.responseText );
				} );
			}
		},

		/**
		 * AmazonSES specific methods.
		 *
		 * @since 1.5.0
		 *
		 * @type {Object}
		 */
		amazonses: {

			/**
			 * Process all AmazonSES actions/events.
			 *
			 * @since 1.5.0
			 */
			bindActions: function () {
				jQuery( '.js-wp-mail-smtp-providers-amazonses-email-add' ).on( 'click', this.processEmailAdd );
				app.pageHolder.on( 'click', '.js-wp-mail-smtp-providers-amazonses-email-delete', this.processEmailDelete );
				app.pageHolder.on( 'click', '.js-wp-mail-smtp-providers-amazonses-email-resend', this.processEmailResend );
				app.pageHolder.on( 'click', '.js-wp-mail-smtp-providers-amazonses-email-resend-delete', this.processEmailResendDelete );
			},

			/**
			 * Process the click on an Add Email button.
			 *
			 * @since 1.5.0
			 *
			 * @param {object} event jQuery event.
			 */
			processEmailAdd: function ( event ) {
				event.preventDefault();

				var $btn = jQuery( event.target );
				var $email = $btn.siblings( 'input[type="email"]' );
				var email = $email.val();
				var nonce = $btn.siblings( 'input[name="wp_mail_smtp_pro_amazonses_email_add"]' ).val();
				var $holder = jQuery( '#wp-mail-smtp-providers-amazonses-email-enter' );

				if ( $btn.hasClass( 'disabled' ) ) {
					return false;
				}

				if ( !__private.isEmailValid( email ) ) {
					$holder.find( 'p.response' ).remove();
					$holder.append( '<p class="response error">' + wp_mail_smtp_pro.ses_text_email_invalid + '</p>' );
					return false;
				}

				if ( email.length && nonce.length ) {

					// Send ajax request.
					jQuery.ajax( {
							  url: ajaxurl,
							  type: 'POST',
							  dataType: 'json',
							  data: {
								  action: 'wp_mail_smtp_pro_providers_ajax',
								  task: 'email_add',
								  mailer: 'amazonses',
								  email: email,
								  nonce: nonce
							  },
							  beforeSend: function () {
								  $holder.find( 'p.response' ).remove();
								  $btn.addClass( 'disabled' );
							  }
						  } )
						  .done( function ( response ) {
							  var p_class = 'response';
							  p_class += response.hasOwnProperty( 'success' ) && response.success ? ' success' : ' error';

							  if ( response.hasOwnProperty( 'success' ) && response.success ) {
								  $email.val( '' );
							  }

							  $holder.append( '<p class="' + p_class + '">' + response.data + '</p>' );
						  } )
						  .fail( function () {
							  $holder.append( '<p class="response error">' + wp_mail_smtp_pro.ses_text_smth_wrong + '</p>' );
						  } )
						  .complete( function () {
							  $btn.removeClass( 'disabled' );
						  } );
				}
			},

			/**
			 * Process the click on an Delete link for verified emails.
			 *
			 * @since 1.5.0
			 *
			 * @param {object} event jQuery event.
			 */
			processEmailDelete: function ( event ) {
				event.preventDefault();

				// Ask the user whether s\he insists.
				if ( !confirm( wp_mail_smtp_pro.ses_text_email_delete ) ) {
					return false;
				}

				var $link = jQuery( event.target ).closest( 'a' );
				var email = $link.data( 'email' );
				var nonce = $link.data( 'nonce' ).toString();

				if ( $link.hasClass( 'disabled' ) ) {
					return false;
				}

				if ( !__private.isEmailValid( email ) ) {
					alert( wp_mail_smtp_pro.ses_text_smth_wrong );
					return false;
				}

				if ( email.length && nonce.length ) {
					// Send ajax request.
					jQuery.ajax( {
							  url: ajaxurl,
							  type: 'POST',
							  dataType: 'json',
							  data: {
								  action: 'wp_mail_smtp_pro_providers_ajax',
								  task: 'email_delete',
								  mailer: 'amazonses',
								  email: email,
								  nonce: nonce,
							  },
							  beforeSend: function () {
								  $link.addClass( 'disabled' );
							  },
						  } )
						  .done( function ( response ) {
							  if ( response.hasOwnProperty( 'success' ) && response.success ) {
								  $link.closest( 'tr' ).fadeOut( 'fast', function () {
									  this.remove();
								  } );
							  }
							  else {
								  alert( response.data );
							  }
						  } )
						  .fail( function () {
							  alert( wp_mail_smtp_pro.ses_text_smth_wrong );
						  } )
						  .complete( function () {
							  $link.removeClass( 'disabled' );
						  } );
				}
			},

			/**
			 * Process the click on an Resend link.
			 *
			 * @since 1.5.0
			 *
			 * @param {object} event jQuery event.
			 */
			processEmailResend: function ( event ) {
				event.preventDefault();

				var $link = jQuery( event.target ).closest( 'a' );
				var email = $link.data( 'email' );
				var nonce = $link.data( 'nonce' ).toString();

				if ( $link.hasClass( 'disabled' ) ) {
					return false;
				}

				if ( !__private.isEmailValid( email ) ) {
					alert( wp_mail_smtp_pro.ses_text_smth_wrong );
					return false;
				}

				if ( email.length && nonce.length ) {
					// Send ajax request.
					jQuery.ajax( {
							  url: ajaxurl,
							  type: 'POST',
							  dataType: 'json',
							  data: {
								  action: 'wp_mail_smtp_pro_providers_ajax',
								  task: 'email_add',
								  mailer: 'amazonses',
								  email: email,
								  nonce: nonce
							  },
							  beforeSend: function () {
								  $link.addClass( 'disabled' );
								  $link.text( wp_mail_smtp_pro.ses_text_sending );
							  }
						  } )
						  .done( function ( response ) {
							  if ( response.hasOwnProperty( 'success' ) && response.success ) {
								  $link
									  .html( '<span class="dashicons dashicons-yes"></span> ' + wp_mail_smtp_pro.ses_text_sent )
									  .fadeOut( 1000, function () {
										  jQuery( this ).text( wp_mail_smtp_pro.ses_text_resend );
										  jQuery( this ).fadeIn( 'fast' );
									  } );
							  }
						  } )
						  .fail( function () {
							  alert( wp_mail_smtp_pro.ses_text_smth_wrong );
						  } )
						  .complete( function () {
							  $link.removeClass( 'disabled' );
						  } );
				}
			},

			/**
			 * Process the click on an Delete link for pending emails.
			 *
			 * @since 1.5.0
			 *
			 * @param {object} event jQuery event.
			 */
			processEmailResendDelete: function ( event ) {
				event.preventDefault();

				// Ask the user whether s\he insists.
				if ( !confirm( wp_mail_smtp_pro.ses_text_email_delete ) ) {
					return false;
				}

				var $link = jQuery( event.target ).closest( 'a' );
				var email = $link.data( 'email' );
				var nonce = $link.data( 'nonce' ).toString();

				if ( $link.hasClass( 'disabled' ) ) {
					return false;
				}

				if ( !__private.isEmailValid( email ) ) {
					alert( wp_mail_smtp_pro.ses_text_smth_wrong );
					return false;
				}

				if ( email.length && nonce.length ) {
					// Send ajax request.
					jQuery.ajax( {
							  url: ajaxurl,
							  type: 'POST',
							  dataType: 'json',
							  data: {
								  action: 'wp_mail_smtp_pro_providers_ajax',
								  task: 'email_resend_delete',
								  mailer: 'amazonses',
								  email: email,
								  nonce: nonce
							  },
							  beforeSend: function () {
								  $link.addClass( 'disabled' );
							  }
						  } )
						  .done( function ( response ) {
							  if ( response.hasOwnProperty( 'success' ) && response.success ) {
								  $link.closest( 'tr' ).fadeOut( 'fast', function () {
									  this.remove();
								  } );
							  }
							  else {
								  alert( response.data );
							  }
						  } )
						  .fail( function () {
							  alert( wp_mail_smtp_pro.ses_text_smth_wrong );
						  } )
						  .complete( function () {
							  $link.removeClass( 'disabled' );
						  } );
				}
			},

			/**
			 * Close the popup to send a verification email.
			 *
			 * @since 1.5.0
			 */
			closeEmailAddPopup: function () {
				jQuery( '#TB_closeWindowButton' ).click();
			}
		}

	};

	// Provide access to public functions/properties.
	return app;
})( document, window, jQuery );

// Initialize.
WPMailSMTP.Admin.Settings.Pro.init();
