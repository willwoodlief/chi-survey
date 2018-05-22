<?php


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
global $chi_enquete_details_object;

$survey_obj = $chi_enquete_details_object['survey'];
$questions = $chi_enquete_details_object['answers'];
?>


<div class="chi-enquete-chart">
    <div class="chartjs-wrapper" style="position: relative;" >
        <canvas id="chartjs-3" class="chartjs" width="undefined" height="undefined"></canvas>
        <script>
            let radar_chart = new Chart(document.getElementById("chartjs-3"),
                {
                    type: "radar",
                    data:
                        {
                            labels: ["Autonomie", "Competentie", "Sociale Verbondenheid", "Fysieke Vrijheid", "Emotioneel Welbevinden", "Energie" ],
                            datasets: [
                                {
                                    label: "<?= $survey_obj->anon_key ?>",
                                    data: [
                                        <?= $survey_obj->autonomie ?>,
                                        <?= $survey_obj->competentie ?>,
                                        <?= $survey_obj->sociale_verbondenheid ?>,
                                        <?= $survey_obj->fysieke_vrijheid ?>,
                                        <?= $survey_obj->emotioneel_welbevinden ?>,
                                        <?= $survey_obj->energie ?>
                                    ],
                                    fill: true,
                                    backgroundColor: "rgba(54, 162, 235, 0.2)",
                                    borderColor: "rgb(54, 162, 235)",
                                    pointBackgroundColor: "rgb(54, 162, 235)",
                                    pointBorderColor: "#fff",
                                    pointHoverBackgroundColor: "#fff",
                                    pointHoverBorderColor: "rgb(54, 162, 235)"
                                }

                            ]
                        },
                    options:
                        {
                            elements:
                                {
                                    line:
                                        {
                                            "tension": 0,
                                            "borderWidth": 3
                                        }
                                },
                            scale: {
                                ticks: {
                                    min: 0
                                }
                            }
                        }
                });
            radar_chart.options.legend.display = false;
        </script>
    </div>


    <table class="chi-enquete-raw-detail">
        <tbody>
            <tr>
                <td>Emotioneel Raw</td>
                <td><?= $survey_obj->sub_emotioneel_raw ?></td>
                <td> </td>

                <td>Emotioneel Final</td>
                <td><?= $survey_obj->sub_emotioneel_final ?></td>
            </tr>
            <tr>
                <td>Energie Raw</td>
                <td><?= $survey_obj->sub_energie_raw ?></td>
                <td> </td>
                <td>Fysieke Final</td>
                <td><?= $survey_obj->sub_fysieke_final ?></td>
            </tr>
            <tr>
                <td>Fysieke Raw</td>
                <td><?= $survey_obj->sub_fysieke_raw ?></td>
                <td> </td>
                <td>Energie Final</td>
                <td><?= $survey_obj->sub_energie_final ?></td>
            </tr>
            <tr>
                <td>Autonomie Raw</td>
                <td><?= $survey_obj->sub_autonomie ?></td>
                <td> </td>
                <td>Binding Raw</td>
                <td><?= $survey_obj->sub_binding ?></td>
            </tr>
            <tr>
                <td>Competentie Raw</td>
                <td><?= $survey_obj->sub_competentie ?></td>
                <td> </td>
                <td>Database ID</td>
                <td><?= $survey_obj->id ?></td>
            </tr>
        </tbody>
    </table>

    <h3>Questions</h3>
    <table class="chi-enquete-answers">
        <tbody>
    <?php
    foreach ($questions as $qob) {
        $question = $qob['question'];
        $question_number = $qob['question_number'];
        $promt = $qob['promt'];
        $answer = strval($qob['answer']);
        if ($answer == $promt) {
            $promt = '';
        }
    ?>
        <tr>
            <td>
                <span class="chi-question-number"> <?=$question_number ?></span>
                <span class="chi-question-text"> <?= $question ?></span>
            </td>
            <td>
                <span class="chi-answer"><?=$answer ?></span>
                <span class="chi-promt"><?= $promt?></span>
            </td>
        </tr>

    <?php } ?>
    </tbody>
    </table>
</div>
