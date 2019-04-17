<?php

class SortTookit
{
	private function getMockData($size = 10)
	{
		$data = array();
		for ($i=0; $i<$size; $i++) {
			$data[] = random_int(0, 1000);
		}

		return $data;
	}

	public function test()
	{
		$data = $this->getMockData();

		var_dump($this->quickSort($data));
	}

	private function quickSort($data)
	{
		$dataSize = count($data);

		// 递归出口
		if ($dataSize <= 1) {
			return $data;
		}

		// 选主元
		$pviot = intval($dataSize/2);
		$pviotVal = $data[$pviot];

		// 初始化容器
		list($less, $greater) = array(array(), array());

		// 二分，partition
		for ($i=0; $i<$dataSize; $i++) {
			if ($i == $pviot) {
				continue;
			}

			if ($data[$i] > $pviotVal) {
				$greater[] = $data[$i];
			} else {
				$less[] = $data[$i];
			}
		}

		// 递归分治,然后合并分治得到的结果
		return array_merge($this->quickSort($less), array($pviotVal), $this->quickSort($greater));
	}
}


$class = new SortTookit();
$class->test();
