<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 22.06.2017
 * Time: 18:31
 */

namespace bobroid\memesRedirectorBot\commands\actions;

use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Entities\ServerResponse;

const   ACTION_INCREASE = '+',
        ACTION_DECREASE = '-';

class RatePost extends BaseAction
{

    /**
     * @return ServerResponse
     */
    public function run(): ServerResponse
    {
        if ($this->queryData) {
            \Yii::debug($this->queryData);
        }

        $postId = $this->queryData->id;

        if (!$dbMessage = Message::findOne(['id' => $postId])) {
            return $this->answerCallbackQuery([
                'chat_id'   =>  $this->update->getCallbackQuery()->getMessage()->getChat()->getId(),
                'text'      =>  \Yii::t('tg-posts-redirector', 'Пост не найден!'),
            ]);
        }

        switch ($this->queryData->act) {
            case ACTION_INCREASE:
                break;
            case ACTION_DECREASE:
                break;
        }

        return $this->answerCallbackQuery([
            'chat_id'       =>  $this->update->getCallbackQuery()->getMessage()->getChat()->getId(),
            'text'          =>  \Yii::t('tg-posts-redirector', 'Понял принял'),
            //'reply_markup'  =>  $this->getReplyMarkup()
        ]);
    }

}