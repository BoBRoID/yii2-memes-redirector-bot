<?php


namespace bobroid\memesRedirectorBot\helpers;


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
        return new InlineKeyboardButton([
            'text'          =>  \Yii::t('tg-posts-redirector', '{icon} ({count})', ['icon' => Emoji::thumbsUpSign(), 'count' => $count]),
            'callback_data' =>  json_encode(['action' => 'likePost', 'data' => ['id' => $messageId]])
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
        return new InlineKeyboardButton([
            'text'          =>  \Yii::t('tg-posts-redirector', '{icon} ({count})', ['icon' => Emoji::thumbsDownSign(), 'count' => $count]),
            'callback_data' =>  json_encode(['action' => 'dislikePost', 'data' => ['id' => $messageId]])
        ]);
    }

}