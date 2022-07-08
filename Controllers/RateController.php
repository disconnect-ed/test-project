<?php

class RateController {

    private $url = 'https://www.nbrb.by/api/exrates/rates/431';

    public function getCurrentRate() {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $this->url);
        $response = curl_exec($ch);
        $data = json_decode($response, true);
        curl_close($ch);
        $currentRate = $data["Cur_OfficialRate"];
        if ($currentRate) {
            return $currentRate;
        }
        return null;
    }

}