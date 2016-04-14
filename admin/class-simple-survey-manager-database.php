<?php

/**
 * Fired during plugin activation to create databases
 *
 * @link       http://www.davidhewitt.tech/
 * @since      1.0.0
 *
 * @package    Simple_Survey_Manager
 * @subpackage Simple_Survey_Manager/admin
 */

/**
 * Fired during plugin activation to create databases
 *
 * This class defines all code necessary to create database tables during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Simple_Survey_Manager
 * @subpackage Simple_Survey_Manager/includes
 * @author     David Hewitt <davidmhewitt@gmail.com>
 */
class Simple_Survey_Manager_Database {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function create() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();

		$survey_table_name = $wpdb->prefix . "ssm_surveys";
		$question_table_name = $wpdb->prefix . "ssm_questions";
		$answer_table_name = $wpdb->prefix . "ssm_answers";

		if( $wpdb->get_var( "SHOW TABLES LIKE '$survey_table_name'" ) != $survey_table_name ) {
			$sql = "CREATE TABLE $survey_table_name (
				survey_id mediumint(9) NOT NULL AUTO_INCREMENT,
				survey_name TEXT NOT NULL,
				last_activity DATETIME NOT NULL,
				require_log_in INT NOT NULL,
				user TEXT NOT NULL,
				survey_taken INT NOT NULL,
				deleted INT NOT NULL,
				wp_post_id INT NOT NULL,
				PRIMARY KEY  (survey_id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		if( $wpdb->get_var( "SHOW TABLES LIKE '$question_table_name'" ) != $question_table_name ) {
			$sql = "CREATE TABLE $question_table_name (
				question_id mediumint(9) NOT NULL AUTO_INCREMENT,
				survey_id INT NOT NULL,
				question_name TEXT NOT NULL,
				answer_array TEXT NOT NULL,
				question_order INT NOT NULL,
				question_type INT NOT NULL,
				required INT NOT NULL,
				deleted INT NOT NULL,
				PRIMARY KEY  (question_id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}

		if( $wpdb->get_var( "SHOW TABLES LIKE '$answer_table_name'" ) != $answer_table_name ) {
			$sql = "CREATE TABLE $answer_table_name (
				answer_id mediumint(9) NOT NULL AUTO_INCREMENT,
				survey_id INT NOT NULL,
				question_id INT NOT NULL,
				answer TEXT NOT NULL,
				PRIMARY KEY  (answer_id)
			) $charset_collate;";

			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			dbDelta( $sql );
		}
	}
}
