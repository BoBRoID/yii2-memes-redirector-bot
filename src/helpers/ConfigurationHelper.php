<?php
/**
 * Created by PhpStorm.
 * User: BoBRoID
 * Date: 27.09.2018
 * Time: 0:06
 */

namespace bobroid\memesRedirectorBot\helpers;


use bobroid\memesRedirectorBot\models\Configuration;
use yii\helpers\ArrayHelper;

class ConfigurationHelper
{

    private static $configuration;

    private static function getConfiguration(): array
    {
        if (self::$configuration === null) {
            self::$configuration = ArrayHelper::map(Configuration::find()->asArray()->all(), 'name', 'value');
        }

        return self::$configuration;
    }

    public static function get(string $varName, $defaultValue = null): ?string
    {
        return self::getConfiguration()[$varName] ?? $defaultValue ?? null;
    }

    public static function set(string $varName, string $value): bool
    {
        if (self::get($varName) === null) {
            $model = new Configuration(['name' => $varName]);
        } else {
            $model = Configuration::findOne(['name' => $varName]);
        }

        $model->value = $value;

        return $model->save();
    }

    public static function getDelay(): int
    {
        return (int)(self::get('delay') ?? 6) * 60;
    }

    public static function getNightDelay(): int
    {
        return (int)(self::get('nightDelay') ?? 12) * 60;
    }

    public static function getLastUpdate(): ?int
    {
        return (int)self::get('lastUpdate');
    }

    public static function setLastUpdate(int $timestamp): bool
    {
        return self::set('lastUpdate', $timestamp);
    }

    public static function getChannelId(): ?int
    {
        return (int)self::get('channelId');
    }

    public static function getChangeableVars(): array
    {
        return ['delay', 'nightDelay', 'channelId'];
    }

    /**
     * @return array
     */
    public static function getAdminsIDs(): array
    {
        $ids = \Yii::$app->params['adminsIDs'] ?? [];

        if (!is_array($ids)) {
            $ids = [$ids];
        }

        return $ids;
    }
}