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

        $b_check = strpos($_SERVER['QUERY_STRING'], 'chi-enquete');
        if ($b_check !== false) {



            wp_enqueue_style( $this->plugin_name.'slick', plugin_dir_url( __DIR__ ) . 'lib/SlickGrid/slick.grid.css', array(), $this->version, 'all' );
         //   wp_enqueue_style( $this->plugin_name.'slickuismooth', plugin_dir_url( __DIR__ ) . 'lib/SlickGrid/css/smoothness/jquery-ui-1.11.3.custom.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name.'slickexamps', plugin_dir_url( __DIR__ ) . 'lib/SlickGrid/css/working.css', array(), $this->version, 'all' );
            wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/chi-enquete-admin.css', array(), $this->version, 'all' );
        }


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {


        $b_check = strpos($_SERVER['QUERY_STRING'], 'chi-enquete');


        if ($b_check !== false) {


          //  wp_enqueue_script($this->plugin_name.'slickcorejqui', plugin_dir_url(__DIR__) . 'lib/SlickGrid/lib/jquery-ui-1.11.3.js', array('jquery'), $this->version, false);

            wp_enqueue_script($this->plugin_name.'slickcoredrag', plugin_dir_url(__DIR__) . 'lib/SlickGrid/lib/jquery.event.drag-2.3.0.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name.'slickcorejsonp', plugin_dir_url(__DIR__) . 'lib/SlickGrid/lib/jquery.jsonp-2.4.min.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name.'slickcore', plugin_dir_url(__DIR__) . 'lib/SlickGrid/slick.core.js', array('jquery'), $this->version, false);
            wp_enqueue_script( $this->plugin_name.'a', plugin_dir_url( __FILE__ ) . 'js/chi-enquete-admin.js', array( 'jquery' ), $this->version, false );
            wp_enqueue_script($this->plugin_name.'slickgrid', plugin_dir_url(__DIR__) . 'lib/SlickGrid/slick.grid.js', array('jquery'), $this->version, false);
            wp_enqueue_script($this->plugin_name.'slicksel', plugin_dir_url(__DIR__) . 'lib/SlickGrid/plugins/slick.rowselectionmodel.js', array('jquery'), $this->version, false);



            wp_enqueue_script($this->plugin_name, plugin_dir_url(__DIR__) . 'lib/Chart.min.js', array('jquery'), $this->version, false);

            $title_nonce = wp_create_nonce('chi_enquete_admin');
            wp_localize_script('chi-enquete', 'chi_enquete_backend_ajax_obj', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'action' => 'chi_enquete_admin',
                'nonce' => $title_nonce,
            ));
        }

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

    public function query_survey_ajax_handler() {
        /** @noinspection PhpIncludeInspection */
        global $chi_enquete_list_survey_obj;
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/chi-survey-backend.php';
        check_ajax_referer('chi_enquete_admin');

        if (array_key_exists( 'method',$_POST) && $_POST['method'] == 'stats') {
            try {
                $stats = ChiSurveyBackend::get_stats_array();
                wp_send_json(['is_valid' => true, 'data' => $stats, 'action' => 'stats']);
                die();
            } catch (Exception $e) {
                wp_send_json(['is_valid' => false, 'message' => $e->getMessage(), 'trace'=>$e->getTrace(), 'action' => 'stats' ]);
                die();
            }

        } elseif (array_key_exists( 'method',$_POST) && $_POST['method'] == 'list') {

            try {

                $chi_enquete_list_survey_obj = ChiSurveyBackend::do_query_from_post();
                wp_send_json(['is_valid' => true, 'data' => $chi_enquete_list_survey_obj, 'action' => 'list']);
                die();
            } catch (Exception $e) {
                wp_send_json(['is_valid' => false, 'message' => $e->getMessage(), 'trace'=>$e->getTrace(), 'action' => 'list' ]);
                die();
            }

        } elseif (array_key_exists( 'method',$_POST) && $_POST['method'] == 'detail') {
                global $chi_enquete_details_object;
            try {
                $chi_enquete_details_object = ChiSurveyBackend::get_details_of_one(intval($_POST['id']));
                ob_start();
                require_once plugin_dir_path(dirname(__FILE__)) . 'admin/partials/chi-enquete-admin-detail.php';
                $html = ob_get_contents();
                ob_end_clean();
                $chi_enquete_details_object['html'] = $html;
                wp_send_json(['is_valid' => true, 'data' => $chi_enquete_details_object, 'action' => 'detail']);
                die();
            } catch (Exception $e) {
                wp_send_json(['is_valid' => false, 'message' => $e->getMessage(), 'trace'=>$e->getTrace(), 'action' => 'detail' ]);
                die();
            }

        } else {
            //unrecognized
            wp_send_json(['is_valid' => false, 'message' => "unknown action"]);
            die();
        }
    }



}
