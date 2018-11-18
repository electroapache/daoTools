<?php
/**
 * Created by PhpStorm.
 * User: daoyankong
 * Date: 2018/11/18
 * Time: 16:26
 */

namespace DaoTools;

class Base
{
    /**
     * curl
     * @param $url
     * @return mixed
     */
    static public function httpGet($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    /**
     * curl
     * @param $url
     * @return mixed
     */
    static public function httpPost($url, $param,$type='')
    {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        if($type=='json'){//json $_POST=json_decode(file_get_contents('php://input'), TRUE);
            $headers = array("Content-type: application/json;charset=UTF-8","Accept: application/json","Cache-Control: no-cache", "Pragma: no-cache");
            $param=json_encode($param);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
        } else {
            $param = http_build_query($param);
        }
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }
}