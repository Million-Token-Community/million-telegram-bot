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
            $response = "$moneybag 24h volume is <b>$".$volume24h."</b> ($volume24hpct%)";
            if ($volume24hpct > 0) {
                $response .= " $chartup";
            } elseif ($volume24hpct < 0) {
                $response .= " $chartdown";
            }
        } else {
            throw new Exception();
        }

    } catch (Exception $e) {
        $response = "Sorry, something went wrong with /volume";
    }
?>