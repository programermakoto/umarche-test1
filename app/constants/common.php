<?php

namespace App\Constants;

class Common
{

    const PRODUCT_ADD = "1";//追加

    const PRODUCT_REDUCE = "2";//削減

    //上のままでもOK下のように連想配列も書いても良し

    const PRODUCT_LIST = [

        "add" => self::PRODUCT_ADD,//クラスの中でconstを指定するならself::が必要!

        "reduce" => self::PRODUCT_REDUCE

    ];

    // 表示順の定数を作成

    const ORDER_RECOMMEND = '0';//おすすめ順

    const ORDER_HIGHER = '1';//高い順

    const ORDER_LOWER = '2';//安い順

    const ORDER_LATER = '3';//新しい順

    const ORDER_OLDER = '4';//古い順

    // 各定数を配列に入れていく、同じファイルの定数にはself::必須

    const SORT_ORDER = [

        'recommend' => self::ORDER_RECOMMEND,

        'higherPrice' => self::ORDER_HIGHER,

        'lowerPrice' => self::ORDER_LOWER,

        'later' => self::ORDER_LATER,

        'older' => self::ORDER_OLDER

    ];


}
