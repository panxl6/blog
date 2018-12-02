<?php

class Calculator
{
    public function test()
    {
        $expression = '2*(9+6/3-5)+4';
        $infix = $this->parseExpression($expression);
        //var_dump($expression);
        //print("\n");
        //var_dump($ret);
        //var_dump($expression ===  implode('',$ret));
        $postfix = $this->infixToPostfix($infix);
        var_dump(implode(' ', $postfix));
        $num = $this->rpn($postfix);
        var_dump($num);
        eval("\$num=(2*(9+6/3-5)+4);");
        var_dump($num);
    }

    private function rpn($expression)
    {
        $opNumStack = array();
        foreach ($expression as $item) {
            if (is_numeric($item)) {
                $opNumStack[] = $item;
                continue;
            }

            $top = array_pop($opNumStack);
            $second = array_pop($opNumStack);

            $ret = $this->cal($second, $top, $item);
            $opNumStack[] = $ret;
        }

        return end($opNumStack);
    }

    private function cal($a, $b, $op)
    {
        switch ($op) {
        case '+':
            return $a + $b;
        case '-':
            return $a - $b;
        case '*':
            return $a * $b;
        case '/':
            return $a / $b;
        case '%':
            return $a % $b;
        default:
            return 0;
        }
    }

    private function infixToPostfix($infix)
    {
        $stack = array();
        $postfix = array();
        $opSet = array('+', '-', '*', '/', '%');
        foreach ($infix as $item) {
            if (is_numeric($item)) {
                $postfix[] = $item;
                continue;
            }

            if ($item == '(') {
                $stack[] = $item;
                continue;
            }

            if ($item == ')') {
                while ($stack) {
                    $op = array_pop($stack);
                    if ($op == '(')
                        break;
                    $postfix[] = $op;
                }
                continue;
            }

            if (count($stack) == 0) {
                $stack[] = $item;
                continue;
            }

            $top = end($stack);
            if ($this->isPriorityHigher($item, $top)) {
                $stack[] = $item;
                continue;
            }

            while ($stack) {
                $postfix[] = array_pop($stack);
                if ($this->isPriorityHigher($item, end($stack))) {
                    break;
                }
            }

            $stack[] = $item;
        }

        foreach ($stack as $item) {
            $postfix[] = $item;
        }

        return $postfix;
    }

    private function parseExpression($expression)
    {
        if (empty($expression))
            return array();

        $elemList = array();

        $start = 0;
        $opSet = array('+', '-', '*', '/', '(', ')');
        $strLen = strlen($expression);
        $strLast = $strLen - 1;
        for ($i=0; $i<$strLen; $i++) {
            if (in_array($expression[$i], $opSet)) {
                $this->getNumber($expression, $start, $i, $elemList);
                $elemList[] = $expression[$i];
                $start = $i + 1;
                continue;
            } else if ($i==$strLast) {
                $this->getNumber($expression, $start, $strLen, $elemList);
            }
        }

        return $elemList;
    }

    private function getNumber($expression, $start, $end, &$elemList)
    {
          $ret = substr($expression, $start, $end-$start);
          if ($ret)
              $elemList[] = $ret;        
    }

    private function isPriorityHigher($a, $b)
    {
        $firstPriority = array('*', '/', '%');
        $secondPriority = array('+', '-');

        if (in_array($a, $firstPriority) && in_array($b, $firstPriority))
            return false;
        
        if (in_array($a, $secondPriority) && in_array($b, $secondPriority))
            return false;
        
        if (in_array($a, $secondPriority) && in_array($b, $firstPriority))
            return false;

        return true;
    }
}

$calculator = new Calculator();
$calculator->test();

