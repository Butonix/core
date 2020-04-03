<?php

namespace WPMailSMTP\Pro\Emails\Logs\Admin;

use WPMailSMTP\Options;
use WPMailSMTP\Admin\Area;
use WPMailSMTP\Pro\Emails\Logs\EmailsCollection;
use WPMailSMTP\WP;

if ( ! class_exists( 'WP_List_Table', false ) ) {
	require_once ABSPATH . ADMIN_DIR . '/includes/class-wp-list-table.php';
}

/**
 * Class Table that displays the list of email log.
 *
 * @since 1.5.0
 */
class Table extends \WP_List_Table {

	/**
	 * Saved credentials for certain mailers, gmail only for now, to not retrieve them for all rows in a table.
	 *
	 * @since 1.7.1
	 *
	 * @var array
	 */
	private $cached_creds = array();

	/**
	 * Plugin options.
	 *
	 * @since 1.7.1
	 *
	 * @var Options
	 */
	protected $options;

	/**
	 * Set up a constructor that references the parent constructor.
	 * Using the parent reference to set some default configs.
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		$this->options = new Options();

		// Set parent defaults.
		parent::__construct(
			array(
				'singular' => 'email',
				'plural'   => 'emails',
				'ajax'     => false,
			)
		);
	}

	/**
	 * Define the table columns.
	 *
	 * @since 1.5.0
	 *
	 * @return array Associate array of slug=>Name columns data.
	 */
	public function get_columns() {

		$columns = array(
			'cb'        => '<input type="checkbox" />',
			'status'    => '',
			'subject'   => esc_html__( 'Subject', 'wp-mail-smtp-pro' ),
			'from'      => esc_html__( 'From', 'wp-mail-smtp-pro' ),
			'to'        => esc_html__( 'To', 'wp-mail-smtp-pro' ),
			'date_sent' => esc_html__( 'Date Sent', 'wp-mail-smtp-pro' ),
		);

		return $columns;
	}

	/**
	 * Allow users to select multiple emails at once (to perform a bulk action, for example).
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\Pro\Emails\Logs\Email $item Email object.
	 *
	 * @return string Checkbox for bulk selection.
	 */
	protected function column_cb( $item ) {

		return sprintf(
			'<input type="checkbox" name="email_id[]" value="%d" />',
			$item->get_id()
		);
	}

	/**
	 * Display a nice email status: sent or not.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\Pro\Emails\Logs\Email $item Email object.
	 *
	 * @return string Email status as a dot.
	 */
	public function column_status( $item ) {

		if ( $item->get_mailer() !== 'smtp' ) {
			return '';
		}

		return $item->is_sent()
			? '<span title="' . esc_html__( 'Sent', 'wp-mail-smtp-pro' ) . '" class="dot sent"></span>'
			: '<span title="' . esc_html__( 'Not Sent', 'wp-mail-smtp-pro' ) . '" class="dot notsent"></span>';
	}

	/**
	 * Display Email subject.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\Pro\Emails\Logs\Email $item Email object.
	 *
	 * @return string Email subject.
	 */
	public function column_subject( $item ) {

		$subject = '<strong>' .
						'<a href="' . esc_url( $this->get_item_link( $item, 'edit' ) ) . '" class="row-title">' .
							$item->get_subject() .
						'</a>' .
					'</strong>';

		$actions = '<div class="row-actions">' .
						'<span class="view">
							<a href="' . esc_url( $this->get_item_link( $item, 'edit' ) ) . '">' .
								esc_html__( 'View', 'wp-mail-smtp-pro' ) .
							'</a>
						</span> | ' .
						'<span class="delete">
							<a href="' . esc_url( $this->get_item_link( $item, 'delete' ) ) . '">' .
								esc_html__( 'Delete', 'wp-mail-smtp-pro' ) .
							'</a>
						</span>' .
					'</div>';

		return $subject . $actions;
	}

	/**
	 * Get the link to a certain action: "edit" or "delete" for now.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\Pro\Emails\Logs\Email $item Email object.
	 * @param string                            $link
	 *
	 * @return string
	 */
	protected function get_item_link( $item, $link = 'edit' ) {

		$url  = '';
		$link = sanitize_key( $link );

		switch ( $link ) {
			case 'edit':
				$url = add_query_arg(
					array(
						'email_id' => $item->get_id(),
						'mode'     => 'view',
					),
					wp_mail_smtp()->get_admin()->get_admin_page_url( Area::SLUG . '-logs' )
				);
				break;

			case 'delete':
				$url = wp_nonce_url( add_query_arg(
					array(
						'email_id' => $item->get_id(),
						'mode'     => 'delete',
					),
					wp_mail_smtp()->get_admin()->get_admin_page_url( Area::SLUG . '-logs' )
				), 'wp_mail_smtp_pro_logs_log_delete' );
				break;
		}

		return $url;
	}

