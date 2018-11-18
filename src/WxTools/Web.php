<?php
/**
 * Created by PhpStorm.
 * User: daoyankong
 * Date: 2018/11/18
 * Time: 16:47
 */

namespace DaoTools\WxTools;

use DaoTools\WxTools\lib\msgCrypt\WXBizMsgCrypt;

class Web extends WXBizMsgCrypt
{
    protected $appid = '';
    protected $appsecret = '';
    protected $mchid = '';
    protected $apikey = '';
    // 验证消息来自微信服务器的token
    protected $token = '';

    public function getAccessToken()
    {
        if (isset($_GET['code'])) {
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
            $param = [
                'appid'=>$this->appid,
                'secret'=>$this->appsecret,
                'code'=>$_GET['code'],
                'grant_type'=>'authorization_code'
            ];
            $res = self::httpGet($url.'?'.http_build_query($param));
            return $res;
        } else {
            $baseUrl = 'https://open.weixin.qq.com/connect/oauth2/authorize';
            $http_host = $_SERVER['HTTP_HOST'];
            $http_host = explode(":",$http_host);
            $param = [
                'appid'=>$this->appid,
                'redirect_uri'=>'http://'.$http_host[0].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'],
                'response_type'=>'code',
                'scope'=>'snsapi_userinfo'
            ];

            header("Location:".$baseUrl.'?'.http_build_query($param));

            exit;
        }
    }

    /**
     * 获取用户信息
     * @param $access_token
     * @param $openid
     * @return mixed
     */
    public function getUinfo($access_token,$openid)
    {
        $url = 'https://api.weixin.qq.com/sns/userinfo';
        $param = [
            'access_token'=>$access_token,
            'openid'=>$openid,
            'lang'=>'zh_CN'
        ];
        $res = self::httpGet($url.'?'.http_build_query($param));
        return $res;
    }

    /**
     * 刷新token
     * @param $refresh_token
     * @return mixed
     */
    public function refreshToken($refresh_token)
    {
        $url = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
        $param = [
            'appid'=>$this->appid,
            'grant_type'=>'refresh_token',
            'refresh_token'=>$refresh_token
        ];

        $res = self::httpGet($url.'?'.http_build_query($param));
        return $res;
    }

    /**
     * 验证消息的确来自微信服务器
     */
    public function checkToken()
    {
        $nonce     = $_GET['nonce'];
        $token     = $this->token;
        $timestamp = $_GET['timestamp'];
        $echostr   = $_GET['echostr'];
        $signature = $_GET['signature'];
        //形成数组，然后按字典序排序
        $array = array();
        $array = array($nonce, $timestamp, $token);
        sort($array);
        //拼接成字符串,sha1加密 ，然后与signature进行校验
        $str = sha1( implode( $array ) );
        if( $str == $signature && $echostr ){
            //第一次接入weixin api接口的时候
            echo  $echostr;
            exit;
        }
    }
}