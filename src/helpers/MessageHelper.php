<?php


namespace bobroid\memesRedirectorBot\helpers;

use bobroid\memesRedirectorBot\models\Message;
use Longman\TelegramBot\Entities\PhotoSize;

class MessageHelper
{
    /**
     * @param \Longman\TelegramBot\Entities\Message $message
     * @return Message
     */
    public static function parseToDbMessage(\Longman\TelegramBot\Entities\Message $message): Message
    {
        $dbMessage = new Message();

        if ($message->getMessageId()) {
            $dbMessage->messageId = $message->getMessageId();
        }

        if ($message->getText(true)) {
            $dbMessage->text = utf8_encode($message->getText(true));
        }

        if (empty($dbMessage->text) && !empty($message->getCaption())) {
            $dbMessage->text = utf8_encode($message->getCaption());
        }

        if ($message->getAudio()) {
            $dbMessage->audioFileId = $message->getAudio()->getFileId();
        }

        if (($messagePhotos = $message->getPhoto()) !== null) {
            /**
             * @var $photo PhotoSize|null
             */
            $photo = null;

            foreach ($messagePhotos as $messagePhoto) {
                if ($photo === null || $messagePhoto->getFileSize() > $photo->getFileSize()) {
                    $photo = $messagePhoto;
                }
            }

            $dbMessage->photoFileId = $photo->getFileId();
        }

        if ($gif = $message->getDocument()) {
            if(\in_array($gif->getMimeType(), self::getAllowedGifMimes())){
                $dbMessage->animationFileId = $gif->getFileId();
            }
        } else if ($video = $message->getVideo()) {
            if (\in_array($video->getMimeType(), self::getAllowedVideoMimes())) {
                $dbMessage->videoFileId = $gif->getFileId();
            }
        }

        return $dbMessage;
    }

    /**
     * @return array
     */
    public static function getAllowedGifMimes(): array
    {
        return ['video/mp4', 'image/gif'];
    }

    public static function getAllowedVideoMimes(): array
    {
        return ['video/mp4'];
    }
}