	/**
	 * Display FROM email address.
	 *
	 * @since 1.5.0
	 * @since 1.7.1 Added special processing for Gmail/Outlook mailers.
	 *
	 * @param \WPMailSMTP\Pro\Emails\Logs\Email $item Email object.
	 *
	 * @return string Email recipient(s).
	 */
	public function column_from( $item ) {

		switch ( $item->get_mailer() ) {
			case 'gmail':
				if ( $this->options->get( 'mail', 'mailer' ) !== 'gmail' ) {
					$from_email = $item->get_people( 'from' );

					break;
				}

				$creds = $this->get_cached_creds( 'gmail' );
				if ( empty( $creds ) ) {
					$creds = wp_mail_smtp()->get_providers()->get_auth( 'gmail' )->get_user_info();

					$this->set_cached_creds( 'gmail', $creds );
				}

				if ( ! empty( $creds['email'] ) ) {
					$from_email = $creds['email'];
				}
				break;

			case 'outlook':
				$creds = $this->options->get( 'outlook', 'user_details' );

				if ( ! empty( $creds['email'] ) ) {
					$from_email = $creds['email'];
				}
				break;

			default:
				$from_email = $item->get_people( 'from' );
		}

		$from_email = $this->generate_email_search_link( $from_email );

		if ( empty( $from_email ) ) {
			$from_email = esc_html__( 'N/A', 'wp-mail-smtp-pro' );
		}

		return $from_email;
	}

	/**
	 * Display TO email addresses.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\Pro\Emails\Logs\Email $item Email object.
	 *
	 * @return string Email recipient(s), comma separated.
	 */
	public function column_to( $item ) {

		$to_emails = $item->get_people( 'to' );

		foreach ( $to_emails as $key => $email ) {
			$to_emails[ $key ] = $this->generate_email_search_link( $email );
		}

		if ( ! empty( $to_emails ) ) {
			$to_emails = implode( ', ', $to_emails );
		} else {
			$to_emails = esc_html__( 'N/A', 'wp-mail-smtp-pro' );
		}

		return $to_emails;
	}

	/**
	 * Display Email date sent.
	 *
	 * @since 1.5.0
	 *
	 * @param \WPMailSMTP\Pro\Emails\Logs\Email $item Email object.
	 *
	 * @return string
	 * @throws \Exception Date manipulation can throw an exception.
	 */
	public function column_date_sent( $item ) {

		$date = null;

		try {
			$date = $item->get_date_sent();
		} catch ( \Exception $e ) {
			// We don't handle this exception as we define a default value above.
		}

		if ( empty( $date ) ) {
			return esc_html__( 'N/A', 'wp-mail-smtp-pro' );
		}

		return esc_html( date_i18n( WP::datetime_format(), strtotime( get_date_from_gmt( $date->format( WP::datetime_mysql_format() ) ) ) ) );
	}

	/**
	 * Define columns that are sortable.
	 *
	 * @since 1.5.0
	 *
	 * @return array List of columns that should be sortable.
	 */
	protected function get_sortable_columns() {

		return array(
			'subject'   => array( 'subject', false ),
			'date_sent' => array( 'date_sent', false ),
		);
	}

	/**
	 * Define a list of available bulk actions.
	 *
	 * @since 1.5.0
	 *
	 * @return array List of actions: slug=>Name.
	 */
	protected function get_bulk_actions() {

		$actions = array(
			'delete' => esc_html__( 'Delete', 'wp-mail-smtp-pro' ),
		);

		return $actions;
	}

	/**
	 * Process the bulk actions.
	 *
	 * @since 1.5.0
	 *
	 * @see $this->prepare_items()
	 */
	public function process_bulk_action() {

		switch ( $this->current_action() ) {
			case 'delete':
				// This case is handled in \WPMailSMTP\Pro\Emails\Logs\Logs::process_email_delete().
				break;
		}
	}

	/**
	 * Get the data, prepare pagination, process bulk actions.
	 * Prepare columns for display.
	 *
	 * @since 1.5.0
	 * @since 1.7.0 Added search support.
	 */
	public function prepare_items() {

		// Define our column headers.
		$this->_column_headers = array( $this->get_columns(), array(), $this->get_sortable_columns() );

		/**
		 * TODO: implement.
		 */
		$this->process_bulk_action();

		/*
		 * Prepare all the params to pass to our Collection.
		 * All sanitization is done in that class.
		 */
		$params = array();

		if ( ! empty( $_REQUEST['search']['place'] ) && ! empty( $_REQUEST['search']['term'] ) ) { // phpcs:ignore
			$params['search']['place'] = sanitize_key( $_REQUEST['search']['place'] ); // phpcs:ignore
			$params['search']['term']  = sanitize_text_field( $_REQUEST['search']['term'] ); // phpcs:ignore
		}

		// Total amount for pagination with WHERE clause - super quick count DB request.
		$total_items = ( new EmailsCollection( $params ) )->get_count();

		if ( ! empty( $_REQUEST['orderby'] ) ) { // phpcs:ignore
			$params['orderby'] = $_REQUEST['orderby']; // phpcs:ignore
		};

		if ( ! empty( $_REQUEST['order'] ) ) { // phpcs:ignore
			$params['order'] = $_REQUEST['order']; // phpcs:ignore
		};

		$params['offset'] = ( $this->get_pagenum() - 1 ) * EmailsCollection::$per_page;

		// Get the data from the DB using parameters defined above.
		$collection  = new EmailsCollection( $params );
		$this->items = $collection->get();

		/*
		 * Register our pagination options & calculations.
		 */
		$this->set_pagination_args(
			array(
				'total_items' => $total_items,
				'per_page'    => EmailsCollection::$per_page,
			)
		);
	}

