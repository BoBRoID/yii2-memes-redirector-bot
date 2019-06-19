<?php
/**
 * This file is part of the TelegramBot package.
 *
 * (c) Avtandil Kikabidze aka LONGMAN <akalongman@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Longman\TelegramBot\Commands\AdminCommands;

use bobroid\memesRedirectorBot\models\Message;
use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class QueuesizeCommand extends BaseAdminCommand
{
    /**
     * @var string
     */
    protected $name = 'queueSize';

    /**
     * @var string
     */
    protected $description = 'Сколько постов в очереди';

    /**
     * @var string
     */
    protected $usage = '/queueSize';

    /**
     * @var string
     */
    protected $version = '1.0';

    /**
     * Command execute method
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute()
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();

        $count = Message::find()->where(['isSent' => 0])->count();

        $data = [
            'chat_id' => $chat_id,
            'text'    => "Memes left: {$count}",
        ];

        return Request::sendMessage($data);
    }
}
