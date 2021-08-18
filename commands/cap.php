<?php

    try {

        $result = General::newHttpRequest("https://api.coingecko.com/api/v3/coins/million?tickers=false&market_data=true&community_data=false&developer_data=false&sparkline=false");

        if ($result->status === 200 && is_object($result->response)) {
            $mcap = $result->response->market_data->market_cap->usd;

            $response = (object) array(
                "type" => "htmltext",
                "payload" => NULL
            );

            $response->payload = $dollar."Marketcap is <b>$".number_format($mcap)."</b>";

            array_push($responses, $response);
        } else {
            throw new Exception();
        }

    } catch (Exception $e) {
        $errorMsg = "Sorry, something went wrong with /price";
    }
?>