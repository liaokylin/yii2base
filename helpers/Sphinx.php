<?php
/**
 * 列表网  liebiao.com
 *
 * SphinxClient 子类
 * 包装 建立sphinx链接, 基本属性, 获取分词, 全分类检索
 *
 * @author linjian
 * @date 2012-12-07
 * @copyright Yuxun Co. Since 2007
 */

namespace app\helpers;

include_once __DIR__ . '/sphinxapi.php';


class Sphinx extends SphinxClient {

	public $server = '127.0.0.1';

	public $port = 9312;

	public function __construct() {
		parent::SphinxClient();
		$this->connect($this->server, $this->port);
	}

	// 连接
	private function connect($host, $port) {
		$this->SetServer($host, $port);
		$this->SetConnectTimeout(1);
		$this->SetArrayResult(true);
		//$this->SetWeights(array(100, 1));
	}

	/**
	 * 覆盖sphinx分词方法, 为索引和命中增加默认参数方法
	 * @param string $string 需要分词的词
	 * @param string $index 索引
	 * @param bool   $hits 是否命中
	 * @return array|bool
	 */
	public function BuildKeywords($string, $index = 'lb_posts1307s1', $hits = false) {
		return parent::BuildKeywords($string, $index, $hits);
	}
}