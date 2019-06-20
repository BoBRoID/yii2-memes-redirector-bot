<?php
namespace Longman\TelegramBot\Commands\SystemCommands;

use bobroid\memesRedirectorBot\commands\actions\BaseAction;
use bobroid\memesRedirectorBot\commands\BaseSystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

/**
 * Callback query command
 */
class CallbackqueryCommand extends BaseSystemCommand
{
    /**
     * @var string
     */
    protected $name = 'callbackquery';

    /**
     * @var string
     */
    protected $description = 'Reply to callback query';

    /**
     * @var string
     */
    protected $version = '1.1.0';

    /**
     * Command execute method
     *
     * @return mixed
     */
    public function execute(): ServerResponse
    {
        $update            = $this->getUpdate();
        $callback_query    = $update->getCallbackQuery();
        $callback_query_id = $callback_query->getId();
        $callback_data     = $callback_query->getData();

        if(json_decode($callback_data)){
            $callback_data = json_decode($callback_data);

            return $this->answerCallbackAction($callback_data);
        }

        $data = [
            'callback_query_id' => $callback_query_id,
            'text'              => 'Hello World!',
            'show_alert'        => $callback_data === 'thumb up',
            'cache_time'        => 5,
        ];

        return Request::answerCallbackQuery($data);
    }

    /**
     * @param $callbackData \stdClass
     * @return ServerResponse
     */
    public function answerCallbackAction(\stdClass $callbackData): ServerResponse
    {
        $update            = $this->getUpdate();
        $callback_query    = $update->getCallbackQuery();
        $callback_query_id = $callback_query->getId();

        $calledAction = 'tg\bot\Actions\\'.ucfirst($callbackData->action);

        if(class_exists($calledAction)){
            /**
             * @var $calledAction BaseAction
             */
            $calledAction = new $calledAction($this, $callbackData);

            return $calledAction->run();
        }

        return Request::answerCallbackQuery([
            'callback_query_id' => $callback_query_id,
            'text'              => \Yii::t('tg-posts-redirector', 'Действие не найдено!'),
            'cache_time'        => 5,
        ]);
    }
}
