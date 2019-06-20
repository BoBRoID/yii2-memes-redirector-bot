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

use bobroid\memesRedirectorBot\helpers\ConfigurationHelper;
use bobroid\memesRedirectorBot\models\Message;
use bobroid\memesRedirectorBot\commands\BaseAdminCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class WhennextCommand extends BaseAdminCommand
{
    /**
     * @var string
     */
    protected $name = 'whenNext';

    /**
     * @var string
     */
    protected $description = 'когда следующий пост упадёт в канал';

    /**
     * @var string
     */
    protected $usage = '/whenNext';

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

        $count = Message::getCountOfNotSent();

        $data = [
            'chat_id' => $chat_id,
        ];

        if ($count !== 0) {
            $lastUpdate = ConfigurationHelper::getLastUpdate();

            if (empty($lastUpdate)) {
                $data['text'] = \Yii::t('tg-posts-redirector', 'Следующий пост будет в течении минуты');
            } else {
                $delay = ConfigurationHelper::getDelay();

                $data['text'] = \Yii::t('tg-posts-redirector', 'Следующий пост будет {time}', [
                    'time'  =>  \Yii::$app->formatter->asRelativeTime($lastUpdate + $delay)
                ]);
            }
        } else {
            $data['text'] = \Yii::t('tg-posts-redirector', 'Постов нет, нужно добавить. Как добавите так и приходите');
        }

        return Request::sendMessage($data);
    }
}
