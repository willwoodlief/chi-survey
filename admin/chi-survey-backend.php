<?php
class ChiSurveyBackend
{
    /**
     * gets min, max,avg of the important fields see sql statement for the names
     * @return object
     * @throws
     */
    public static function get_stats_array() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'chi_enquete_survey';
        /** @noinspection SqlResolve */
        $res = $wpdb->get_results(
            " 
            select count(id) number_completed,
round(min(autonomie),2) as min_autonomie, round(max(autonomie),2) as max_autonomie,round(avg(autonomie),2) as avg_autonomie,
round(min(competentie),2) as min_competentie, round(max(competentie),2) as max_competentie,round(avg(competentie),2) as avg_competentie,
round(min(sociale_verbondenheid),2) as min_sociale_verbondenheid, round(max(sociale_verbondenheid),2) as max_sociale_verbondenheid,round(avg(sociale_verbondenheid),2) as avg_sociale_verbondenheid,
round(min(fysieke_vrijheid),2) as min_fysieke_vrijheid,round( max(fysieke_vrijheid),2) as max_fysieke_vrijheid,round(avg(fysieke_vrijheid),2) as avg_fysieke_vrijheid,
round(min(emotioneel_welbevinden),2) as min_emotioneel_welbevinden,round( max(emotioneel_welbevinden),2) as max_emotioneel_welbevinden,round(avg(emotioneel_welbevinden),2) as avg_emotioneel_welbevinden,
round( min(energie),2) as min_energie, round(max(energie),2) as max_energie, round(avg(energie),2) as avg_energie,
min(UNIX_TIMESTAMP(created_at)) as min_created_at_ts, max(UNIX_TIMESTAMP(created_at)) as max_created_at_ts
            from $table_name where is_completed = 1;
            ");

        if ($wpdb->last_error) {
            throw new Exception($wpdb->last_error );
        }
        return $res[0];

    }

    public static function do_query_from_post() {
        if (array_key_exists( 'start_index',$_POST) ) {
            $start_index = intval($_POST['start_index']);
        } else {
            $start_index = null;
        }

        if (array_key_exists( 'limit',$_POST) ) {
            $limit = intval($_POST['limit']);
        } else {
            $limit = null;
        }

        if (array_key_exists( 'sort_by',$_POST) ) {
            $sort_by = $_POST['sort_by'];
        } else {
            $sort_by = null;
        }

        if (array_key_exists( 'sort_direction',$_POST) ) {
            $sort_direction = intval($_POST['sort_direction']);
        } else {
            $sort_direction = null;
        }

        if (array_key_exists( 'search_column',$_POST) ) {
            $search_column = $_POST['search_column'];
        } else {
            $search_column = null;
        }

        if (array_key_exists( 'search_value',$_POST) ) {
            $search_value = $_POST['search_value'];
        } else {
            $search_value = null;
        }

        return ChiSurveyBackend::get_search_results_array($start_index,$limit,$sort_by,
            $sort_direction,$search_column,$search_value);
    }

