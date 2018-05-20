<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Chi_Enquete
 * @subpackage Chi_Enquete/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Chi_Enquete
 * @subpackage Chi_Enquete/admin
 * @author     Your Name <email@example.com>
 */
class Chi_Enquete_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {



		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chi-enquete-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {


        wp_enqueue_script($this->plugin_name, plugin_dir_url(__DIR__) . 'lib/Chart.min.js', array('jquery'), $this->version, false);
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/chi-enquete-admin.js', array( 'jquery' ), $this->version, false );

	}

    public function my_admin_menu() {
        add_menu_page( 'Survey Results', 'Vitality Survey', 'manage_options', 'chi-enquete/chi-enquete-admin-page.php', array( $this, 'create_admin_interface' ), 'dashicons-chart-line', null  );
    }

    /**
     * Callback function for the admin settings page.
     *
     * @since    1.0.0
     */
    public function create_admin_interface(){

        /** @noinspection PhpIncludeInspection */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/chi-enquete-admin-display.php';

    }

    public function add_settings() {

        // register a new section in the "reading" page
        add_settings_section(
            'chi_enquete_settings_section',
            'Chi Enquete Settings Section',
            function() {
                echo '<p>Chi Enquete  Section Introduction.</p>';
            },
            'reading'
        );


        // register settings  for "reading" page
        $args = array(
            'type' => 'string',
            'sanitize_callback' => null,
            'default' => '',
        );

        $args['default'] = "Vitacheck section, customize me at admin settings reading page";
        //for vitacheck header
        register_setting('reading', 'chi_enquete_vitacheck_text',$args);


        // register vitacheck field in the "chi_enquete_settings_section" section, inside the "reading" page
        add_settings_field(
            'chi_enquete_vitacheck_text',
            'Vitacheck Header HTML',
            function() {
                // get the value of the setting we've registered with register_setting()
                $setting = get_option('chi_enquete_vitacheck_text');
                // output the field
                ?>
                <label>
                    <textarea name="chi_enquete_vitacheck_text" cols="60" rows="10"> <?=$setting ?></textarea>
                </label>
                <?php
            },
            'reading',
            'chi_enquete_settings_section'
        );

        $args['default'] = "Psychologische section, customize me at admin settings reading page";
        //for psychologische header
        register_setting('reading', 'chi_enquete_psychologische_text',$args);


        // register psychologische field in the "chi_enquete_settings_section" section, inside the "reading" page
        add_settings_field(
            'chi_enquete_psychologische_text',
            'Psychologische Header HTML',
            function() {
                // get the value of the setting we've registered with register_setting()
                $setting = get_option('chi_enquete_psychologische_text');
                // output the field
                ?>
                <label>
                    <textarea name="chi_enquete_psychologische_text" cols="60" rows="10"> <?=$setting ?></textarea>
                </label>
                <?php
            },
            'reading',
            'chi_enquete_settings_section'
        );

        $args['default'] = "Intro Text, customize me at admin settings reading page";
        //for start header
        register_setting('reading', 'chi_enquete_start_text',$args);


        // register start field in the "chi_enquete_settings_section" section, inside the "reading" page
        add_settings_field(
            'chi_enquete_start_text',
            'Start Header HTML',
            function() {
                // get the value of the setting we've registered with register_setting()
                $setting = get_option('chi_enquete_start_text');
                // output the field
                ?>
                <label>
                    <textarea name="chi_enquete_start_text" cols="60" rows="10"> <?=$setting ?></textarea>
                </label>
                <?php
            },
            'reading',
            'chi_enquete_settings_section'
        );

        $args['default'] = "Not Found Text, customize me at admin settings reading page";
        //for not found text header
        register_setting('reading', 'chi_enquete_not_found_text',$args);


        // register not found field in the "chi_enquete_settings_section" section, inside the "reading" page
        add_settings_field(
            'chi_enquete_not_found_text',
            'Not Found HTML',
            function() {
                // get the value of the setting we've registered with register_setting()
                $setting = get_option('chi_enquete_not_found_text');
                // output the field
                ?>
                <label>
                    <textarea name="chi_enquete_not_found_text" cols="60" rows="10"> <?=$setting ?></textarea>
                </label>
                <?php
            },
            'reading',
            'chi_enquete_settings_section'
        );


        $args['default'] = "Finished Chart Text, customize me at admin settings reading page";
        //for not found text header
        register_setting('reading', 'chi_enquete_finished_chart',$args);


        // register not found field in the "chi_enquete_settings_section" section, inside the "reading" page
        add_settings_field(
            'chi_enquete_finished_chart',
            'Finished Chart header HTML',
            function() {
                // get the value of the setting we've registered with register_setting()
                $setting = get_option('chi_enquete_finished_chart');
                // output the field
                ?>
                <label>
                    <textarea name="chi_enquete_finished_chart" cols="60" rows="10"> <?=$setting ?></textarea>
                </label>
                <?php
            },
            'reading',
            'chi_enquete_settings_section'
        );
    }





}
