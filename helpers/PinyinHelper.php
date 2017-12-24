<?php
namespace app\helpers;
class PinyinHelper {

	//utf-8中国汉字集合
	private static $ChineseCharacters;
	//编码
	private static $charset = 'utf-8';

	public static function init() {
		if (empty(self::$ChineseCharacters)) {
			self::$ChineseCharacters = file_get_contents(__DIR__ . '/ChineseCharacters.dat');
		}
	}

	/*
	* 转成带有声调的汉语拼音
	* param $input_char String  需要转换的汉字
	* param $delimiter  String   转换之后拼音之间分隔符
	* param $outside_ignore  Boolean     是否忽略非汉字内容
	*/
	public static function convertWithTone($input_char, $delimiter = '', $outside_ignore = false) {
		self::init();
		$input_len = mb_strlen($input_char, self::$charset);
		$output_char = '';
		for ($i = 0; $i < $input_len; $i++) {
			$word = mb_substr($input_char, $i, 1, self::$charset);
			if (preg_match('/^[\x{4e00}-\x{9fa5}]$/u', $word) && preg_match('/\,' . preg_quote($word) . '(.*?)\,/', self::$ChineseCharacters, $matches)) {
				$output_char .= $matches[1] . $delimiter;
			} else if (!$outside_ignore) {
				$output_char .= $word;
			}
		}

		return $output_char;
	}

	/*
	* 转成带无声调的汉语拼音
	* param $input_char String  需要转换的汉字
	*/
	public static function convert($input_char) {
		self::init();
		$search = ['旅行', '行李', '自行车', '行政', '行踪', '进行', '品行', '行礼', '五行', '乐器', '音乐', '声乐', '乐队', '会计', '城堡', '堡垒', '家长', '长者', '长辈', '部长', '校长', '举重', '重镇', '重点', '重任', '重托', '重视', '尊重', '器重', '隆重', '慎重'];
		$replace = ['lvxing', 'xingli', 'zixingche', 'xingzheng', 'xingzong', 'jinxing', 'pinxing', 'xingli', 'wuxing', 'yueqi', 'yinyue', 'shengyue', 'yuedui', 'kuaiji', 'chengbao', 'baolei', 'jiazhang', 'zhangzhe', 'zhangbei', 'buzhang', 'xiaozhang', 'juzhong', 'zhongzhen', 'zhongdian', 'zhongren', 'zhongtuo', 'zhongshi', 'zunzhong', 'qizhong', 'longzhong', 'shenzhong'];
		$input_char = str_replace($search, $replace, $input_char);

		//地名多音字
		$search = ['东莞', '蔚县', '歙县', '繁峙', '铅山', '六安', '东阿', '重', '长', '堡', '喀什'];
		$replace = ['dongguan', 'yuxian', 'shexian', 'fanshi', 'yanshan', 'luan', 'donge', 'chong', 'chang', 'pu', 'kashi'];
		$input_char = str_replace($search, $replace, $input_char);

		$char_with_tone = self::convertWithTone($input_char);

		$char_without_tone = str_replace(['ā', 'á', 'ǎ', 'à', 'ō', 'ó', 'ǒ', 'ò', 'ē', 'é', 'ě', 'è', 'ī', 'í', 'ǐ', 'ì', 'ū', 'ú', 'ǔ', 'ù', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'ü'],
			['a', 'a', 'a', 'a', 'o', 'o', 'o', 'o', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'u', 'u', 'u', 'u', 'v', 'v', 'v', 'v', 'v']
			, $char_with_tone);
		return $char_without_tone;

	}

	/*
	* 转成汉语拼音首字母
	* param $input_char String  需要转换的汉字
	*/
	public static function convertSZM($input_char) {
		self::init();
		$search = ['重庆', '东莞'];
		$replace = ['cq', 'dg'];
		$input_char = str_replace($search, $replace, $input_char);

		$char_without_tone = ucwords(self::convertWithTone($input_char, ' '));
		$ucwords = preg_replace('/[^A-Z]/', '', $char_without_tone);
		return $ucwords;
	}

}

