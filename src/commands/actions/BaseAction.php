<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 23.03.2017
 * Time: 12:50
 */

namespace bobroid\memesRedirectorBot\commands\actions;

use Longman\TelegramBot\Commands\Command;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Request;
use yii\base\Exception;

abstract class BaseAction
{

    /**
     * @var Command|null
     */

    protected $command = null;

    /**
     * @var null|Update
     */
    protected $update = null;

    /**
     * @var null|\stdClass
     */
    protected $queryData = null;

    /**
     * BaseAction constructor.
     * @param $command Command
     * @param $queryData \stdClass
     * @throws Exception
     */
    public function __construct($command, $queryData = null)
    {
        if(null === $command || $command instanceof Command === false){
            throw new Exception('wrong use of action! $command must be an instance of Longman\TelegramBot\Commands\Command!');
        }

        $this->command = $command;
        $this->update = $command->getUpdate();

        if (null !== $queryData) {
            if($queryData instanceof \stdClass === false){
                $queryData = new \stdClass($queryData);
            }

            if(isset($queryData->data)){
                $this->queryData = $queryData->data;
            }
        }
    }

    /**
     * @return ServerResponse
     */
    public function run(): ServerResponse
    {
        return $this->answerCallbackQuery('do nothing');
    }

    /**
     * @param $data
     * @return ServerResponse
     */
    protected function answerCallbackQuery($data = null): ServerResponse
    {
        $data = $data ?? [];

        if(!is_array($data)){
            $data = ['text' => $data];
        }

        $callback_query    = $this->update->getCallbackQuery();
        $callback_query_id = $callback_query->getId();

        return Request::answerCallbackQuery(array_merge([
            'callback_query_id' => $callback_query_id,
            'cache_time'        => 5,
        ], $data));
    }

    /**
     * @param $data
     * @return ServerResponse
     *
     * @todo: подебажить. Возможно можно отправить несколько Request'ов на edit
     */
    protected function updateCallbackQuery($data = null): ServerResponse
    {
        $data = $data ?? [];

        if(!is_array($data)){
            $data = ['text' => $data];
        }

        $callback_query = $this->update->getCallbackQuery();

        $coordinates = [
            'chat_id'   =>  $callback_query->getMessage()->getChat()->getId(),
            'message_id'=>  $callback_query->getMessage()->getMessageId(),
        ];

        $data = array_merge($data, $coordinates);

        if(array_key_exists('caption', $data)){
            \Yii::debug('editMessageCaption');
            return Request::editMessageCaption($data);
        }

        if(array_key_exists('reply_markup', $data) && !array_key_exists('text', $data)){
            \Yii::debug('editMessageReplyMarkup');
            return Request::editMessageReplyMarkup($data);
        }

        \Yii::debug('editMessageText');
        return Request::editMessageText($data);
    }

}