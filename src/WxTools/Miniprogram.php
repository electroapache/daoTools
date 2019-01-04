<?php
namespace DaoTools\WxTools;

class Miniprogram extends Web 
{
	public function getAccessToken()
	{
		$url = 'https://api.weixin.qq.com/sns/oauth2/access_token';
            $param = [
                'appid'=>$this->appid,
                'secret'=>$this->appsecret,
                'code'=>$_GET['code'],
                'grant_type'=>'authorization_code'
            ];
            $res = self::httpGet($url.'?'.http_build_query($param));
            return $res;
	}
}