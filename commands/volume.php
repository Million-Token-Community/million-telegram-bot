<?php

    try {

        $result = General::newHttpRequest("https://api.nomics.com/v1/currencies/ticker?key=$nomicsKey&ids=MM4");
        
        if ($result->status === 200 && is_array($result->response)) {
            $data = $result->response[0];
            $volume24h = round($data->{'1d'}->volume);
            $volume24hpct = round($data->{'1d'}->volume_change_pct * 100, 2);

            $plus = ($volume24hpct > 0) ? "+" : "";

            $response = (object) array(
                "type" => "htmltext",
                "payload" => NULL
            );

            $response->payload = "$moneybag 24h volume is <b>$".number_format($volume24h)."</b> ($plus$volume24hpct%)";
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