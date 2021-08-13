<?php

    try {

        $body = General::outJson(array(
            "key" => $mtLiveKey,
            "action" => "holders"
        ));

        $result = General::newHttpRequest("https://milliontoken.live/api", "POST", $body);
        if ($result["status"] === 200 && is_object($result["response"])) {

            $holders = $result["response"]->totalHolders;
            $ethHolders = $result["response"]->ethHolders;
            $bscHolders = $result["response"]->bscHolders;
            $changeNum = $result["response"]->change24h;
            $change24h = $result["response"]->change24hpct;

            if ($changeNum > 0) {
                $changeNum = "+".$changeNum;
                $change24h = "+".$change24h;
            }

            $response = (object) array(
                "type" => "htmltext",
                "payload" => NULL
            );

            $response->payload = "$diamond Current holders count is <b>$holders</b> ($ethHolders eth and $bscHolders bsc)\n24h: <b>$changeNum</b> (".$change24h."%)";

            if ($changeNum > 0) {
                $response->payload .= " $chartup";
            } elseif ($changeNum < 0) {
                $response->payload .= " $chartdown";
            }

            array_push($responses, $response);

        } else {
            throw new Exception();
        }
    } catch (Exception $e) {
        $errorMsg = "Sorry, something went wrong with /holders";
    }

?>