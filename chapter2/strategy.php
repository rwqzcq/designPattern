<?php
/**
 * 策略模式
 */

// 商场收银系统V1.0
$total = 0;
function chargeV1($price, $num) {
    $total += doubleval($price)*doubleval($num);
    echo '数量: ' + $num + '合计: ' + $total;
}

// 商场收银系统V1.1 增加了打折的选项，商品可能会打折
$range = ['正常收费', '八折', '5折', '七折'];
function chargeV1_1($obj) {
    $totalPrice = 0; // 某一个商品的总价
    $index = $obj['SelectIndex'];
    //###### 此处开始出现冗余的代码
    switch($index) { // 根据$index来判断怎么收费
        case 0: // 正常收费
            $totalPrice = $obj['num']*$obj['price'];
            break;
        case 1: // 打八折
            $totalPrice = $obj['num']*$obj['price']*0.8;
            break;
        case 2: // 打5折
            $totalPrice = $obj['num']*$obj['price']*0.5;
            break;
        case 3: // 打7折
            $totalPrice = $obj['num']*$obj['price']*0.5;
            break;
    }
    $total += $totalPrice;
    echo '数量: ' + $obj['num'] + '合计: ' + $total;
}

// 商场收银系统V1.2 利用简单工厂实现 打折是相同的，但是打折的具体方式是不同的，同时增加一个返利类

//######服务器端代码开始
abstract class CashSuper { // 收银抽象类
    public abstract function acceptCash($money); // 抽象方法，让子类去实现这个收银的工作
}

class CashNormal extends CashSuper { // 普通收银类
    public function acceptCash($money) { // 实现了抽象父类的抽象方法
        return $money; // 不打折，或者说打10折，原价返回
    }
}

class CashRebate extends CashSuper { // 打折类
    private $moneyRate = 0.8; // 打折的费率
    public function __construct($moneyRate) { // 实例化类的时候把打折的折数赋值给成员变量
        $this->moneyRate = $moneyRate;
    }
    public function acceptCash($money) { // 实现抽象父类的抽象方法
        return $money*$moneyRate; // 根据打折的折数来计算总价
    }
}

class CashReturn extends CashSuper { // 返利类
    private $moneyCondition = 0;
    private $moneyReturn = 0;

    public function __construct($moneyCondition, $moneyReturn) { // 实例化类的时候把需要满足的金额数和返利数初始化
        $this->moneyCondition = $moneyCondition;
        $this->moneyReturn = $moneyReturn;
    }

    public function acceptCash($money) { // 实现抽象父类的抽象方法
        if($money > $this->moneyCondition) { // 满足返利条件
            $money = $money - floor($money/$this->moneyCondition)*$this->moneyReturn; // 满减
        }
        return $money;
    }
}

class CashFactory { // 现金收费工厂类
    public static function createCashAccept($type) { // 收取现金的工厂方法
        $cashAccept = null;
        switch($type) {
            case 'normal' : // 正常收费
                $cashAccept = new CashNormal();
                break;
            case '满300返100' : // 满减
                $cashAccept = new CashReturn(300, 100);
                break;
            case '打8折' : // 打折
                $cashAccept = new CashRebate(0.8);
                break;
        }
        return $cashAccept;
    }
}
//######服务器端代码结束

function clientTest($obj) { // 客户端代码
    $cashAccept = CashFactory::createCashAccept($obj['type']); // 简单工厂生产一个计算收费的对象
    $total = $cashAccept->acceptCash($obj['num']*$obj['price']); // 计算最终的费用
    return $total;
}

//###### 策略模式代码开始

class CashContext { // 收费的上下文类，持有一个收费策略对象的引用
    private $cashStrategy = null; // 收费策略对象
    public function __construct(CashSuper $cash) { // 实例化的时候也就相当于在该环境中新增了一个收费对象
        $this->cashStrategy = $cash;
    }
    public function getResult($money) {
        return $this->cashStrategy->acceptCash($money); // 计算最后的总价
    }
}

function chargeV1_3($obj) { // 客户端代码
    $totalPrice = 0; // 某一个商品的总价
    $index = $obj['SelectIndex'];
    $context = null; // 上下文对象
    switch($index) { // 根据$index来返回收费的策略，此时向客户端暴露了太多的类
        case 0: // 正常收费
            $context = new CashContext(new CashNormal());
            break;
        case 1: // 打八折
            $context = new CashContext(new CashRebate(0.8));
            break;
        case 2: // 打5折
            $context = new CashContext(new CashRebate(0.5));
            break;
        case 4: // 满减
            $context = new CashContext(new CashRuturn(300, 100));
            break;
    }
    $totalPrice = $context->getResult($obj['money']); // 计算总价
    $total += $totalPrice;
    echo '数量: ' + $obj['num'] + '合计: ' + $total;
}

//###### 策略模式代码结束

//###### 策略模式结合简单工厂
class CashContext { // 结合简单工厂实现的上下文类
    private $cashStrategy = null;
    public function __construct($type) { // 把客户端的代码放到上下文的“工厂中”
        switch($index) { // 根据$index来返回收费的策略
            case 0: // 正常收费
                $this->cashStrategy = new CashContext(new CashNormal());
                break;
            case 1: // 打八折
                $this->cashStrategy = new CashContext(new CashRebate(0.8));
                break;
            case 2: // 打5折
                $this->cashStrategy = new CashContext(new CashRebate(0.5));
                break;
            case 4: // 满减
                $this->cashStrategy = new CashContext(new CashRuturn(300, 100));
                break;
        }        
    }
    public function getResult($money) {
        return $this->cashStrategy->acceptCash($money); // 计算最后的总价
    }
}

function chargeV1_4($obj) { // 客户端代码
    $totalPrice = 0; // 某一个商品的总价
    $index = $obj['SelectIndex'];
    $context = null; // 上下文对象
    $context = new CashContext($index); // 客户端月简单越好，只暴露了一个类
    $totalPrice = $context->getResult($obj['money']); // 计算总价
    $total += $totalPrice;
    echo '数量: ' + $obj['num'] + '合计: ' + $total;
}
