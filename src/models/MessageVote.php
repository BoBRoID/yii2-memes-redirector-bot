<?php


namespace bobroid\memesRedirectorBot\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class MessageVote
 * @package bobroid\memesRedirectorBot\models
 *
 * @property int    messageId
 * @property int    userId
 * @property string voteType
 * @property int    votedAt
 */
class MessageVote extends ActiveRecord
{

    public const    VOTE_TYPE_INCREASE = '+',
                    VOTE_TYPE_DECREASE = '-';


    public static function tableName(): string
    {
        return 'messagesVotes';
    }


    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class'                 =>  TimestampBehavior::class,
                'createdAtAttribute'    =>  'votedAt',
                'updatedAtAttribute'    =>  null,
                'value'                 =>  time()
            ],
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getMessage(): ActiveQuery
    {
        return $this->hasOne(Message::class, ['messageId' => 'id'])->inverseOf('votes');
    }

}