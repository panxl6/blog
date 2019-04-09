<?php

class HashTale
{
	private $size = 0;
	private $container = null;
	
	public function __construct($size=1000)
	{
		// 哈希表的大小
		$this->size = $size;
		// 初始化容器
		$this->container = array_fill(0, $size, $this->getItemDefine());
	}

	private function getItemDefine()
	{
		$item = array(
			'key' => '',
			'value' => '',
		);
		
		return $item;
	}
}

$hashTable = new HashTale(2);
var_dump($hashTable);