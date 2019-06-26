<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 22.06.2017
 * Time: 18:31
 */

namespace bobroid\memesRedirectorBot\commands\actions;

use Longman\TelegramBot\Entities\ServerResponse;

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

        return $this->updateCallbackQuery([
            'chat_id'       =>  $this->update->getCallbackQuery()->getMessage()->getChat()->getId(),
            'text'          =>  \Yii::t('tg-posts-redirector', 'testing...'),
            //'reply_markup'  =>  $this->getReplyMarkup()
        ]);
    }

}