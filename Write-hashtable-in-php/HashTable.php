<?php
/*
 * 系列文章，[《从零开始写一个哈希表》](https://blog.csdn.net/panxl6/article/details/84846229)的PHP版的实现。
 */
class HashTable
{
    
    private $size = 0;
    private $container = null;
    
    private $bigPrime = 1861;
    
    public function __construct($size=1000)
    {
        // 初始化容器
        $this->size = $size;
        $this->container = array_fill(0, $size, $this->getItemDefine());
    }
    
    public function get($key)
    {
        $hashNum = $this->hashNum($key);
        $result = $this->container[$hashNum];
        
        if ($result['key'] == $key) {
            return $result['value'];
        }
        
        return $this->findItemInList($result['list'], $key);
    }
    
    public function set($key, $value)
    {
        $hashNum = $this->hashNum($key);
        
        $result = $this->container[$hashNum];
        
        if ($result['key'] == '') {
            // 槽位空，直接写入
            $result['key'] = $key;
            $result['value'] = $value;
        } elseif ($result['key'] == $key) {
            // 更新value
            $result['value'] = $value;
        } else {
            // 解决哈希冲突
            $this->addItemToList($result['list'], $key, $value);
        }
        
        $this->container[$hashNum] = $result;
        
        return null;
    }
    
    public function delete($key)
    {
        $hashNum = $this->hashNum($key);
        $this->container[$hashNum] = $this->getItemDefine();
    }
    
    private function addItemToList(&$list, $key, $item)
    {
        foreach ($list as $curKey => $value) {
            if ($key == $curKey) {
                return true;
            }
        }
        
        // 未找到,将新的键值插入链表
        $list[$key] = $item;
        return false;
    }
    
    private function findItemInList($list, $key)
    {
        foreach ($list as $curKey => $value) {
            if ($key == $curKey) {
                return $value;
            }
        }
        
        return null;
    }
    
    private function getItemDefine()
    {
        $item = array(
            'key' => '',
            'value' => '',
            
            // 用于解决哈希冲突的链表
            'list' => array()
        );
        
        return $item;
    }
    
    private function hashNum($key)
    {
        $hashNum = 0;
        $keyLen = strlen($key);
        
        for ($i=0; $i<$keyLen; $i++) {
            $hashNum += ($this->bigPrime ** ($keyLen - ($i+1))) * ord($key[$i]);
        }
        
        $hashNum = $hashNum % $this->size;
        
        return $hashNum;
    }
}


$hashTable = new HashTable(2);

$hashTable->set('hello', 'value');
var_dump($hashTable->get('hello'));

$hashTable->set('hello1', 'value1');
var_dump($hashTable->get('hello1'));

$hashTable->set('hello2', 'value2');
var_dump($hashTable->get('hello2'));