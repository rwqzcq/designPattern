<?php
/**
 * 策略模式练习
 * 1-24
 * 《PHP面向对象模式、实践》P184
 */

/**
 * 问题描述：创建了一个问题类，添加了一个mark方法，用户回答问题可以使用多种不同的标记方式，直接想到的方法就是写一些不同标记方式的类去继承Question类，这个是强调问题
 * 的标记方式，如果此时问题的类型需要增加的时候，那么再让回答问题的方式去继承不同的问题类型的时候就会产生冗余，因此此时需要用到策略模式
 * 在本问题中，提问问题的方式是随时变化的，他们也是一系列的算法，只是实现的方式不同，这些子类都会实现父类的一个方法
 */

 abstract class Marker { // 抽象父类
     public abstract function mark(); // 让不同策略的子类去实现的方法
 }

 class MarkLogicMarker extends Marker { // MarkLogicMarker策略
    public function mark() {
        echo __CLASS__;
    }
 }

 class MatchMarker extends Marker { // MatchLogicMarker策略
    public function mark() {
        echo __CLASS__;
    }    
 }

 class RegexMarker extends Marker {     
    public function mark() {
        echo __CLASS__;
    }   
 }

 class Question { // 策略对象
     protected $marker = null;
     public function __construct(Marker $marker) {
        $this->marker = $marker;
     }
     public function mark() { // 执行不同算法的不同实现
       return $this->marker->mark();
     }
 }

 class AVQuestion extends Question {

 }
 $av = new AVQuestion(new RegexMarker());
 $av->mark();

