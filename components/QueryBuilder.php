<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 18.11.16
 * Time: 15:40
 */

namespace app\components;

use app\models\Product;
use app\models\Provider;

/**
 * Class QueryBuilder
 *
 * @package app\components
 */
class QueryBuilder
{
    static protected $pathes = [
        Provider::class => 'providers',
        Product::class  => 'items',
    ];

    /**
     * @param $className
     *
     * @return mixed
     */
    protected function preparePath($className)
    {
        return self::$pathes[$className];
    }

    /**
     * @param ApiQuery $query
     * @param string   $className
     *
     * @return string
     */
    public function build($query, $className)
    {
        $queryString = $this->preparePath($className);
        $queryString .= $this->prepareApiParams($query->where);

        return $queryString;
    }

    /**
     * Формирует строку запроса к апи
     *
     * @param array $params
     * @param bool  $isOr
     *
     * @return string
     */
    public function prepareApiParams($params = [], $isOr = false)
    {
        $tmpParam = [];
        if (!is_array($params)) {
            return '';
        }

        foreach ($params as $param) {
            if (is_array($param)) {
                $tmpParam[] = $this->prepareApiParams($param, ('"or"' === ($tmpParam[0] ?? false)));
            } else {
                $tmpParam[] = '"' . $param . '"';
            }
        };

        if ($isOr && false !== strpos($tmpParam[0], '[')) {
            return implode(',', $tmpParam);
        }

        return '[' . implode(',', $tmpParam) . ']';
    }
}
