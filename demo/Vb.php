<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2019/7/16
 * Time: 10:09 AM
 */
class Vb
{

    private static $regin = 'https://api.vbt.cc';
    protected static $key = '';
    private static $secret = '';

    public function __construct($key = '', $secret = '')
    {

        if ($key)
            self::$key = $key;

        if($secret)
            self::$secret = $secret;
    }

    /** 一个url请求的方法
     * @param string $url
     * @param int $is_post
     * @param array $data
     * @return mixed
     */
    private function request($url = '', $is_post = 1, $data = [])
    {

        //将请求的参数格式化
        $curlPost = '';
        foreach ( $data as $key => $value ) {
            $curlPost = empty($curlPost) ? $key . '=' . urlencode($value) : $curlPost . '&' . $key . '=' . urlencode($value);
        }


        //初始化curl
        $ch = curl_init();
        //解决乱码问题，请求头
        $this_header = [
            "content-type:application/x-www-form-urlencoded;charset=utf8;"
        ];
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this_header);

        //抓取指定网页
        curl_setopt($ch, CURLOPT_URL, $url);

        //设置header
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //post提交方式
        curl_setopt($ch, CURLOPT_POST, $is_post);


        //提交需要的参数
        if($curlPost) curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        //运行curl

        $ret = curl_exec($ch);
        //关闭curl
        curl_close($ch);



        return $ret;
    }

    /**
     * 获取签名
     * @param array $param
     * @return string
     */
    private function sign(array $param){
        //按照键值升序排序
        ksort($param);

        //拼接参数
        $string = '';
        foreach ($param as $key => $value)
            $string = ($string)? $string."&{$key}={$value}": "{$key}={$value}";

        //sha1加密
        return sha1($string);

    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////===============get 请求=============/////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////


    /**
     * 获取当前最新行情
     * @param int $symbol 货币编号，详情参照 区块链资产对照表
     * @return array
     */
    public function ticker(array $parms){
        $path = '/api/v1/ticker';
        $url = self::$regin.$path;
        $ret = self::request($url, 0, $parms);

        return json_decode($ret,true);
    }
    /**
     * 获取当前所有最新行情
     * @return array
     */
    public function tickers(){
        $path = '/api/v1/tickers';
        $url = self::$regin.$path;
        $ret = self::request($url, 0, []);

        return json_decode($ret, true);
    }

    /**市场深度
     * @param int $symbol 货币编号
     * @param int $merge 精确到小数点后几位，默认为0(0,1,2,3,4)
     * @return array|mixed
     */
    public function depth(array $parms){
        $path = '/api/v1/depth';
        $url = self::$regin.$path;
        $ret = self::request($url, 0, $parms);

        return json_decode($ret, true);
    }

    /** 最近的市场交易
     * @param int $symbol 货币编号
     * @param int $size 返回几条，默认为100，范围 0-100
     * @return array|mixed
     */
    public function orders(array $parms){
        $path = '/api/v1/orders';
        $url = self::$regin.$path;
        $ret = self::request($url, 0, $parms);

        return json_decode($ret, true);
    }

    /**
     * 账户信息
     * @return array|mixed
     */
    public function balances(){
        $path = '/api/v1/balances';
        $url = self::$regin.$path;
        $parms['key'] = self::$key;
        $parms['secret'] = self::$secret;
        $parms['sign'] = self::sign($parms);
        $ret = self::request($url, 0, $parms);

        return json_decode($ret, true);
    }

    /**
     * 充值地址
     * @param int $symbol 货币编号
     * @return array|mixed
     */
    public function coin_address(array $parms){
        $path = '/api/v1/coin_address';
        $url = self::$regin.$path;
        $parms['key'] = self::$key;
        $parms['secret'] = self::$secret;
        $parms['sign'] = self::sign($parms);
        $ret = self::request($url, 0, $parms);

        return json_decode($ret, true);
    }

    /**
     * 挂单查询
     * @param int $symbol 货币编号
     * @param int $since 开始时间，为时间戳，精确到秒
     * @param int $type 挂单类型[1: 正在挂单, 0: 所有挂单]
     * @return array|mixed
     */
    public function trade_list(array $parms){
        $path = '/api/v1/trade_list';
        $url = self::$regin.$path;
        $parms['key'] = self::$key;
        $parms['secret'] = self::$secret;
        $parms['sign'] = self::sign($parms);
        $ret = self::request($url, 0, $parms);

        return json_decode($ret, true);
    }

    /**
     * 查询订单信息
     * @param int $id 订单id
     * @return array|mixed
     */
    public function trade_view(array $parms){
        $path = '/api/v1/trade_view';
        $url = self::$regin.$path;
        $parms['key'] = self::$key;
        $parms['secret'] = self::$secret;
        $parms['sign'] = self::sign($parms);
        $ret = self::request($url, 0, $parms);

        return json_decode($ret, true);
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////////
    ///////////////////////////////////===============post 请求=============////////////////////////////////
    ////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * 挂单
     * @param int $symbol 货币编号
     * @param float $amount 数量
     * @param float $price 价格
     * @param string $type 挂单类型 传入参数为 buy|sell
     * @return mixed
     */
    public function trade($parms){
        $path = '/api/v1/trade';
        $url = self::$regin.$path;
        $parms['key'] = self::$key;
        $parms['secret'] = self::$secret;
        $parms['sign'] = self::sign($parms);
        $ret = self::request($url, 1, $parms);

        return json_decode($ret, true);
    }

    /**
     * 取消订单
     * @param int $id 订单id
     * @return array|mixed
     */
    public function cancel_trade(array $parms){
        $path = '/api/v1/cancel_trade';
        $url = self::$regin.$path;
        $parms['key'] = self::$key;
        $parms['secret'] = self::$secret;
        $parms['sign'] = self::sign($parms);
        $ret = self::request($url, 1, $parms);

        return json_decode($ret, true);
    }

}
