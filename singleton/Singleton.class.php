<?php
header("Content-Type:text/html; charset=utf-8");
class Singleton {
    public $name = null;
    private static $instance = null; // 需要生成的唯一的实例
    // 把构造方法的权限变成private，这样可以new失效
    private function __construct() {}
    // 把对象的clone也给禁止
    private function __clone() {}
    //对外界暴露唯一对象的方法
    public static function getInstance() {
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }
}

$single = Singleton::getInstance();
$single->name = "我是由single创建的单例对象，我没有改变";
unset($single);
$single2 = Singleton::getInstance();
echo $single2->name;


