<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 18.11.16
 * Time: 15:07
 */

namespace app\components;

use yii\db\Connection;
use yii\db\QueryInterface;
use yii\db\QueryTrait;

/**
 * Class ApiQuery
 *
 * @package app\components
 */
class ApiQuery implements QueryInterface
{
    use QueryTrait;
    /**
     * @var string|ApiRecord
     */
    protected $class;

    public $multiple = true;

    /**
     * ApiQuery constructor.
     *
     * @param $className
     */
    public function __construct($className)
    {
        $this->class = $className;
    }

    /**
     * @param null $db
     *
     * @return array
     */
    public function all($db = null)
    {
        $result = Api::request(QueryBuilder::build($this, $this->class));
        $items  = $this->createModels($result);

        return $items;
    }

    /**
     * @param null $db
     *
     * @return mixed
     */
    public function one($db = null)
    {
        $this->limit(1);
        $result = Api::request(QueryBuilder::build($this, $this->class));
        $items  = $this->createModels($result);

        return array_shift($items);
    }

    /**
     * Returns the number of records.
     *
     * @param string     $q  the COUNT expression. Defaults to '*'.
     * @param Connection $db the database connection used to execute the query.
     *                       If this parameter is not given, the `db` application component will be used.
     *
     * @return int number of records.
     */
    public function count($q = '*', $db = null)
    {
        // TODO: Implement count() method.
    }

    /**
     * Returns a value indicating whether the query result contains any row of data.
     *
     * @param Connection $db the database connection used to execute the query.
     *                       If this parameter is not given, the `db` application component will be used.
     *
     * @return bool whether the query result contains any row of data.
     */
    public function exists($db = null)
    {
        $this->limit(1);
        $result = Api::request(QueryBuilder::build($this, $this->class));
        $items  = $this->createModels($result);

        return 0 < count($items);
    }

    /**
     * @param $data
     *
     * @return ApiRecord[]
     */
    protected function createModels($data)
    {
        $models = [];
        foreach ($data as $item) {
            $models[] = ($this->class)::fabric($item);
        }

        return $models;
    }
}