	/**
	 * Display the search box.
	 *
	 * @since 1.7.0
	 *
	 * @param string $text     The 'submit' button label.
	 * @param string $input_id ID attribute value for the search input field.
	 */
	public function search_box( $text, $input_id ) {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( empty( $_REQUEST['search']['term'] ) && ! $this->has_items() ) {
			return;
		}

		$search_place = ! empty( $_REQUEST['search']['place'] ) ? sanitize_key( $_REQUEST['search']['place'] ) : 'people'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$search_term  = ! empty( $_REQUEST['search']['term'] ) ? wp_unslash( $_REQUEST['search']['term'] ) : ''; // phpcs:ignore WordPress.Security

		if ( ! empty( $_REQUEST['orderby'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<input type="hidden" name="orderby" value="' . esc_attr( $_REQUEST['orderby'] ) . '" />'; // phpcs:ignore WordPress.Security
		}

		if ( ! empty( $_REQUEST['order'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			echo '<input type="hidden" name="order" value="' . esc_attr( $_REQUEST['order'] ) . '" />'; // phpcs:ignore WordPress.Security
		}
		?>

		<p class="search-box">
			<label class="screen-reader-text" for="<?php echo esc_attr( $input_id ); ?>"><?php echo esc_html( $text ); ?>:</label>
			<select name="search[place]">
				<option value="people" <?php selected( 'people', $search_place ); ?>><?php esc_html_e( 'Emails Addresses', 'wp-mail-smtp-pro' ); ?></option>
				<option value="headers" <?php selected( 'headers', $search_place ); ?>><?php esc_html_e( 'Subject & Headers', 'wp-mail-smtp-pro' ); ?></option>
				<option value="content" <?php selected( 'content', $search_place ); ?>><?php esc_html_e( 'Content', 'wp-mail-smtp-pro' ); ?></option>
			</select>
			<input type="search" id="<?php echo esc_attr( $input_id ); ?>" name="search[term]" value="<?php echo esc_attr( $search_term ); ?>" />
			<?php submit_button( $text, '', '', false, array( 'id' => 'search-submit' ) ); ?>
		</p>

		<?php
	}

	/**
	 * Whether the table has items to display or not.
	 *
	 * @since 1.5.0
	 *
	 * @return bool
	 */
	public function has_items() {
		return count( $this->items ) > 0;
	}

	/**
	 * Message to be displayed when there are no items.
	 *
	 * @since 1.5.0
	 * @since 1.7.0 Added a custom message for empty search results.
	 */
	public function no_items() {

		if ( ! empty( $_REQUEST['search']['term'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			esc_html_e( 'No emails found.', 'wp-mail-smtp-pro' );
		} else {
			esc_html_e( 'No emails have been logged for now.', 'wp-mail-smtp-pro' );
		}
	}

	/**
	 * Save the mailer-related credentials data.
	 *
	 * @since 1.7.1
	 *
	 * @param string $mailer Mailer to save "cache" for.
	 * @param array $creds   Creds data to save into "cache".
	 */
	private function set_cached_creds( $mailer, $creds ) {

		$this->cached_creds[ $mailer ] = $creds;
	}

	/**
	 * Get the mailer-related credentials data.
	 *
	 * @since 1.7.1
	 *
	 * @param string $mailer Mailer to get "cache" data for.
	 *
	 * @return bool|array
	 */
	private function get_cached_creds( $mailer ) {

		if ( empty( $this->cached_creds[ $mailer ] ) ) {
			return false;
		}

		return $this->cached_creds[ $mailer ];
	}

	/**
	 * Generate a HTML link for searching/filtering table items by provided email.
	 *
	 * @since {VERSION}
	 *
	 * @param string $email The email address for which to search for.
	 *
	 * @return string A HTML link with the href pointing to the table email search for the provided email.
	 */
	private function generate_email_search_link( $email ) {

		$url = add_query_arg(
			array(
				'search' => array(
					'place' => 'people',
					'term'  => rawurlencode( $email ),
				),
			),
			wp_mail_smtp()->get_admin()->get_admin_page_url( Area::SLUG . '-logs' )
		);

		return '<a href="' . esc_url( $url ) . '">' . esc_html( $email ) . '</a>';
	}
}
