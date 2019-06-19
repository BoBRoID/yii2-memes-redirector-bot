<?php

namespace bobroid\memesRedirectorBot\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property int $created
 * @property int $isSent
 * @property string $text
 * @property string $photoFileId
 * @property string $animationFileId
 * @property string $audioFileId
 * @property int $messageId
 * @property string $videoFileId
 */
class Message extends \yii\db\ActiveRecord
{

    public const    TYPE_TEXT   = 'text',
                    TYPE_PHOTO  = 'photo',
                    TYPE_AUDIO  = 'audio',
                    TYPE_GIF    = 'gif',
                    TYPE_VIDEO  = 'video';

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'messages';
    }

    /**
     * @return array
     */
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class'                 =>  TimestampBehavior::class,
                'createdAtAttribute'    =>  'created',
                'updatedAtAttribute'    =>  null,
                'value'                 =>  time()
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['created', 'isSent', 'messageId'], 'integer'],
            [['text', 'photoFileId', 'animationFileId', 'audioFileId', 'videoFileId'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'created' => 'Created',
            'isSent' => 'Is Sent',
            'text' => 'Text',
            'photoFileId' => 'Photo File ID',
            'animationFileId' => 'Animation File ID',
            'audioFileId' => 'Audio File ID',
            'messageId' => 'Message ID',
        ];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        if (!empty($this->audioFileId)) {
            return self::TYPE_AUDIO;
        }

        if (!empty($this->animationFileId)) {
            return self::TYPE_GIF;
        }

        if (!empty($this->photoFileId)) {
            return self::TYPE_PHOTO;
        }

        if (!empty($this->videoFileId)) {
            return self::TYPE_VIDEO;
        }

        return self::TYPE_TEXT;
    }

    /**
     * @return string
     */
    public function getTelegramMethod(): string
    {
        switch ($this->getType()) {
            case self::TYPE_AUDIO:
                return 'sendAudio';
                break;
            case self::TYPE_GIF:
                return 'sendDocument';
                break;
            case self::TYPE_PHOTO:
                return 'sendPhoto';
                break;
            case self::TYPE_VIDEO:
                return 'sendVideo';
                break;
            case self::TYPE_TEXT:
            default:
                return 'sendMessage';
                break;
        }
    }

    /**
     * @return array
     */
    public function getTelegramData(): array
    {
        $data = [];

        switch ($this->getType()) {
            case self::TYPE_PHOTO:
                $data['photo'] = $this->photoFileId;
                break;
            case self::TYPE_GIF:
                $data['document'] = $this->animationFileId;
                break;
            case self::TYPE_AUDIO:
                $data['audio'] = $this->audioFileId;
                break;
            case self::TYPE_VIDEO:
                $data['video'] = $this->videoFileId;
                break;
            case self::TYPE_TEXT:
                break;
        }

        switch ($this->getType()) {
            case self::TYPE_AUDIO:
            case self::TYPE_GIF:
            case self::TYPE_PHOTO:
            case self::TYPE_VIDEO:
                if (!empty($this->text)) {
                    $data['caption'] = utf8_decode($this->text);
                }
                break;
            case self::TYPE_TEXT:
                $data['text'] = utf8_decode($this->text);
                break;
        }

        return $data;
    }
}
