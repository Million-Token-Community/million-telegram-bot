<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require 'lib/classes.php';
    require 'lib/keys.php';
    require 'lib/emoji.php';

    $botHistory = new BotHistory("botHistory.json");

    $path = "https://api.telegram.org/bot$apiToken";
    $message = $_GET["message"];

    $response = NULL;

    if (strpos($message, '/') === 0) {

        switch ($message) {

            case '/history':
                var_dump($botHistory->messages);
                exit();
                break;

            case '/oldmessages':
                var_dump($botHistory->getOldMessages());
                exit();
                break;

            case '/deleteoldmessages':
                $botHistory->deleteOldMessages();
                exit();
                break;

            case '/gas':
                require "commands/gas.php";
                break;

            case '/price':
                require "commands/price.php";
                break;

            case '/holders':
                require "commands/holders.php";
                break;

            case '/volume':
                require "commands/volume.php";
                break;

            case '/cat':
                require "commands/cat.php";
                break;

            case '/lambo':
                require "commands/lambo.php";
                break;
        }

        if (isset($response) && !is_null($response)) {
            var_dump($response);
        }

        if (isset($responsePhoto) && !is_null($responsePhoto)) {
            var_dump($responsePhoto);
        }
    }
?>