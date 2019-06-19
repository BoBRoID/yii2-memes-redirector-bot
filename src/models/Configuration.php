<?php

namespace bobroid\memesRedirectorBot\models;

use Yii;

/**
 * This is the model class for table "configuration".
 *
 * @property string $name
 * @property string $value
 */
class Configuration extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'configuration';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['value'], 'string'],
            [['name'], 'string', 'max' => 32],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'name' => 'Name',
            'value' => 'Value',
        ];
    }

    /**
     * @return array
     */
    public static function getAdminsIDs(): array
    {
        return \Yii::$app->params['adminsIDs'] ?? [];
    }
}
