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
 * @property string     pinFrom
 * @property string     pinTo
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

    public static function findAvailable(): ActiveQuery
    {
        $date = date('Y-m-d H:i:s');

        return self::find()->andWhere([
            'and',
            ['or', ['<=', 'pinFrom', $date], ['pinFrom' => null]],
            ['>=', 'pinTo', $date],
            ['isDeleted' => 0],
            ['pinnedAt' => null]
        ]);
    }

    public static function findNotUnpinned(): ActiveQuery
    {
        return self::find()->andWhere([
            'and',
            ['!=', 'pinnedAt', null],
            ['unpinnedAt' => null]
        ]);
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
        return $this->hasOne(Message::class, ['id' => 'messageId'])->inverseOf('pins');
    }

}