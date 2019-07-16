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
     * @return \stdClass|null
     */
    public static function sendRequest(string $methodName, array $data): ?\stdClass
    {
        $botKey = \Yii::$app->params['apiKey'];
        $url = "https://api.telegram.org/bot{$botKey}/{$methodName}";

        try {
            $curl = new Curl();
            $curl->setHeader('Content-Type', 'application/json');
            return $curl->post($url, json_encode($data));
        } catch (\ErrorException $e) {
            return null;
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function pinChatMessage(array $data): bool
    {
        return self::sendRequest('pinChatMessage', $data) !== null;
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function unpinChatMessage(array $data): bool
    {
        return self::sendRequest('unpinChatMessage', $data) !== null;
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function sendMessage(array $data): bool
    {
        return self::sendRequest('sendMessage', $data) !== null;
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function deleteMessage(array $data): bool
    {
        return self::sendRequest('deleteMessage', $data) !== null;
    }

    /**
     * @param array $data
     * @return bool
     */
    public static function updateMessageMarkup(array $data): bool
    {
        return self::sendRequest('editMessageReplyMarkup', $data) !== null;
    }

    /**
     * @param int $messageId
     * @return int|null
     */
    public static function getMessageViews(int $messageId): ?int
    {
        $link = ConfigurationHelper::get('channelLink');

        if ($link === null) {
            return null;
        }

        $pageContent = file_get_contents("{$link}/{$messageId}?embed=1");

        preg_match('/<span class="tgme_widget_message_views">(\w)?<\/span>/', $pageContent, $matches);

        var_dump($matches);

        return null;
    }
}