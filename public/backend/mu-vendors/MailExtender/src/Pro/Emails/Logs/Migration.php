<?php

namespace WPMailSMTP\Pro\Emails\Logs;

use WPMailSMTP\WP;

/**
 * Class Migration
 *
 * @since 1.5.0
 */
class Migration {

	/**
	 * Version of the database table(s) for this Logs functionality.
	 *
	 * @since 1.5.0
	 */
	const DB_VERSION = 2;

	/**
	 * Option key where we save the current DB version for Logs functionality.
	 *
	 * @since 1.5.0
	 */
	const OPTION_NAME = 'wp_mail_smtp_logs_db_version';

	/**
	 * @since 1.5.0
	 *
	 * @var int Current version, received from DB wp_options table.
	 */
	protected $cur_ver;

	/**
	 * Migration constructor.
	 *
	 * @since 1.5.0
	 */
	public function __construct() {

		$this->cur_ver = self::get_cur_version();

		$this->validate_db();
	}

	/**
	 * Static on purpose, to get current DB version without __construct() and validation.
	 *
	 * @since 1.5.0
	 *
	 * @return int
	 */
	public static function get_cur_version() {

		return (int) get_option( self::OPTION_NAME, 0 );
	}

	/**
	 * Check DB version and update to the latest one.
	 *
	 * @since 1.5.0
	 */
	protected function validate_db() {

		if ( $this->cur_ver < self::DB_VERSION ) {
			$this->run( self::DB_VERSION );
		}
	}

	/**
	 * Update DB version in options table.
	 *
	 * @since 1.5.0
	 *
	 * @param int $ver Version number.
	 */
	protected function update_db_ver( $ver = 0 ) {

		$ver = (int) $ver;

		if ( empty( $ver ) ) {
			$ver = self::DB_VERSION;
		}

		// Autoload it, because this value is checked all the time
		// and no need to request it separately from all autoloaded options.
		update_option( self::OPTION_NAME, $ver, true );
	}

	/**
	 * Prevent running the same migration twice.
	 * Run migration only when required.
	 *
	 * @since 1.5.0
	 *
	 * @param int $ver
	 */
	protected function maybe_required_older_migrations( $ver ) {

		$ver = (int) $ver;

		if ( ( $ver - $this->cur_ver ) > 1 ) {
			$this->run( $ver - 1 );
		}
	}

	/**
	 * Actual migration launcher.
	 *
	 * @since 1.5.0
	 *
	 * @param int $ver
	 */
	protected function run( $ver ) {

		$ver = (int) $ver;

		if ( method_exists( $this, 'migrate_to_' . $ver ) ) {
			$this->{'migrate_to_' . $ver}();
		} else {

			$message = sprintf( /* translators: %1$s - WP Mail SMTP, %2$s - error message. */
				esc_html__( 'There was an error while upgrading the database. Please contact %1$s support with this information: %2$s.', 'wp-mail-smtp-pro' ),
				'<strong>WP Mail SMTP</strong>',
				'<code>migration from v' . self::get_cur_version() . ' to v' . self::DB_VERSION . ' failed. Plugin version: v' . WPMS_PLUGIN_VER . '</code>'
			);

			WP::add_admin_notice( $message, WP::ADMIN_NOTICE_ERROR );
		}
	}

	/**
	 * Initial migration - create the table structure.
	 *
	 * @since 1.5.0
	 * @since 1.6.0 Changed `date_sent` column type from DATETIME to TIMESTAMP to support MySQL 5.1+ for new clients.
	 */
	private function migrate_to_1() {

		global $wpdb;

		$table = Logs::get_table_name();

		/*
		 * Create the table.
		 */
		$sql = "
		CREATE TABLE `$table` (
		    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
		    `subject` TEXT NOT NULL,
		    `people` TEXT NOT NULL,
		    `headers` TEXT NOT NULL,
		    `content_plain` LONGTEXT NOT NULL,
		    `content_html` LONGTEXT NOT NULL,
		    `status` TINYINT UNSIGNED NOT NULL DEFAULT '0',
		    `date_sent` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
		    `mailer` VARCHAR(255) NOT NULL,
		    `attachments` TINYINT UNSIGNED NOT NULL DEFAULT '0',
		    PRIMARY KEY (id),
		    FULLTEXT INDEX subject (subject),
		    FULLTEXT INDEX people (people),
		    INDEX status (status)
		)
		ENGINE='MyISAM'
		COLLATE='{$wpdb->collate}';";

		$result = $wpdb->query( $sql ); // phpcs:ignore

		// Save the current version to DB.
		if ( $result !== false ) {
			$this->update_db_ver( 1 );
		}
	}

	/**
	 * Change the `date_sent` column type from DATETIME to TIMESTAMP to support MySQL 5.1+.
	 * Applied to older users, who initially created the table with the DATETIME type.
	 *
	 * @since 1.6.0
	 * @since 1.6.1 Included previous DB migration call for new users on 1.6.0.
	 */
	private function migrate_to_2() {

		$this->maybe_required_older_migrations( 2 );

		global $wpdb;

		$table = Logs::get_table_name();

		$sql = "ALTER TABLE `$table` CHANGE COLUMN `date_sent` `date_sent` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP AFTER `status`;";

		$result = $wpdb->query( $sql ); // phpcs:ignore

		// Save the current version to DB.
		if ( $result !== false ) {
			$this->update_db_ver( 2 );
		}
	}

	/**
	 * [For future usage.]
	 */
	private function migrate_to_3() {

		$this->maybe_required_older_migrations( 3 );
	}
}
