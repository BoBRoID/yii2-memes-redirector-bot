<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 27.09.2018
 * Time: 1:32
 */

namespace bobroid\memesRedirectorBot\helpers;

use Curl\Curl;

class TelegramHelper
{

    /**
     * @param string $methodName
     * @param array $data
     * @return bool
     */
    public static function sendRequest(string $methodName, array $data): bool
    {
        $botKey = \Yii::$app->params['apiKey'];
        $url = "https://api.telegram.org/bot{$botKey}/{$methodName}";

        try {
            $curl = new Curl();
            $curl->setHeader('Content-Type', 'application/json');
            $res = $curl->post($url, json_encode($data));

            var_dump($res);
        } catch (\ErrorException $e) {
            return false;
        }

        return true;
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function sendMessage(array $data): bool
    {
        return self::sendRequest('sendMessage', $data);
    }
}