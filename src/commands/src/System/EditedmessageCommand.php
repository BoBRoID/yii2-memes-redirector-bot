<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use bobroid\memesRedirectorBot\commands\BaseSystemCommand;
use bobroid\memesRedirectorBot\helpers\MessageHelper;
use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use yii\base\InvalidConfigException;

/**
 * Edited message command
 */
class EditedmessageCommand extends BaseSystemCommand
{
    /**
     * @var string
     */
    protected $name = 'editedmessage';

    /**
     * @var string
     */
    protected $description = 'User edited message';

    /**
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Command execute method
     *
     * @return mixed
     * @throws TelegramException
     * @throws InvalidConfigException
     */
    public function execute()
    {
        $update = $this->getUpdate();
        $message = $update->getEditedMessage();
        $chatId = $this->getChatId();

        if (($dbMessage = Message::findOne(['messageId' => $message->getMessageId()])) === null) {
            return Request::emptyResponse();
        }

        $editedParsedMessage = MessageHelper::parseToDbMessage($message);
        $dbMessage->setAttributes(array_filter($editedParsedMessage->getAttributes(), 'trim'));
        $dbMessage->text = $editedParsedMessage->text;

        if ($dbMessage->save() === false) {
            return Request::sendMessage([
                'chat_id'   =>  $chatId,
                'text'      =>  'Пацан к успеху шёл, но не получилось, не фартонуло :('
            ]);
        }

        return Request::emptyResponse();
    }
}
