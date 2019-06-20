<?php


namespace bobroid\memesRedirectorBot\helpers;


use Longman\TelegramBot\Entities\InlineKeyboardButton;
use Longman\TelegramBot\Exception\TelegramException;

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
            'text'          =>  \Yii::t('tg-posts-redirector', 'Лайк {icon} ({count})', ['icon' => '', 'count' => $count]),
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
            'text'          =>  \Yii::t('tg-posts-redirector', 'Дизлайк {icon} ({count})', ['icon' => '', 'count' => $count]),
            'callback_data' =>  json_encode(['action' => 'dislikePost', 'data' => ['id' => $messageId]])
        ]);
    }

}