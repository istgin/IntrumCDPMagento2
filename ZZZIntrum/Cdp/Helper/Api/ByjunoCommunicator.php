<?php
/**
 * Created by Byjuno.
 * User: i.sutugins
 * Date: 14.4.9
 * Time: 16:42
 */
namespace ZZZIntrum\Cdp\Helper\Api;

class ByjunoCommunicator
{
    private $server;

    /**
     * @param mixed $server
     */
    public function setServer($server)
    {
        $this->server = $server;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }

    public function sendRequest($xmlRequest, $timeout = 30)
    {
        $response = "";
        if (intval($timeout) < 0) {
            $timeout = 30;
        }

        $url = 'https://secure.intrum.ch/';
        if ($this->server == 'test') {
            $url .= 'services/creditCheckDACH_01_41_TEST/response.cfm';
        } else {
            $url .= 'services/creditCheckDACH_01_41/response.cfm';
        }

        $request_data = urlencode("REQUEST") . "=" . urlencode($xmlRequest);

        $headers = [
            "Content-type: application/x-www-form-urlencoded",
            "Connection: close"
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request_data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $response = @curl_exec($curl);
        @curl_close($curl);

        $response = trim($response);
        return $response;
    }

}