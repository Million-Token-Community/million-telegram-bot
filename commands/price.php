<?php

    try {

        $body = General::outJson(array(
            "key" => $mtLiveKey,
            "action" => "price"
        ));

        $result = General::newHttpRequest("https://milliontoken.live/api", "POST", $body);
        if ($result["status"] === 200 && is_object($result["response"])) {
            $price = $result["response"]->price;
            $price24h = $result["response"]->price24h;
            $plus = ($price24h > 0) ? "+" : "";

            $response = (object) array(
                "type" => "htmltext",
                "payload" => NULL
            );

            $response->payload = $dollar."Price is <b>$$price</b> ($plus$price24h%)";
            if ($price24h > 0) {
                $response->payload .= " $chartup";
            } elseif ($price24h < 0) {
                $response->payload .= " $chartdown";
            }
            array_push($responses, $response);
        } else {
            throw new Exception();
        }

    } catch (Exception $e) {
        $errorMsg = "Sorry, something went wrong with /price";
    }
?>