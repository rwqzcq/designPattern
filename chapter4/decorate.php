<?php
/**
 * 装饰器模式
 */

// 服装搭配系统第一版
class Person {
    private $name = '';
    public function __construct($name) {
        $this->name = $name;
    }
    /**
     * 穿体恤
     */
    public function wearTshirt() {
        echo '穿体恤';
    }
    /**
     * 穿垮裤
     */
    public function wearBigTrouser() {
        echo '穿垮裤';
    }
    /**
     * 穿破球鞋
     */
    public function wearSneakers() {
        echo '穿破球鞋';
    }
    /**
     * 穿西装
     */
    public function wearSuit() {
        echo '穿西装';
    }
    /**
     * 穿领带
     */
    public function wearTie() {
        echo '穿领带';
    }
    /**
     * 展示
     */
    public function show() {
        echo '做展示的是'.$this->name;
    }
}

// 服装搭配系统第二版

abstract class Finery { // 服饰的抽象基类
    public abstract function show(); // 抽象方法，每种装扮都去实现这个方法
}

class WearTshirt extends Finery { // 穿T恤的类
    public function show() { 
        echo '穿体恤';
    }
}

class WearSuit extends Finery { // 穿西装的类
    public function show() { 
        echo '穿西装';
    }
}

// class client { // 客户端调用
//     public static function main() {
//         $person = new Person('rwq');
//         $suit = new wearSuit(); // 穿西装
//         $tshirt = new WearTshirt(); // 穿T恤
//         $suit->show();
//         $tshirt->show();
//     } 
// }

// 装饰模式代码

abstract class Component { // 抽象基类
    public abstract function Operation();
}

class ConcreteComponent extends Component { // 具体对象
    public function Operation() {
        echo '具体对象的操作';
    }
}

abstract class Decorator  extends Component { // 抽象的装饰类
    protected $component = null; // 持有一个装饰对象的引用
    public function __set($key, $value) {
        $this->$key = $value;
    }
    public function Operation() { // 执行component的方法
        if($this->component != null) {
            $this->component->Operation();
        }
    }
}

class ConcreteDecoratorA extends Decorator { // 具体的装饰A
    private $addedState = null; // 本类特有的功能
    public function Operation() {
        parent::Operation(); // 执行原来component的功能
        $this->addedState = "装饰A特有的操作"; // 然后再执行本类特有的功能
        echo '装饰A中进行了特有的操作';
    }
}

class ConcreteDecoratorB extends Decorator { // 具体的装饰A
    public function Operation() {
        parent::Operation(); // 执行原来component的功能
        $this->addedBehavior(); // 再执行本类特有的操作
    }
    private function addedBehavior() { // 本类特有的操作
        echo '这是装饰B特有的操作';
    }
}

class Client { // 客户端代码调用
    public static function main() {
        header("Content-Type:text/html; charset=utf-8");
        $concrete = new ConcreteComponent(); // 实例化一个具体的操作类
        $decorator_1 = new ConcreteDecoratorA(); // 装饰1
        $decorator_2 = new ConcreteDecoratorB(); // 装饰2
        // 层层装饰
        $decorator_1->component = $concrete; // d1来包装c
        $decorator_2->component = $decorator_1; // d2来包装d1
        $decorator_2->Operation(); // 有顺序地执行
    }
}
//Client::main();

// 服装选择系统 装饰模式实现
class PersonDec { // 最上层的component类
    private $name = '';
    public function __construct($name) {
        $this->name = $name;
    }
    public function show() {
        echo $this->name.'开始装饰自己了'.'<br>';
    }
}
class FineryDecorator extends PersonDec { // 服饰的基类
    protected $person = null; // 服饰所持有的人的引用
    public function __construct(PersonDec $person) { // 把人传了进来
        $this->person = $person;
    }
    public function show() { // 方法重写最核心的代码
        if($this->person != null) {
            $this->person->show();
        }
    }
}

class SuitDecorator extends FineryDecorator { // 穿西装
    public function show() { // 重写show方法，追加
        parent::show(); // 执行父类的show
        $this->suit(); // 追加自己的装饰
    }
    private function suit() {
        echo '我要穿西装了'.'<br>';
    }
}

class HatDecorator extends FineryDecorator {
    public function show() { // 重写show方法，追加
        parent::show(); // 执行父类的show
        $this->hat(); // 追加自己的装饰
    }
    private function hat() {
        echo '戴帽子'.'<br>';
    }   
}
class clientDec {
    public static function main() {
        header("Content-Type:text/html; charset=utf-8");
        echo '第一个人的装扮<br>';
        $p1 = new PersonDec('rwq');
        $suit = new SuitDecorator($p1); // 把人传了进去
        $hat = new HatDecorator($suit); // 把戴帽子的人传了进去
        $hat->show();
    }
}
clientDec::main();