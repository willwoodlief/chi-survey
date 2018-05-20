<?php
class ChiSurveyBackend
{
    /**
     * gets min, max,avg of the important fields see sql statement for the names
     * @return object
     */
    public static function get_stats_array() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'chi_enquete_survey';
        /** @noinspection SqlResolve */
        $res = $wpdb->get_results(
            " 
            select count(id) number_completed,
              min(autonomie) as min_autonomie, max(autonomie) as max_autonomie,avg(autonomie) as avg_autonomie,
              min(competentie) as min_competentie, max(competentie) as max_competentie,avg(competentie) as avg_competentie,
              min(sociale_verbondenheid) as min_sociale_verbondenheid, max(sociale_verbondenheid) as max_sociale_verbondenheid,avg(sociale_verbondenheid) as avg_sociale_verbondenheid,
              min(fysieke_vrijheid) as min_fysieke_vrijheid, max(fysieke_vrijheid) as max_fysieke_vrijheid,avg(fysieke_vrijheid) as avg_fysieke_vrijheid,
              min(autonomie) as min_autonomie, max(autonomie) as max_autonomie,avg(autonomie) as avg_autonomie,
              min(emotioneel_welbevinden) as min_emotioneel_welbevinden, max(emotioneel_welbevinden) as max_emotioneel_welbevinden,avg(emotioneel_welbevinden) as avg_emotioneel_welbevinden,
              min(created_at) as min_created_at, max(created_at) as max_created_at,
              min(energie) as min_energie, max(energie) as max_energie,avg(energie) as avg_energie
            from $table_name where is_completed = 1;
            ");

        return $res[0];

    }

    public static function get_search_results_array($start_index,$limit,$sort_by,
                                                    $sort_direction, $search_column,$search_value) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'chi_enquete_survey';

        $where_clause = '';
        $search_value = trim($search_value);
        if (!empty($search_column) && !empty($search_value)) {
            $escaped_value = sanitize_text_field($search_value);
            switch ($search_column) {
                case 'anon_key':
                    $where_clause .= " AND ($search_column = '$escaped_value' ) ";
            }
        }

        $sort_by_clause = " sort by id asc";
        $sort_by = trim($sort_by);
        $sort_direction = intval($sort_direction);
        if ($sort_by) {
            switch ($sort_by) {
                case 'autonomie':
                case 'competentie':
                case 'sociale_verbondenheid':
                case 'fysieke_vrijheid':
                case 'emotioneel_welbevinden':
                case 'energie':
                case 'created_at':
                case 'anon_key':
                case 'dob': {
                     if ($sort_direction > 0) {
                         $sort_by_clause = " ORDER BY $sort_by ASC";
                     } else {
                         $sort_by_clause = " ORDER BY $sort_by DESC";
                     }
                     break;
                }
                default:
            }
        }
        //todo create sort by clause
        //todo fill in offset,limit
        //add in meta section of start and limit


        "
select id,autonomie,competentie,sociale_verbondenheid,fysieke_vrijheid,
  emotioneel_welbevinden,energie,created_at,anon_key,dob
from wp_chi_enquete_survey where 1 order by energie desc ;";
    }

    public static function get_details_of_one($survey_id) {

    }

}