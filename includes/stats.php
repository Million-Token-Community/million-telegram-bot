<?php

    # description / Keep count of how many times different commands has been run since last deploy

    $stats = new Statistics("stats.json");
    if (isset($command)) {
        $stats->increment($command);
    }

?>