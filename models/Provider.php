<?php

namespace app\models;

use app\components\ApiRecord;

/**
 * This is the model class for table "provider".
 *
 * @property integer $id
 * @property string  $name
 */
class Provider extends ApiRecord
{
    protected static $replaceFields = [
        'name' => 'db_name',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'provider';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'   => 'ID',
            'name' => 'Name',
        ];
    }
}
