<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 18.11.16
 * Time: 15:12
 */

namespace app\components;

use yii\base\Model;

/**
 * Class ApiRecord
 *
 * @property int $id
 * @package app\components
 */
abstract class ApiRecord extends Model
{
    protected $attributes = [];

    protected static $replaceFields = [];

    /**
     * @return mixed
     */
    public function getPrimaryKey()
    {
        return $this->id;
    }

    /**
     * @param string $name
     *
     * @return mixed
     * @throws \yii\base\Exception
     */
    public function __get($name)
    {
        if (is_array($this->attributes) && array_key_exists($name, $this->attributes)) {
            return $this->attributes[$name];
        }

        try {
            $value = parent::__get($name);
        } catch (\Exception $e) {
            return '';
        }

        if ($value instanceof ApiQuery) {
            if ($value->multiple) {
                return $this->$name = $value->all();
            } else {
                return $this->$name = $value->one();
            }
        } else {
            return $value;
        }
    }

    /**
     * @param array $data
     * @param null  $formName
     *
     * @return bool
     */
    public function load($data, $formName = null)
    {
        $this->attributes = $this->attributes ?? [];
        $data             = $data ?? [];
        $this->attributes = array_merge($this->attributes, $data);

        return parent::load($data, $formName);
    }

    /**
     * @param string $name
     * @param mixed  $value
     *
     * @return mixed
     */
    public function __set($name, $value)
    {
        return $this->attributes[$name] = $value;
    }

    /**
     * @param ApiRecord $class
     * @param array     $link
     *
     * @return mixed
     */
    public function hasOne($class, $link)
    {
        $query = $class::find();
        foreach ($link as $key => $item) {
            $query->where[][$key] = $this->$item;
        }
        $query->multiple = false;

        return $query;
    }

    /**
     * @param ApiRecord $class
     * @param array     $link
     *
     * @return mixed
     */
    public function hasMany($class, $link)
    {
        $query = $class::find();
        foreach ($link as $key => $item) {
            $query->where[][$key] = $this->$item;
        }
        $query->multiple = true;

        return $query;
    }

    /**
     * @return ApiQuery
     */
    public function find()
    {
        return \Yii::createObject(ApiQuery::class, [static::class]);
    }

    /**
     * @param array $condition
     *
     * @return array
     */
    public function findAll($condition = [])
    {
        return self::find()->where($condition)->all();
    }

    /**
     * @param $condition
     *
     * @return array|bool
     */
    public function findOne($condition)
    {
        return self::find()->where($condition)->one();
    }

    /**
     * @param array $data
     *
     * @return static
     */
    public static function fabric($data)
    {
        $class = static::class;
        /** @var static $model */
        $model = new $class();
        foreach ($model::$replaceFields as $fieldName => $apiName) {
            if (array_key_exists($apiName, $data)) {
                $data[$fieldName] = $data[$apiName];
                unset($data[$apiName]);
            }
        }

        $model->setAttributes($data);

        return $model;
    }

    /** @inheritdoc */
    public function setAttributes($values, $safeOnly = true)
    {
        $this->attributes = $values;

        return parent::setAttributes($values, $safeOnly);
    }

    /**
     * @param null  $names
     * @param array $except
     *
     * @return array
     */
    public function getAttributes($names = null, $except = [])
    {
        $result = parent::getAttributes($names, $except);
        $data   = array_filter($this->data, function ($key) use ($names, $except) {
            if (count($names)) {
                if (!in_array($key, $names)) {
                    return false;
                }
            }

            if (count($except)) {
                if (in_array($key, $except)) {
                    return false;
                }
            }

            return true;
        }, ARRAY_FILTER_USE_KEY);

        $result = array_merge($result, $data);

        return $result;
    }

    /**
     * @param array $conditions
     *
     * @return int
     */
    public static function count($conditions)
    {
        return self::find()->where($conditions)->count();
    }

    /** @inheritdoc */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        if (count($fields)) {
            return array_filter($this->attributes, function ($key) use ($fields) {
                return in_array($key, $fields);
            }, ARRAY_FILTER_USE_KEY);
        }

        return $this->attributes;
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasAttribute($name)
    {
        return parent::hasProperty($name)
        || (is_array($this->attributes) && array_key_exists($name, $this->attributes));
    }
}
