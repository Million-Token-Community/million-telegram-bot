<?php

    try {

        $result = General::newHttpRequest("https://api.nomics.com/v1/currencies/ticker?key=$nomicsKey&ids=MM4");

        if ($result->status === 200 && is_array($result->response)) {
            $data = $result->response[0];

            $price = round($data->price, 2);
            $price24h = round($data->{'1d'}->price_change_pct * 100, 2);
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