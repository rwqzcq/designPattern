<?php
/**
 * 有若干个Person对象那个存放在一个List里面，对他们分别进行排序，分别按照名字、年龄、id进行排序，要有正序和倒序两种方式
 * 假如年龄和名字重复，那么就按照id的正序排序
 */

/*
    分析: 
        找到策略: 排序
        找到实现策略的方式: 名字、年龄、id
    自己没有想到的是，需要构造一个比较器
*/
/**
 * 比较器
 */
function compareByName($list){
    usort($list, function($obj1, $obj2){
        $name1 = $obj1->name;        
        $name2 = $obj2->name;
        $compare_result = strcmp($name1, $name2);
        // 按照名字比较
        if($compare_result != 0) {
            return $compare_result;
        } else {
            return $obj1->id - $obj2->id;
        }
    });
    return $list;
}


class Person {
    private $id;
    private $name;
    private $age;
    public function __construct($name, $id, $age) {
        $this->id = $id;
        $this->name = $name;
        $this->age = $age;
    }
    public function __get($key) {
        return $this->$key;
    }
}

/**
 * 排序策略
 */
abstract class Sort {
    public abstract function  poSort($list); // 正序
    //public abstract function reSort($list);
}
/**
 * 按照姓名排序策略
 */
class NameSort extends Sort {
    public function  poSort($list) {
        return compareByName($list);
    }
}
/**
 * 上下文类
 */
class Context {
    private $sort = null;
    public function __construct($type) {
        switch($type) {
            case 'name' :
                $this->sort = new NameSort();
                break;
        }
        
    }
    public function getPResult($list) {
        return $this->sort->poSort($list);
    }
}

class Client {
    public static function main() {
        $test = [
            new Person('zhangsan', 18, 5866),
            new Person('zhangsan', 25, 5863),
            new Person('lisi', 19, 5867),
            new Person('wangwu', 26, 5866),
        ];
        $context = new Context('name'); // 按照name排序
        var_dump($context->getPResult($test));
    }
}
Client::main();





