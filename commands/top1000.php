<?php

    try {

        $arr = explode(" ", $message);
        array_shift($arr);
        $address = implode(" ", $arr);

        $body = General::outJson(array(
            "key" => $mtLiveKey,
            "action" => "top1000",
            "address" => $address
        ));

        $result = General::newHttpRequest("https://milliontoken.live/api", "POST", $body);
        if ($result->status === 200 && is_object($result->response)) {

            $holder = $result->response;

            $response = (object) array(
                "type" => "htmltext",
                "payload" => NULL
            );

            if (is_object($holder) && !property_exists($holder, "error")) {
                $response->payload = "<b>Address:</b> ".$holder->address;
                $response->payload .= "\n<b>Rank:</b> ".$holder->rank;
                $response->payload .= "\n<b>Million tokens:</b> ".number_format($holder->quantity);
                $response->payload .= "\n<b>Percentage:</b> ".$holder->percentage."%";
            } else {
                $response->payload = $holder->error;
            }

            array_push($responses, $response);

        } else {
            throw new Exception();
        }

    } catch (Exception $e) {
        $errorMsg = "Sorry, something went wrong with /top1000";
    }

?>