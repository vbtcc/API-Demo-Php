<?php
/**
 * Created by PhpStorm.
 * User: xu
 * Date: 2019/7/18
 * Time: 2:13 PM
 */
include './Vb.php';

$vb = new vb([
    'key'=>'替换为自己申请的key',
    'secret'=>'替换为自己申请的secret'
]);


//获取当前最新行情
print_r($vb -> ticker(['symbol' => 40]));

//获取当前所有最新行情
print_r($vb -> tickers());

//市场深度
print_r($vb -> depth(['symbol' => 40, 'merge' => 2]));

//最近的市场交易
print_r($vb -> orders(['symbol' => 40, 'size' => 20]));

//查询账户信息
print_r($vb -> balances());

//充值地址
print_r($vb -> coin_address(['symbol' => 35]));

//账户挂单查询
print_r($vb -> trade_list(['symbol' => 40, 'since' => 1562743428, 'type' => 0]));

//查询订单信息
print_r($vb -> trade_view(['id' => 8295859]));

//挂单
print_r($ret = $vb -> trade(['symbol' => 40, 'amount' => 10, 'price' => 0.01, 'type' => 'sell']));

//撤销上一笔挂的单
print_r( $vb -> cancel_trade(['id' => $ret['id']]));

