<?php

    try {

        $body = General::outJson(array(
            "key" => $mtLiveKey,
            "action" => "volume"
        ));

        $result = General::newHttpRequest("https://milliontoken.live/api", "POST", $body);
        if ($result["status"] === 200 && is_object($result["response"])) {
            $volume24h = $result["response"]->volume24h;
            $volume24hpct = $result["response"]->volume24hpct;

            $response = (object) array(
                "type" => "htmltext",
                "payload" => NULL
            );

            $response->payload = "$moneybag 24h volume is <b>$".$volume24h."</b> ($volume24hpct%)";
            if ($volume24hpct > 0) {
                $response->payload .= " $chartup";
            } elseif ($volume24hpct < 0) {
                $response->payload .= " $chartdown";
            }

            array_push($responses, $response);

        } else {
            throw new Exception();
        }

    } catch (Exception $e) {
        $errorMsg = "Sorry, something went wrong with /volume";
    }

?>