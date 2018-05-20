<?php
 global $chi_enquete_custom_header;

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Chi_Enquete
 * @subpackage Chi_Enquete/public/partials
 */
  $start_text = get_option('chi_enquete_start_text');
?>

<div class="chi-enquete">
    <div class='chi-enquete-custom-header'> <?= $chi_enquete_custom_header ?></div>
    <div class="chi-enquete-html">
        <div class="chi-enquete-start">
            <div class='chi-enquete-custom-header chi-enquete-start-text'> <?= $start_text ?></div>
            <label for="chi-enquete-dob">Verjaardag</label><input type="date" name="chi-enquete-dob" id="chi-enquete-dob">
            <br>
            <label for="chi-enquete-code">Code</label><input type="text" name="chi-enquete-code" id="chi-enquete-code">
        </div>
    </div>
    <button id='chi-enquete-submit'> Submit </button>

</div>
