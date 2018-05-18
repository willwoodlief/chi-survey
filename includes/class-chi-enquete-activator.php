<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Chi_Enquete
 * @subpackage Chi_Enquete/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Chi_Enquete
 * @subpackage Chi_Enquete/includes
 * @author     Your Name <email@example.com>
 */
class Chi_Enquete_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */

	const DB_VERSION = 1.1;
	public static function activate() {
        global $wpdb;


        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}chi_enquete` (
              id int NOT NULL AUTO_INCREMENT,
              anon_key varchar(10) NOT NULL,
              created_at datetime NOT NULL,
              comments text default NULL,
              PRIMARY KEY  (id),
              UNIQUE    (anon_key)
            ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);

        $version_to_add = Chi_Enquete_Activator::DB_VERSION;
        add_option( '_chi_enquete_db_version', $version_to_add);
	}

	public static function db_update() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'chi_enquete';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        Chi_Enquete_Activator::activate();

    }

}
