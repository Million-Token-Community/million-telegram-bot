<?php
    try {

        $body = General::outJson(array(
            "key" => $mtLiveKey,
            "action" => "top1000",
            "address" => $address
        ));
        $result = General::newHttpRequest("https://milliontoken.live/api", "POST", $body);
        if ($result["status"] === 200 && is_object($result["response"])) {
            $holder = $result["response"];
            if (is_object($holder) && !property_exists($holder, "error")) {
                $response = "<b>Address:</b> ".$holder->address;
                $response .= "\n<b>Rank:</b> ".$holder->rank;
                $response .= "\n<b>Million tokens:</b> ".number_format($holder->quantity);
                $response .= "\n<b>Percentage:</b> ".$holder->percentage."%";
            } else {
                $response = $holder->error;
            }
        } else {
            throw new Exception();
        }

    } catch (Exception $e) {
        $response = "Sorry, something went wrong with /top1000";
    }
?>