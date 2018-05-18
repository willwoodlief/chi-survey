<?php
global $chi_enquete_custom_header;

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
?>

<div class="chi-enquete">
    <h2> Questions Title </h2>
    <div class='custom-header'> <?= $chi_enquete_custom_header ?></div>

    <button id='submit-results'> Submit Results </button>
</div>
