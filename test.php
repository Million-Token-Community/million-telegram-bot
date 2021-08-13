<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require 'lib/classes.php';
    require 'lib/keys.php';
    require 'lib/emoji.php';

    $botHistory = new BotHistory("botHistory.json");
    $stats = new Statistics("stats.json");

    $path = "https://api.telegram.org/bot$apiToken";
    $message = $_GET["message"];

    $response = NULL;

    if (strpos($message, '/') === 0) {

        if (strpos($message, "/gas") === 0) {
            require "commands/gas.php";
    
        } elseif (strpos($message, "/price") === 0) {
            require "commands/price.php";
    
        } elseif (strpos($message, "/holders") === 0) {
            require "commands/holders.php";
    
        } elseif (strpos($message, "/volume") === 0) {
            require "commands/volume.php";
    
        } elseif (strpos($message, "/top1000") === 0) {
            $address = str_replace("/top1000", "", $_GET["message"]);
            require "commands/top1000.php";
    
        } elseif (strpos($message, "/lambo") === 0 || $message == "when lambo" || $message == "lambo when") {
            require "commands/lambo.php";
    
        } elseif (strpos($message, "/history") === 0) {
            var_dump($botHistory->messages);
    
        } elseif (strpos($message, "/oldmessages") === 0) {
            var_dump($botHistory->getOldMessages());
    
        } elseif (strpos($message, "/deleteoldmessages") === 0) {
            var_dump($botHistory->deleteOldMessages());
    
        } elseif (strpos($message, "/stats") === 0) {
            var_dump($stats->stats);
    
        }

        if (isset($response) && !is_null($response)) {
            var_dump($response);
        }

        if (isset($responsePhoto) && !is_null($responsePhoto)) {
            var_dump($responsePhoto);
        }
    }
?>