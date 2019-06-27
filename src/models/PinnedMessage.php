<?php


namespace bobroid\memesRedirectorBot\models;


use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Class PinnedMessage
 * @package bobroid\memesRedirectorBot\models
 *
 * @property int        id
 * @property int        messageId
 * @property int        pinnedAt
 * @property int        unpinnedAt
 * @property string     pinnedFrom
 * @property string     pinnedTo
 * @property boolean    isDeleted
 * @property boolean    removeAfterUnpin
 *
 * @property Message    message
 */
class PinnedMessage extends ActiveRecord
{

    /**
     * @return string
     */
    public static function tableName(): string
    {
        return 'pinnedMessages';
    }


    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class'                 =>  TimestampBehavior::class,
                'createdAtAttribute'    =>  'addedAt',
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
        return $this->hasOne(Message::class, ['id' => 'messageId'])->inverseOf('votes');
    }

}