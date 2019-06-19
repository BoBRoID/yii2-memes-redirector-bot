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

use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use bobroid\memesRedirectorBot\models\Configuration;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class ConfigureCommand extends BaseAdminCommand
{
    /**
     * @var string
     */
    protected $name = 'configure';

    /**
     * @var string
     */
    protected $description = 'Настройки бота';

    /**
     * @var string
     */
    protected $usage = '/configure';

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
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        $chat_id = $message->getChat()->getId();

        $varsList = implode(', ', Configuration::getEditableVars());

        $data = [
            'chat_id' => $chat_id,
            'text'    => "Список доступных переменных для конфигурации: \r\n{$varsList}",
        ];

        return Request::sendMessage($data);
    }
}
