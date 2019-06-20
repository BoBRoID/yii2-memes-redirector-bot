<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 22.06.2017
 * Time: 18:31
 */

namespace tg\bot\Actions;

use bobroid\memesRedirectorBot\commands\actions\BaseAction;
use Longman\TelegramBot\Entities\ServerResponse;

class LikePost extends BaseAction
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