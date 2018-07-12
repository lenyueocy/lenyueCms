<?php
class JSSDK
{
    private $appId;
    private $appSecret;

    public function __construct($appId, $appSecret)
    {
        $this->appId = $appId;
        $this->appSecret = $appSecret;
    }

    public function getSignPackage()
    {
        $jsapiTicket = $this->getJsApiTicket();
        // 注意 URL 一定要动态获取，不能 hardcode.
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

        $timestamp = time();
        $nonceStr = $this->createNonceStr();

        // 这里参数的顺序要按照 key 值 ASCII 码升序排序
        $string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";

        $signature = sha1($string);

        $signPackage = array(
            "appId" => $this->appId,
            "nonceStr" => $nonceStr,
            "timestamp" => $timestamp,
            "url" => $url,
            "signature" => $signature,
            "rawString" => $string
        );
        return $signPackage;
    }

    private function createNonceStr($length = 16)
    {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    private function getJsApiTicket()
    {
        // jsapi_ticket 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file("jsapi_ticket.json"));
        if ($data->expires_time < time()) {
            $accessToken = $this->getAccessToken();
            // 如果是企业号用以下 URL 获取 ticket
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/get_jsapi_ticket?access_token=$accessToken";
            $url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
            $res = json_decode($this->httpGet($url));
            $ticket = $res->ticket;
            if ($ticket) {
                $data->expires_time = time() + 7000;
                $data->jsapi_ticket = $ticket;
                $this->set_php_file("jsapi_ticket.json", json_encode($data));
            } else {
                $this->set_php_file("error_jsapi_ticket.json", $res->errcode . '++++++' . $res->errmsg);
            }
        } else {
            $ticket = $data->jsapi_ticket;
        }

        return $ticket;
    }

    private function getAccessToken()
    {
        // access_token 应该全局存储与更新，以下代码以写入到文件中做示例
        $data = json_decode($this->get_php_file("access_token.json"));
        if ($data->expires_time < time()) {
            // 如果是企业号用以下URL获取access_token
            // $url = "https://qyapi.weixin.qq.com/cgi-bin/gettoken?corpid=$this->appId&corpsecret=$this->appSecret";
            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$this->appId&secret=$this->appSecret";
            $res = json_decode($this->httpGet($url));
            $access_token = $res->access_token;
            if ($access_token) {
                $data->expires_time = time() + 7000;
                $data->access_token = $access_token;
                $this->set_php_file("access_token.json", json_encode($data));
            } else {
                $this->set_php_file("error_access_token.json", $res->errcode . '++++++' . $res->errmsg);
            }
        } else {
            $access_token = $data->access_token;
        }
        return $access_token;
    }

    private function httpGet($url)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 500);
        // 为保证第三方服务器与微信服务器之间数据传输的安全性，所有微信接口采用https方式调用，必须使用下面2行代码打开ssl安全校验。
        // 如果在部署过程中代码在此处验证失败，请到 http://curl.haxx.se/ca/cacert.pem 下载新的证书判别文件。
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_URL, $url);

        $res = curl_exec($curl);
        curl_close($curl);

        return $res;
    }

    private function get_php_file($filename)
    {
        if (!is_file(RUNTIME_PATH . $filename)) {
            $data['expires_time'] = 0;
            $data['jsapi_ticket'] = '';
            $this->set_php_file($filename, json_encode($data));
        }
        return file_get_contents(RUNTIME_PATH . $filename);
    }

    private function set_php_file($filename, $content)
    {
        file_put_contents(RUNTIME_PATH . $filename, $content);
    }

    public function pay($url, $obj){
        $obj['nonce_str'] = $this->create_noncestr();
        $stringA = $this->formatQueryMap($obj, false);
        $stringsignTemp = $stringA . "&key=" . PARTNERKEY;
        $sign = strtoupper(md5($stringsignTemp));
        $obj['sign'] = $sign;

        $postXml = $this->arrayToXml($obj);
        $responseXml = $this->curl_post_ssl($url, $postXml);
        return $responseXml;
    }

    public function create_noncestr(){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $length = strlen($chars);
        $str = "";
        for($i = 0; $i < $length; $i++){
            $str.=substr($chars, mt_rand(0, strlen($chars)-1, 1));
        }
        return $str;
    }

    public function formatQueryMap($paraMap, $urlencode){
        $buff = "";
        ksort($paraMap);
        foreach($paraMap as $k => $v){
            if(null != $v && "null" != $v && "sign" != $k){
                if($urlencode){
                    $v = $urlencode($v);
                }
                $buff .= $k . "=" . $v . "&";
            }
        }
        $reqPar = '';
        if(strlen($buff) > 0){
            $reqPar = substr($buff, 0, strlen($buff)-1);
        }
        return $reqPar;
    }

    //数组转XML
    public function arrayToXml($arr){
        $xml = "<xml>";
        foreach($arr as $key => $val){
            if(is_numeric($val)){
                $xml .= "<".$key.">".$val."</".$key.">";
            }else{
                $xml .= "<".$key."><![CDATA[".$val."]]></".$key.">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    function curl_post($url,$vars,$certpath,$keypath,$rootpath) {

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);

        //因为微信红包在使用过程中需要验证服务器和域名，故需要设置下面两行
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // 只信任CA颁布的证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配


        curl_setopt($ch, CURLOPT_SSLCERT,$certpath);
        curl_setopt($ch, CURLOPT_SSLKEY,$keypath);
        curl_setopt($ch, CURLOPT_CAINFO, $rootpath); // CA根证书（用来验证的网站证书是否是CA颁布）


        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $vars);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $res = curl_exec($ch);
        curl_close($ch);
        return $res;
    }

    function curl_post_ssl($url, $vars) {
        $ch = curl_init ();

        $certpath = SITE_PATH . '/Download_cert/apiclient_cert.pem' ;
        $keypath = SITE_PATH . '/Download_cert/apiclient_key.pem' ;
        // 超时时间
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 30 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        // 这里设置代理，如果有的话
        // curl_setopt($ch,CURLOPT_PROXY, '10.206.30.98');
        // curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
        curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );

        // 以下两种方式需选择一种

        // 第一种方法，cert 与 key 分别属于两个.pem文件
        // 默认格式为PEM，可以注释
        curl_setopt ( $ch, CURLOPT_SSLCERTTYPE, 'PEM' );
        curl_setopt ( $ch, CURLOPT_SSLCERT, $certpath);
        // 默认格式为PEM，可以注释
        curl_setopt ( $ch, CURLOPT_SSLKEYTYPE, 'PEM' );
        curl_setopt ( $ch, CURLOPT_SSLKEY, $keypath);

        // 第二种方式，两个文件合成一个.pem文件
        // curl_setopt ( $ch, CURLOPT_SSLCERT, getcwd () . '/all.pem' );

        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $vars );
        $content = curl_exec ( $ch );

        if ($content) {
            $data = new \SimpleXMLElement ( $content );
            foreach ( $data as $key => $value ) {
                $msg [$key] = $value;
                $test = "ok";
            }
        } else {
            $msg ['return_code'] = 'FAIL';
            $msg ['return_msg'] = "请求失败, 失败编号: " . curl_errno ( $ch );
        }
        curl_close ( $ch );
        return $msg;
    }


}