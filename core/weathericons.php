<?php

for ($i=0; $i < 48; $i++) {
    $iconNumber = $i;

    if ($i < 10) {
        $iconNumber = "0" . $i;
    }

    echo '<img src="https://weather.gc.ca/weathericons/' . $iconNumber . '.gif"></img>';
}
