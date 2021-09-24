<?php

    try {

        $result = General::newHttpRequest("https://api.ethplorer.io/getAddressInfo/0x6b4c7a5e3f0b99fcd83e9c089bddd6c7fce5c611?apiKey=freekey");
        if ($result->status === 200 && is_object($result->response)) {

            $data = $result->response;
            $ethHolders = $data->tokenInfo->holdersCount;
            $bscHolders = 0;
            $bscResult = General::newHttpRequest("https://api.covalenthq.com/v1/56/tokens/0xBF05279F9Bf1CE69bBFEd670813b7e431142Afa4/token_holders/?key=$covalentKey&page-size=1");
            if ($bscResult->status === 200 && is_object($bscResult->response)) {
                $bscHolders = $bscResult->response->data->pagination->total_count;
            }

            if ($bscHolders === 0) {
                throw new Exception();
            }
            
            $holders = $ethHolders + $bscHolders;

            $response = (object) array(
                "type" => "htmltext",
                "payload" => NULL
            );

            $response->payload = "$diamond Current holders count is <b>".number_format($holders)."</b> (".number_format($ethHolders)." eth and ".number_format($bscHolders)." bsc)";

            array_push($responses, $response);

        } else {
            throw new Exception();
        }
    } catch (Exception $e) {
        $errorMsg = "Sorry, something went wrong with /holders";
    }

?>