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
            $response = $dollar."Price is <b>$$price</b> ($price24h%)";
            if ($price24h > 0) {
                $response .= " $chartup";
            } elseif ($price24h < 0) {
                $response .= " $chartdown";
            }
        } else {
            throw new Exception();
        }

    } catch (Exception $e) {
        $response = "Sorry, something went wrong with /price";
    }
?>