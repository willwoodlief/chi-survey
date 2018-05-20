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

	const DB_VERSION = 1.4;
	public static function activate() {
        global $wpdb;

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        $charset_collate = $wpdb->get_charset_collate();

        //do main survey table
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}chi_enquete_survey` (
              id int NOT NULL AUTO_INCREMENT,
              anon_key varchar(10) NOT NULL,
              dob datetime NOT NULL,
              created_at datetime NOT NULL,
              is_completed int NOT NULL default 0,
              sub_fysieke_raw float default null,
              sub_emotioneel_raw float default null,
              sub_energie_raw float default null,
              sub_fysieke_final float default null,
              sub_emotioneel_final float default null,
              sub_energie_final float default null,
              sub_autonomie  float default null,
              sub_binding float default null,
              sub_competentie float default null,
              autonomie float default null,
              competentie float default null,
              sociale_verbondenheid float default null,
              fysieke_vrijheid float default null,
              emotioneel_welbevinden float default null,
              energie float default null,
              comments text default NULL,
              PRIMARY KEY  (id),
              UNIQUE    (anon_key),
              key (dob)
            ) $charset_collate;";


        dbDelta($sql);

        //do questions table

        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}chi_enquete_questions` (
              id int NOT NULL AUTO_INCREMENT,
              short_code varchar(20) not null ,
              section varchar(20) not null,
              question text not null,
              answers text not null,
              PRIMARY KEY  (id),
              UNIQUE    (short_code),
              key (section)
              ) $charset_collate;";

        dbDelta($sql);


        //do answers table
        $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->base_prefix}chi_enquete_answers` (
              id int NOT NULL AUTO_INCREMENT,
              survey_id int not null ,
              question_id int not null ,
              raw_answer int not null,
              mentaal_stap float default null,
              fysiek_stap float default null,
              psych_stap float default null,
              PRIMARY KEY  (id),
              KEY (survey_id),
              KEY  (question_id),
              UNIQUE (survey_id,question_id),
              CONSTRAINT  FOREIGN KEY fk_answer_has_question(question_id) REFERENCES {$wpdb->base_prefix}chi_enquete_questions(id)
                ON  UPDATE CASCADE 
                ON DELETE RESTRICT,
              CONSTRAINT  FOREIGN KEY fk_answer_has_survey(survey_id) REFERENCES {$wpdb->base_prefix}chi_enquete_survey(id)
                ON  UPDATE CASCADE 
                ON DELETE RESTRICT  
              ) $charset_collate;";

        dbDelta($sql);


        //see if the table is empty, if it is then insert questions
        $count_results = $wpdb->get_results(
            "select count(*) as number_rows from {$wpdb->base_prefix}chi_enquete_questions WHERE 1"
        );

        if ($count_results[0]->number_rows == 0) {

            $wpdb->query("INSERT INTO `{$wpdb->base_prefix}chi_enquete_questions` (`id`, `short_code`, `section`, `question`, `answers`) VALUES
        (1, 'uw_gezondheid', 'vitacheck', '1, Wat vindt u, over het algemeen genomen van uw gezondheid', 'Maak een keus (0),uitstekend (1),zeer goed (2),goed (3),matig (4),slecht (5)'),
(2, 'matige_inspanning ', 'vitacheck', '2a, Matige inspanning zoals het verplaatsen van een tafel, stofzuigen, fietsen', 'Maak een keus (0),Ja,ernstig beperkt (1),Ja,een beetje beperkt (2),Nee,helemaal niet beperkt (3)'),
(3, 'trappen_oplopen', 'vitacheck', '2b, Een paar trappen oplopen', 'Maak een keus (0),Ja,ernstig beperkt (1),Ja,een beetje beperkt (2),Nee,helemaal niet beperkt (3)'),
(4, 'minder_bereikt', 'vitacheck', '3a, U heeft (lichamelijke gezondheid) minder bereikt dan u zou willen', 'Maak een keus (0),Ja (1),Nee (2)'),
(5, 'beperkt_werk ', 'vitacheck', '3b, U was (lichamelijke gezondheid) beperkt in het soort werk of het soort bezigheden', 'Maak een keus (0),Ja (1),Nee (2)'),
(6, 'minder_behaald', 'vitacheck', '4a, U heeft (emotioneel) minder bereikt dan u zou willen', 'Maak een keus (0),Ja (1),Nee (2)'),
(7, 'werk_zorgvuldig', 'vitacheck', '4b, U heeft (emotioneel) het werk of andere bezigheden niet zo zorgvuldig gedaan als u gewend bent', 'Maak een keus (0),Ja (1),Nee (2)'),
(8, 'afgelopen_weken', 'vitacheck', '5, In welke mate heeft pijn u de afgelopen vier weken', 'Maak een keus (0),helemaal niet (1),een klein beetje (2),nogal (3),veel (4),heel erg veel (5)'),
(9, 'zenuwachtig', 'vitacheck', '6a, voelde u zich erg zenuwachtig?', 'Maak een keus (0),voortdurend (1),meestal (2),vaak (3),soms (4),zelden (5),nooit (6)'),
(10, 'kon_opvrolijken', 'vitacheck', '6b, zat u zo erg in de put dat niets u kon opvrolijken?', 'Maak een keus (0),voortdurend (1),meestal (2),vaak (3),soms (4),zelden (5),nooit (6)'),
(11, 'zich_kalm_en_rustig', 'vitacheck', '6c, voelde u zich kalm en rustig?', 'Maak een keus (0),voortdurend (1),meestal (2),vaak (3),soms (4),zelden (5),nooit (6)'),
(12, 'energiek_voelen', 'vitacheck', '6d, voelde u zich erg energiek?', 'Maak een keus (0),voortdurend (1),meestal (2),vaak (3),soms (4),zelden (5),nooit (6)'),
(13, 'neerslachtig', 'vitacheck', '6e, voelde u zich neerslachtig en somber?', 'Maak een keus (0),voortdurend (1),meestal (2),vaak (3),soms (4),zelden (5),nooit (6)'),
(14, 'gelukkig', 'vitacheck', '6f, voelde u zich gelukkig?', 'Maak een keus (0),voortdurend (1),meestal (2),vaak (3),soms (4),zelden (5),nooit (6)'),
(15, 'uitgeblust', 'vitacheck', '6g, voelde u zich uitgeblust?', 'Maak een keus (0),voortdurend (1),meestal (2),vaak (3),soms (4),zelden (5),nooit (6)'),
(16, 'levenslustig', 'vitacheck', '6h, voelde u zich levenslustig?', 'Maak een keus (0),voortdurend (1),meestal (2),vaak (3),soms (4),zelden (5),nooit (6)'),
(17, 'zich_moe', 'vitacheck', '6i, voelde u zich moe?', 'Maak een keus (0),voortdurend (1),meestal (2),vaak (3),soms (4),zelden (5),nooit (6)'),
(18, 'activiteiten', 'vitacheck', '7, Lichamelijke gezondheid of emotionele problemen sociale activiteiten geremd', 'Maak een keus (0),voortdurend (1),meestal (2),soms (3),zelden (4),nooit (5)'),
(19, 'keuze', 'psychologische', '1, Ik heb een gevoel van keuze en vrijheid in de dingen die ik onderneem', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(20, 'het_moet', 'psychologische', '2, De meeste dingen die ik doe voelen aan alsof ‘het moet’', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(21, 'mensen_waar_ik_om', 'psychologische', '3, Ik voel dat de mensen waar ik om geef, ook geven om mij', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(22, 'uitgesloten', 'psychologische', '4, Ik voel me uitgesloten uit de groep waar ik bij wil horen', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(23, 'goed_kan_doen', 'psychologische', '5, Ik heb er vertrouwen in dat ik dingen goed kan doen', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(24, 'ernstige_twijfels', 'psychologische', '6, Ik heb ernstige twijfels over de vraag of ik de dingen wel goed kan doen', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(25, 'beslissingen', 'psychologische', '7, Ik voel dat mijn beslissingen weerspiegelen wat ik echt wil', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(26, 'gedwongen', 'psychologische', '8, Ik voel me gedwongen om veel dingen te doen waar ik zelf niet voor zou kiezen', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(27, 'verbonden', 'psychologische', '9, Ik voel me verbonden met mensen die om mij geven en waar ik ook om geef', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(28, 'afstandelijk', 'psychologische', '10, Ik voel dat mensen die belangrijk voor me zijn koud en afstandelijk zijn tegen mij', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(29, 'bekwaam', 'psychologische', '11, Ik voel me bekwaam in wat ik doe', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(30, 'teleurgesteld', 'psychologische', '12, Ik voel me teleurgesteld in veel van mijn prestaties', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(31, 'mijn_keuzes', 'psychologische', '13, Ik voel dat mijn keuzes weergeven wie ik werkelijk ben', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(32, 'verplicht', 'psychologische', '14, Ik voel me verplicht om te veel dingen te doen', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(33, 'nauw_verbonden', 'psychologische', '15, Ik voel me nauw verbonden met andere mensen die belangrijk voor me zijn', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(34, 'indruk_van_haat', 'psychologische', '16, Ik heb de indruk dat mensen waarmee ik tijd doorbreng een hekel aan me hebben', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(35, 'doelen_bereiken', 'psychologische', '17, Ik voel me in staat om mijn doelen te bereiken', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(36, 'onzeker', 'psychologische', '18, Ik voel me onzeker over mijn vaardigheden', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(37, 'interesseert', 'psychologische', '19, Ik voel dat wat ik tot nu toe gedaan heb me oprecht interesseert', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(38, 'verplichtingen', 'psychologische', '20, Mijn dagelijkse activiteiten voelen als een aaneenschakeling van verplichtingen', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(39, 'warm_gevoel', 'psychologische', '21, Ik heb een warm gevoel bij mensen waarmee ik tijd doorbreng', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(40, 'oppervlakkig', 'psychologische', '22, Ik voel dat de relaties die ik heb slechts oppervlakkig zijn', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(41, 'met_succes', 'psychologische', '23, Ik voel dat ik moeilijke taken met succes kan voltooien', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)'),
(42, 'een_mislukking', 'psychologische', '24, Ik voel me als een mislukking omwille van de fouten die ik maak', '1. Helemaal niet waar(1), 2(2), 3(3), 4(4), 5. Helemaal waar(5)');

");

        } //end if empty then populate questions

        $version_to_add = Chi_Enquete_Activator::DB_VERSION;
        add_option( '_chi_enquete_db_version', $version_to_add);
	}

	public static function db_update() {

        global $wpdb;

        $table_name = $wpdb->prefix . 'chi_enquete_answers';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);


        $table_name = $wpdb->prefix . 'chi_enquete_survey';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);

        $table_name = $wpdb->prefix . 'chi_enquete_questions';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);



        Chi_Enquete_Activator::activate();

    }

}
