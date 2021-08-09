<?php
    class General {

        public static function outJson($input, $noArray = FALSE) {
            if (!is_array($input) && !$noArray) {
                $input = array($input);
            }
            return json_encode($input);
        }

        public static function newHttpRequest($url, $type = 'GET', $body = '{}', $contentType = 'application/json', $token = NULL, $timeout = 0) {
            $request = curl_init();

            $headers = array(
                "Content-Type: $contentType"
            );

            if (!is_null($token)) {
                array_push($headers, "Authorization: $token");
            }

            curl_setopt_array($request, array(
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => $timeout,
                CURLOPT_CUSTOMREQUEST => $type,
                CURLOPT_HTTPHEADER => $headers,
            ));

            if ($type === 'POST' || $type === 'PUT') {
                if ($contentType === 'application/json') {
                    if (!General::isJson($body)) {
                        return "Expecting param to be JSON string";
                    }
                    curl_setopt($request, CURLOPT_POSTFIELDS, $body);
                } elseif ($contentType === 'application/x-www-form-urlencoded') {
                    if (!is_array($body)) {
                        return "Expecting param to be array";
                    }
                    curl_setopt($request, CURLOPT_POSTFIELDS, http_build_query($body));
                }
            }

            $response = curl_exec($request);

            $responseStatus = curl_getinfo($request);
            
            curl_close($request);

            $codes = array(
                "400" => "Bad Request",
                "401" => "Unauthorized",
                "403" => "Forbidden",
                "404" => "Not Found",
                "500" => "Internal Server Error",
                "502" => "Bad Gateway",
                "503" => "Service Unavailable",
                "503" => "Gateway Timeout"
            );

            switch ($responseStatus["http_code"]) {
                case 400:
                case 401:
                case 403:
                case 404:
                case 500:
                case 502:
                case 503:
                case 504:
                    return array(
                        "status" => $responseStatus["http_code"],
                        "response" => $codes["".$responseStatus["http_code"]]." - ".$responseStatus["url"]." - $response"
                    );
            }
            
            if (!empty($response)) {
                return array(
                    "status" => $responseStatus["http_code"],
                    "response" => json_decode($response)
                );
            }

            return "No JSON response..";
        }

        private static function isJson($string) {
            return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
         }
    }

    class BotHistory {
        public $fileName;
        public $messages;
        public $minuteAgo;

        function __construct($file) {
            $this->minuteAgo = new DateTime();
            $this->minuteAgo->sub(new DateInterval('PT1M'));
            $this->fileName = $file;
            $this->readMessages();
        }

        public function readMessages() {
            $botHistoryFile = fopen($this->fileName, "c+") or die("Unable to open file!");
            $fileSize = filesize($this->fileName);
            if ($fileSize > 0) {
                $botHistory = json_decode(fread($botHistoryFile, $fileSize));
            } else {
                $botHistory = array();
            }
            fclose($botHistoryFile);
            $this->messages = $botHistory;
        }

        public function saveMessage($chatId, $botMessageId, $command) {
            $date = (new DateTime())->format('Y-m-d H:i:s');
            $msg = array(
                "time" => $date,
                "chatId" => $chatId,
                "botMessageId" => $botMessageId,
                "command" => $command,
            );

            if (!is_array($this->messages)) {
                $this->messages = array($this->messages);
            }

            array_push($this->messages, $msg);

            $this->saveToFile();
        }

        public function getOldMessages($command = null) {
            $oldMsg = array();
            foreach ($this->messages as $msg) {
                $time = DateTime::createFromFormat('Y-m-d H:i:s', $msg->time);
                if ($time < $this->minuteAgo || (!is_null($command) && $msg->command == $command)) {
                    array_push($oldMsg, $msg);
                }
            }
            return $oldMsg;
        }

        public function deleteOldMessages() {
            $deleteLater = array();
            foreach ($this->messages as $msg) {
                $time = DateTime::createFromFormat('Y-m-d H:i:s', $msg->time);
                if ($time >= $this->minuteAgo) {
                    array_push($deleteLater, $msg);
                }
            }
            $this->messages = $deleteLater;
            $this->saveToFile();
        }

        private function saveToFile() {
            file_put_contents($this->fileName, General::outJson($this->messages));
        }
    }
?>