<?php
/**
 * 过程化写法
 */
function calculateWithProgress($num_1, $num_2, $operator) {
    $result = null;
    $num_1 = (float)($num_1);
    $num_2 = (float)($num_2);
    switch($operator) {
        case "+" :
            $result = $num_1 + $num_2;
            break;
        case "*" :
            $result = $num_1 * $num_2;
            break;
        case "-" : 
            $result = $num_1 - $num_2;
            break;
        case "/" :
            if($num_2 == 0) {
                $result = "除数不为0";
                break;
            }
            $result = $num_1 / $num_2;
            break;
        default :
            $result = "没有你想要的运算符";        
    }
    return $result;
}
calculateWithProgress(2, 5, "-");

//###### 面向对象设计 让系统变得可复用 计算和显示分开
class opeartion1 { // 计算类
    public static function getResult($num1, $num2, $operator) {
        $result = null;
        $num_1 = (float)($num_1);
        $num_2 = (float)($num_2);
        switch($operator) {
            case "+" :
                $result = $num_1 + $num_2;
                break;
            case "*" :
                $result = $num_1 * $num_2;
                break;
            case "-" : 
                $result = $num_1 - $num_2;
                break;
            case "/" :
                if($num_2 == 0) {
                    $result = "除数不为0";
                    break;
                }
                $result = $num_1 / $num_2;
                break;
            default :
                $result = "没有你想要的运算符";        
        }
        return $result;
    }
}
//###### 在显示结果的时候直接调用这个操作类就可以

//###### 当系统运算需要增加一个新的功能的时候，这个时候用能考验到程序的可扩展性，增加新功能，
//###### 不能对原来系统的功能产生影响，同时也要避免无所谓的开销

class Operation { // 把操作符分离出来的计算类,每一种操作只需要去继承这个类并实现其中的方法就可以
    protected $num1 = 0;
    protected $num2 = 0;
    public function getResult() { // 要被重写的方法
        $result = null;
        $this->num_1 = (float)($this->num_1);
        $this->num_2 = (float)($this->num_2);
        return $result;
    }
}

class AddOperation extends Operation { // 加法类
    public function getResult() { // 重写父类计算方法
        $result = null;
        $this->num_1 = (float)($this->num_1);
        $this->num_2 = (float)($this->num_2);
        $result += $this->num_1 + $this->num_2;
        return $result;
    }    
}

class DeleteOperation extends Operation { // 减法类
    public function getResult() { // 重写父类计算方法
        $result = null;
        $this->num_1 = (float)($this->num_1);
        $this->num_2 = (float)($this->num_2);
        $result += $this->num_1 - $this->num_2;
        return $result;
    }     
}

//###### 这个时候如果需要增加一个求方根的操作，
//###### 只需要去新写一个求方根的子类，然后再去重写父类的计算结果的方法就可以了

//###### 在最终的调用层不需要实例化某一个具体的类，他们也不需要知道具体的类名，只需要用最常见的方法就可以得到最终的结果
//###### 简单工厂模式

class OperationFactory { // 工厂类
    public static function createOperate($operator) { // 工厂方法，通过传入不同的运算符实例化不同的类
        $oper = null; // 最终要返回的计算对象
        switch($operator) {
            case "+" :
                $oper = new AddOperate();
                break;
            case "-" : 
                $oper = new DeleteOperate();
                break;
            case "/" :
                $oper = new DivOperater();
                break;
            default :
                $oper = null;        
        }  
        return $oper;      
    }
}