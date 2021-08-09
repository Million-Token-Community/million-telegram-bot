<?php
    try {

        $body = General::outJson(array(
            "x-api-key" => $catApiKey
        ));
        $result = General::newHttpRequest("https://api.thecatapi.com/v1/images/search", "GET", $body);
        if ($result["status"] === 200 && is_array($result["response"])) {
            $url = $result["response"][0]->url;
            $responsePhoto = $url;
        }

    } catch (Exception $e) {
        $response = "Sorry, something went wrong with /cat";
    }
?>