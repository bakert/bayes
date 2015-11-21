<?php

function main() {
    $VARIABLES = array(
        'priorProbability' => 'What is the prior probability of the hypothesis being true?',
        'chanceOfPositiveIfTrue' => 'What is the probability of the indicator occurring given that the hypothesis is true?',
        'chanceOfPositiveIfFalse' => 'What is the probability of the indicator occurring given that the hypothesis is false?',
    );
    foreach ($VARIABLES as $var => $instructions) {
        if (!isset($_POST[$var])) {
            echo head() . form($var, $instructions) . foot();
            exit;
        }
    }
    echo head() . result() . foot();
}

function form($field, $instructions) {
    $s = '<form method="post">';
    $s .= '<p class="reminder">Enter probabilities as decimals (0.5 = 50% chance).</p>';
    $s .= '<p><b>' . q($instructions) . '</b></p>';
    foreach ($_POST as $key => $value) {
        $s .= '<input type="hidden" name="' . q($key) . '" value="' . q($value) . '">';
    }
    $s .= '<input type="text" size="3" autofocus name="' . q($field) . '">';
    $s .= '<input type="submit" value="Next &raquo;">';
    $s .= '</form>';
    $s .= '<p><a href="' . q($_SERVER['REQUEST_URI']) . '">Start Again</a></p>';
    return $s;
}

function result() {
    $priorProbability = floatval($_POST['priorProbability']);
    $chanceOfPositiveIfTrue = floatval($_POST['chanceOfPositiveIfTrue']);
    $chanceOfPositiveIfFalse = floatval($_POST['chanceOfPositiveIfFalse']);
    $numerator = $chanceOfPositiveIfTrue * $priorProbability;
    $denomenator = $numerator + $chanceOfPositiveIfFalse * (1.0 - $priorProbability);
    return '<p>The revised probability of the hypothesis being true in light of the new evidence is <b>' . ($numerator / $denomenator) . '</b></p>';
}

function q($s) {
    return htmlentities($s, ENT_QUOTES, 'UTF-8');
}

function head() {
    ob_start();
    ?><!DOCTYPE html>
    <html lang="en">
        <head>
            <title>Bayes' Theorem Calculator</title>
            <meta charset="utf-8">
            <style>
            form > * {
                font-size: 110%;
            }
            .intro {
                border-bottom: 1px black solid;
            }
            .reminder {
                color: #999;
                font-style: italic;
            }
            </style>
        </head>
        <body>
            <div class="header box">
                <h1>Bayes' Theorem Calculator</h1>
            </div>
            <div class="intro box">
                <p>Bayes' Theorem lets you calculate how you should change your beliefs in the light of a piece of evidence.</p>
                <p>To use it you need numbers for the following:</p>
                <ul>
                    <li>Your belief that the hypothesis is true before evaluating the piece of evidence.</li>
                    <li>The likelihood of the piece of evidence occuring if the hypothesis is true.</li>
                    <li>The likelihood of the piece of evidence occurring if the hypothesis is false.</li>
                </ul>
            </div>
            <div class="content box">
                <h2>Calculate</h2>
    <?php
    return ob_get_clean();
}

function foot() {
    ob_start();
    ?>
                <p>From <a href="http://bluebones.net/2013/08/bayes-theorem-calculator/">bluebones.net</a></p>
            </div>
        </body>
    </html>
    <?php
    return ob_get_clean();
}

main();

