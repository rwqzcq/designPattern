<?php
/**
 * 策略模式第2题
 */
/*
    假设现在要设计一个贩卖各类书籍的电子商务网站的购物车系统。
    一个最简单的情况就是把所有货品的单价乘上数量，但是实际情况肯定比这要复杂。
    比如，本网站可能对所有的高级会员提供每本20%的促销折扣；
    对中级会员提供每本10%的促销折扣；
    对初级会员没有折扣。
*/
/*
    分析： 都是计算单价和数量，只不过是计算的方式不同
    实现: 
        一个计算价格的抽象基类，也就是基策略类
        一个持有策略类的上下文类
        若干个实现类
*/

/**
 * 策略基类
 */
abstract class Collect {
    protected $rate = 1;
    public abstract function count($money);
}
/**
 * 中等会员收钱策略类
 */
class HighCollect extends Collect {
    public function __construct($rate = 0.8) {
        $this->rate = $rate;
    }
    public function count($money) {
        return $money*$this->rate;
    }
}

/**
 * 中等会员收钱策略类
 */
class MidCollect extends Collect {
    public function __construct($rate = 0.9) {
        $this->rate = $rate;
    }
    public function count($money) {
        return $money*$this->rate;
    }
}
/**
 * 中等会员收钱策略类
 */
class NorCollect extends Collect {
    public function __construct($rate = 1) {
        $this->rate = $rate;
    }
    public function count($money) {
        return $money*$this->rate;
    }
}

/**
 * 计算价钱的上下文类
 */
class context {
    private $collect = null;
    public function __construct($type) {
        switch($type) {
            case 'High' : 
                $this->collect = new HighCollect();
                break;
            case 'Mid' : 
                $this->collect = new MidCollect();
                break;
            case 'Nor' : 
                $this->collect = new NorCollect();
                break;
        }
    }
    public function getResult($money) {
        return $this->collect->count($money);
    }
}
/** 客户端调用 */
class Client {
    public static function getResult($info) {
        $context = null;
        foreach($info as $k) {
            $context = new Context($k['level']);
            echo $context->getResult($k['money']);
            echo '<br>';
        }
    }
}
// 调用
$info = [
    [
        'level' => 'High',
        'money' => 100
    ],
    [
        'level' => 'Mid',
        'money' => 100
    ],
    [
        'level' => 'Nor',
        'money' => 100
    ]
];
Client::getResult($info);