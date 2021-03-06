<?php
/**
 * Created by PhpStorm.
 * User: gilko.nikolai
 * Date: 22.06.2017
 * Time: 18:31
 */

namespace bobroid\memesRedirectorBot\commands\actions;

use bobroid\memesRedirectorBot\models\Message;
use bobroid\memesRedirectorBot\models\MessageVote;
use Longman\TelegramBot\Entities\ServerResponse;
use yii\db\StaleObjectException;

class RatePost extends BaseAction
{

    /**
     * @return ServerResponse
     * @throws \Throwable
     * @throws StaleObjectException
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

        $userId = $this->update->getCallbackQuery()->getFrom()->getId();
        $currentUsersVote = $dbMessage->getVotes()->andWhere(['userId' => $userId])->one();

        /**
         * @var MessageVote|null $currentUsersVote
         */

        switch ($this->queryData->act) {
            case MessageVote::VOTE_TYPE_INCREASE:
                if ($currentUsersVote) {
                    if ($currentUsersVote->voteType === MessageVote::VOTE_TYPE_INCREASE) {
                        return $this->answerCallbackQuery();
                    }

                    $currentUsersVote->delete();
                    $dbMessage->dislikesCount--;
                }

                $dbMessage->likesCount++;
                break;
            case MessageVote::VOTE_TYPE_DECREASE:
                if ($currentUsersVote) {
                    if ($currentUsersVote->voteType === MessageVote::VOTE_TYPE_DECREASE) {
                        return $this->answerCallbackQuery();
                    }

                    $currentUsersVote->delete();
                    $dbMessage->likesCount--;
                }

                $dbMessage->dislikesCount++;
                break;
        }

        $usersVote = new MessageVote([
            'userId'    =>  $userId,
            'messageId' =>  $dbMessage->id,
            'voteType'  =>  $this->queryData->act
        ]);

        $dbMessage->save();
        $dbMessage->link('votes', $usersVote);

        $this->answerCallbackQuery([
            'text'  =>  \Yii::t('tg-posts-redirector', 'Вы {action} это', [
                'action'    =>  $usersVote->voteType === MessageVote::VOTE_TYPE_INCREASE ? \Yii::t('tg-posts-redirector', 'лайкнули') : \Yii::t('tg-posts-redirector', 'дизлайкнули')
            ])
        ]);

        return $this->updateCallbackQuery([
            'reply_markup'  =>  $dbMessage->getUsingKeyboard()
        ]);
    }

}