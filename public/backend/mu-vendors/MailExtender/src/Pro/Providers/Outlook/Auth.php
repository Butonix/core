<?php

namespace WPMailSMTP\Pro\Providers\Outlook;

use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use WPMailSMTP\Debug;
use WPMailSMTP\Options as PluginOptions;
use WPMailSMTP\Providers\AuthAbstract;

/**
 * Class Auth
 *
 * @since 1.5.0
 */
class Auth extends AuthAbstract {

	/**
	 * Scopes that we need to send emails.
	 *
	 * @since 1.5.0
	 */
	const SCOPES = array(
		'https://graph.microsoft.com/mail.send',
		'https://graph.microsoft.com/mail.send.shared',
		'https://graph.microsoft.com/user.read',
		'offline_access',
	);

	/**
	 * Auth constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		$options           = new PluginOptions();
		$this->mailer_slug = $options->get( 'mail', 'mailer' );

		if ( $this->mailer_slug !== Options::SLUG ) {
			return;
		}

		$this->options = $options->get_group( $this->mailer_slug );

		$this->get_client();
	}

	/**
	 * Init and get the OAuth2 Client object.
	 *
	 * @since 1.0.0
	 *
	 * @return \League\OAuth2\Client\Provider\GenericProvider
	 * @throws IdentityProviderException Emits exception on requests failure.
	 */
	public function get_client() {

		// Doesn't load client twice + gives ability to overwrite.
		if ( ! empty( $this->client ) ) {
			return $this->client;
		}

		$this->include_vendor_lib();

		$this->client = new GenericProvider(
			array(
				'clientId'                => $this->options['client_id'],
				'clientSecret'            => $this->options['client_secret'],
				'redirectUri'             => self::get_plugin_auth_url(),
				'urlAuthorize'            => 'https://login.microsoftonline.com/common/oauth2/v2.0/authorize',
				'urlAccessToken'          => 'https://login.microsoftonline.com/common/oauth2/v2.0/token',
				'urlResourceOwnerDetails' => 'https://graph.microsoft.com/v1.0/me',
				'scopeSeparator'          => ' ',
			)
		);

		// Do not process if we don't have both App ID & Password.
		if ( ! $this->is_clients_saved() ) {
			return $this->client;
		}

		if ( ! empty( $this->options['access_token'] ) ) {
			$access_token = new AccessToken( (array) $this->options['access_token'] );
		}

		// We don't have tokens but have auth code.
		if (
			$this->is_auth_required() &&
			! empty( $this->options['auth_code'] )
		) {
			// Try to get an access token using the authorization code grant.
			try {
				/** @var AccessToken $creds */
				$access_token = $this->client->getAccessToken(
					'authorization_code', array( 'code' => $this->options['auth_code'] )
				);

				$this->update_access_token( $access_token->jsonSerialize() );
				$this->update_refresh_token( $access_token->getRefreshToken() );
				$this->update_user_details( $access_token );

				// Reset Auth code. It's valid for 5 minutes anyway.
				$this->update_auth_code( '' );

				Debug::clear();
			}
			catch ( IdentityProviderException $e ) {
				$response = $e->getResponseBody();

				Debug::set(
					'Mailer: Outlook' . "\r\n" .
					$response['error'] . "\r\n" .
					$response['error_description']
				);

				// Reset Auth code. It's valid for 5 minutes anyway.
				$this->update_auth_code( '' );
			}
			catch ( \Exception $e ) {
				// Catch any other general exceptions just in case.
				Debug::set(
					'Mailer: Outlook' . "\r\n" .
					$e->getMessage()
				);

				// Reset Auth code. It's valid for 5 minutes anyway.
				$this->update_auth_code( '' );
			}
		} else {
			/*
			 * We have tokens.
			 */

			// Update the old token if needed.
			if ( ! empty( $access_token ) && $access_token->hasExpired() ) {

				try {
					$new_access_token = $this->client->getAccessToken(
						'refresh_token', array( 'refresh_token' => $access_token->getRefreshToken() )
					);

					$this->update_access_token( $new_access_token->jsonSerialize() );
					$this->update_refresh_token( $new_access_token->getRefreshToken() );
					$this->update_user_details( $new_access_token );
				} catch ( IdentityProviderException $e ) {
					$response = $e->getResponseBody();

					Debug::set(
						'Mailer: Outlook' . "\r\n" .
						$response['error'] . "\r\n" .
						$response['error_description']
					);
				} catch ( \Exception $e ) {
					// Catch general any other exception just in case.
					Debug::set(
						'Mailer: Outlook' . "\r\n" .
						$e->getMessage()
					);
				}
			}
		}

		return $this->client;
	}

	/**
	 * Microsoft Apps doesn't support URLs with query params, and should be less than 256 characters.
	 * That's why we use /wp-admin/ page and will redirect user once again after processing MS request.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 */
	public static function get_plugin_auth_url() {
		return admin_url();
	}

	/**
	 * Check and process the auth code for this provider.
	 *
	 * @since 1.5.0
	 *
	 * @param string $code
	 *
	 * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
	 */
	public function process_auth( $code ) {

		$this->update_auth_code( $code );

		// Remove old errors.
		Debug::clear();

		// Retrieve the token and user details, save errors if any.
		$this->get_client();
	}

	/**
	 * Get the auth URL used to proceed to Provider to request access to send emails.
	 *
	 * @since 1.5.0
	 *
	 * @return string
	 * @throws \Exception Emitted when something went wrong.
	 */
	public function get_auth_url() {

		$client = $this->get_client();

		if (
			! empty( $client ) &&
			class_exists( '\League\OAuth2\Client\Provider\GenericProvider', false ) &&
			$client instanceof GenericProvider
		) {
			$url_options = array(
				'state' => wp_create_nonce( $this->state_key ),
				'scope' => self::SCOPES,
			);

			$auth_url = $client->getAuthorizationUrl( $url_options );

			return $auth_url;
		}

		return '#';
	}

	/**
	 * Get and update user-related details (display name and email).
	 *
	 * @since 1.5.0
	 *
	 * @param \League\OAuth2\Client\Token\AccessTokenInterface $access_token
	 *
	 * @throws IdentityProviderException Emits exception when error occurs.
	 */
	protected function update_user_details( $access_token ) {

		if ( empty( $access_token ) ) {
			$access_token = new AccessToken( (array) $this->options->get( $this->mailer_slug, 'access_token' ) );
		}

		// Default values.
		$user = array(
			'display_name' => '',
			'email'        => '',
		);

		try {
			$resource_owner = $this->get_client()->getResourceOwner( $access_token );
			$resource_data  = $resource_owner->toArray();

			$user = array(
				'display_name' => $resource_data['displayName'],
				'email'        => $resource_data['userPrincipalName'],
			);

		} catch ( IdentityProviderException $e ) {
			$response = $e->getResponseBody();

			Debug::set(
				'Mailer: Outlook (requesting user details)' . "\r\n" .
				$response['error'] . "\r\n" .
				$response['error_description']
			);

			// Reset Auth code. It's valid for 5 minutes anyway.
			$this->update_auth_code( '' );
		} catch ( \Exception $e ) {
			// Catch general any other exception just in case.
			Debug::set(
				'Mailer: Outlook (requesting user details)' . "\r\n" .
				$e->getMessage()
			);
		}

		$options = new PluginOptions();
		$all     = $options->get_all();

		// To save in DB.
		$all[ $this->mailer_slug ]['user_details'] = $user;

		// To save in currently retrieved options array.
		$this->options['user_details'] = $user;

		$options->set( $all );
	}
}
