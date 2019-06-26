<?php


namespace bobroid\memesRedirectorBot\helpers;


use bobroid\memesRedirectorBot\models\MessageVote;
use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Exception\TelegramException;
use Spatie\Emoji\Emoji;

class KeyboardHelper
{

    /**
     * @param int $messageId
     * @param int $count
     * @return InlineKeyboardButton
     * @throws TelegramException
     */
    public static function getLikeButton(int $messageId, int $count = 0): InlineKeyboardButton
    {
        $icon = Emoji::thumbsUpSign();

        return new InlineKeyboardButton([
            'text'          =>  $count ? \Yii::t('tg-posts-redirector', '{icon} {count}', ['icon' => $icon, 'count' => $count]) : $icon,
            'callback_data' =>  json_encode(['action' => 'ratePost', 'data' => ['act' => MessageVote::VOTE_TYPE_INCREASE, 'id' => $messageId]])
        ]);
    }

    /**
     * @param int $messageId
     * @param int $count
     * @return InlineKeyboardButton
     * @throws TelegramException
     */
    public static function getDislikeButton(int $messageId, int $count = 0): InlineKeyboardButton
    {
        $icon = Emoji::thumbsDownSign();

        return new InlineKeyboardButton([
            'text'          =>  $count ? \Yii::t('tg-posts-redirector', '{icon} {count}', ['icon' => $icon, 'count' => $count]) : $icon,
            'callback_data' =>  json_encode(['action' => 'ratePost', 'data' => ['act' => MessageVote::VOTE_TYPE_DECREASE, 'id' => $messageId]])
        ]);
    }

}