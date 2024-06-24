<?php

namespace App\Constants;

class Common{

const PRODUCT_ADD = "1";//追加

const PRODUCT_REDUCE = "2";//削減

//上のままでもOK下のように連想配列も書いても良し

const PRODUCT_LIST = [

"add" => self::PRODUCT_ADD,//クラスの中でconstを指定するならself::が必要!

"reduce" => self::PRODUCT_REDUCE

];

}
