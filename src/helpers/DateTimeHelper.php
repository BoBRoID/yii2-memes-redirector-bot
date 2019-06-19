<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 29.09.2018
 * Time: 1:15
 */

namespace bobroid\memesRedirectorBot\helpers;


class DateTimeHelper
{

    private static $dateTime;

    private static function getDateTime(): \DateTime
    {
        if (self::$dateTime === null) {
            self::$dateTime = new \DateTime();
        }

        return self::$dateTime;
    }

    public static function getCurrentDate(string $format, ?\DateTimeZone $timeZone = null): string
    {
        $dateTime = self::getDateTime();

        if ($timeZone === null) {
            $timeZone = new \DateTimeZone(\Yii::$app->getTimeZone());
        }

        $dateTime->setTimezone($timeZone);

        return $dateTime->format($format);
    }

    public static function nowIsNight(): bool
    {
        $hours = self::getCurrentDate('H');

        return $hours > ConfigurationHelper::get('nightHour', 23) ||
            $hours < ConfigurationHelper::get('morningHour', 8);
    }

}