    /**
     * @param $start_index
     * @param $limit
     * @param $sort_by
     * @param $sort_direction
     * @param $search_column
     * @param $search_value
     * @return array
     * @throws Exception
     */
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
                    $where_clause .= " AND ($search_column LIKE '%$escaped_value%' ) ";
            }
        }

        $sort_by_clause = " order by id asc";
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
                         $sort_by_clause = " ORDER BY $sort_by ASC ";
                     } else {
                         $sort_by_clause = " ORDER BY $sort_by DESC ";
                     }
                     break;
                }
                default:
            }
        }


        $start_index = intval($start_index);
        $limit = intval($limit);
         if ($start_index > 0 && $limit > 0) {
             $offset_clause = "LIMIT $limit OFFSET $start_index";
         } elseif ($limit > 0) {
             $offset_clause = "LIMIT $limit";
         } elseif ($start_index > 0) {
             $offset_clause = "OFFSET $start_index";
         } else {
             $offset_clause = '';
         }


        //add in meta section of start and limit

        $res = $wpdb->get_results( /** @lang text */
                            "
                select id,autonomie,competentie,sociale_verbondenheid,fysieke_vrijheid,
                  emotioneel_welbevinden,energie,created_at,anon_key,dob, UNIX_TIMESTAMP(dob) as dob_ts,
                  UNIX_TIMESTAMP(created_at) as created_at_ts
                from $table_name where ( is_completed = 1 ) 
                $where_clause $sort_by_clause  $offset_clause;"
        );

        if ($wpdb->last_error) {
            throw new Exception($wpdb->last_error );
        }

         $meta = [
             'start_index'=>$start_index,
             'limit'=>$limit,
             'sort_by'=>$sort_by,
             'sort_direction'=>$sort_direction,
             'search_column'=>$search_column,
             'search_value'=>$search_value
         ];
        return ['meta'=>$meta,'results'=>$res];

    }

    /**
     * @param $survey_id
     * @return array|bool
     * @throws Exception
     */
    public static function get_details_of_one($survey_id) {
        global $wpdb;
        $survey_table_name = $wpdb->prefix . 'chi_enquete_survey';
        $answer_table_name = $wpdb->prefix . 'chi_enquete_answers';
        $question_table_name = $wpdb->prefix . 'chi_enquete_questions';
        $survey_id = intval($survey_id);

        /** @noinspection SqlResolve */
        $survey_res = $wpdb->get_results("
        select id,anon_key,dob,created_at,is_completed,sub_emotioneel_raw,sub_fysieke_raw,sub_energie_raw,
          sub_fysieke_final,sub_emotioneel_final,sub_energie_final,sub_autonomie,sub_binding,sub_competentie,
          autonomie,competentie,sociale_verbondenheid,fysieke_vrijheid,emotioneel_welbevinden,energie
        from $survey_table_name where id = $survey_id;
        ");

        if ($wpdb->last_error) {
            throw new Exception($wpdb->last_error );
        }

        if (empty($survey_res)) {return false;}


        /** @noinspection SqlResolve */
        $answers_res = $wpdb->get_results("
        select q.id,a.raw_answer,a.mentaal_stap,a.fysiek_stap,a.psych_stap, q.question,q.answers from $answer_table_name a
        inner join $question_table_name q ON q.id = a.question_id
        where a.survey_id = $survey_id order by a.id;
        ");

        if ($wpdb->last_error) {
            throw new Exception($wpdb->last_error );
        }

        $answers_array = [];
        foreach ($answers_res as $answer) {
            $node = ChiSurveyBackend::get_question_info($answer);
            $node['mentaal_stap'] = $answer->mentaal_stap;
            $node['fysiek_stap'] = $answer->fysiek_stap;
            $node['psych_stap'] = $answer->psych_stap;
            array_push($answers_array,$node);
        }


        return ['survey' => $survey_res[0], 'answers' => $answers_array];
    }

    protected static function get_question_info($question_obj) {
        $the_question = $question_obj->question;
        preg_match("/\s*(?P<number>^[\w]*,)\s*(?P<question>.*$)/", $the_question, $output_array);

        if (!empty($output_array)) {
            $bare_question = $output_array['question'];
            $question_number = rtrim($output_array['number'],',');
        } else {
            $bare_question = $the_question;
            $question_number = '';
        }
        $answer_string = $question_obj->answers;
        $the_question_id = $question_obj->id;
        //break apart answers by comma into array
        $answer_array_raw = explode(',',$answer_string);
        $answer_hash = [];
        foreach ($answer_array_raw as $raw_answer) {
            //get the number associated with this, it will be (%d) at the end, strip it out and turn it into an integer
            preg_match("/(?P<question>.*)\((?P<value>\d)\)\s*$/", $raw_answer, $output_array);
            if (empty($output_array)) { continue;}
            if (0 ==$output_array['value']) {continue;}
            $answer_hash[intval($output_array['value'])] = $output_array['question'];

        }
        $answer_given = intval($question_obj->raw_answer);
        $answer_phrase = $answer_hash[$answer_given];
        return [
            'question_id'=> $the_question_id,
            'question_number'=> $question_number,
            'question'=> $bare_question,
            'promt'=> $answer_phrase,
            'answer'=> $answer_given
        ];

    }

}