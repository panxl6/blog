<?php

class HashTale
{
    private $size = 0;
    private $container = null;
    
    private $bigPrime = 1861;

    public function __construct($size=1000)
    {
        $this->size = $size;
        // 初始化容器
        $this->container = array_fill(0, $size, $this->getItemDefine());
    }
    
    public function get($key)
    {
        
    }
    
    public function set($key, $value)
    {
        
    }
    
    public function delete($key)
    {
        
    }

    private function getItemDefine()
    {
        $item = array(
            'key' => '',
            'value' => '',
        );
        
        return $item;
    }
    
    private function hashNum($key)
    {
        // $key不能太长，$this->bigPrime也不能太大，不然会引起整数溢出

        $hashNum = 0;
        $keyLen = strlen($key);
        
        // 这里的**幂运算你也可以用位移来实现
        for ($i=0; $i<$keyLen; $i++) {
            $hashNum += ($this->bigPrime ** ($keyLen - ($i+1))) * ord($key[$i]);
        }
        
        $hashNum = $hashNum % $this->size;
        
        return $hashNum;
    }
}

$hashTable = new HashTale(2);
var_dump($hashTable);