<?php
/**
 * Created by PhpStorm.
 * User: andkon
 * Date: 18.11.16
 * Time: 16:01
 */

namespace app\components;

use yii\httpclient\Client;

/**
 * Class Api
 *
 * @package app\components
 */
class Api
{
    private static $baseUrl = 'http://api.sliza.ru/';

    /**
     * Делает непосредственный запрос к апи
     *
     * @param $query
     *
     * @return bool
     */
    public static function request($query)
    {
        $url  = self::$baseUrl . $query;
        $http = new Client();
        try {
            $response = $http->createRequest()
                ->setUrl($url)
                ->setMethod('get')
                ->setFormat($http::FORMAT_JSON)
                ->setOptions(['timeout' => 1])
                ->send();

            return $response->getIsOk() ? $response->getData() : false;
        } catch (\Exception $e) {
            return false;
        }
    }
}