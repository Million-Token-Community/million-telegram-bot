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

            $response = "$diamond Current holders count is <b>$holders</b> ($ethHolders eth and $bscHolders bsc)\n24h: <b>$changeNum</b> (".$change24h."%)";

            if ($changeNum > 0) {
                $response .= " $chartup";
            } elseif ($changeNum < 0) {
                $response .= " $chartdown";
            }

        } else {
            throw new Exception();
        }
    } catch (Exception $e) {
        $response = "Sorry, something went wrong with /holders";
    }
?>