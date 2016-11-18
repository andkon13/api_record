<?php

namespace app\models;

use app\components\ApiRecord;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $provider_id
 * @property string  $name
 * @property string  $img
 */
class Product extends ApiRecord
{
    protected static $replaceFields = [
        'provider_id' => 'db_id',
    ];

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['provider_id'], 'integer'],
            [['name', 'img'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'          => 'ID',
            'provider_id' => 'Provider ID',
            'name'        => 'Name',
            'img'         => 'Img',
        ];
    }
}
