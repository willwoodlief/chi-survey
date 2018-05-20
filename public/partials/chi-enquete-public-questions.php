<?php
/**
 * @var {ChiSurvey} $survey_obj
 */
global $survey_obj;

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Chi_Enquete
 * @subpackage Chi_Enquete/public/partials
 */
switch ($survey_obj->section_name) {
    case 'vitacheck': {
        $text = get_option('chi_enquete_vitacheck_text');
        $state = 'vitacheck';
        break;
    }
    case 'psychologische': {
        $text = get_option('chi_enquete_psychologische_text');
        $state = 'psychologische';
        break;
    }
    default:{
        $text = 'unknown section [plugin error]';
        break;
    }
}

?>

<div class="chi-enquete-questions">
    <h2> Survey </h2>
    <div class="chi-enquete-customized-header">
        <?= $text ?>
    </div>
    <input type="hidden" id="chi-enquete-survey-code" class="chi-enquete-code" value="<?= $survey_obj->survey_code ?>">
    <input type="hidden" id="chi-enquete-state-holder" class="chi-enquete-state-info" value="<?= $state ?>">
    <div class="chi-enquete-questions-list">
    <?php foreach ($survey_obj->loaded_questions as $question) {?>
        <?php print $survey_obj->generate_question_html($question); ?>
    <?php } ?>
    </div>

</div>
