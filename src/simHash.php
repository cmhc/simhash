<?php
/**
 * php实现的simhash
 */
namespace simhash;

class simHash
{
    /**
     * 获取词数组的simhash值
     * @param  array $words 关键词数组，数组的key为关键词，数组的值为该词的权重
     * @return  array hashData
     */
    public function get($words)
    {
        $binHash = $this->getHash($words);
        $seg = str_split($binHash, 16);
        $hash = base_convert($binHash, 2, 16);
        // 四个组成部分转换成10进制，用于存储，后续方便查询
        $seg = array_map(function($num) {
            return base_convert($num, 2, 10);
        }, $seg);
        return array(
            'hash' => $hash,
            'bin_hash' => $binHash,
            'seg' => $seg
        );
    }

    /**
     * 获取两个字符串的海明距离
     * @param  string $a
     * @param  string $b
     */
    public function hammingDistance($a, $b)
    {
        $a = str_split($a);
        $b = str_split($b);
        $distance = 0;
        foreach ($a as $i=>$w) {
            if ($b[$i] != $w) {
                $distance += 1;
            }
        }
        return $distance;
    }

    /**
     * 获取关键词的hash结果
     * @param  array $words
     * @return  string 关键词的hash结果
     */
    protected function getHash($words)
    {
        $arr = str_split(str_pad(0, 64, 0));
        foreach ($words as $word => $weight) {
            $hash = hex2bin(substr(md5($word), 8, 16));
            $bin = $this->str2bin($hash);
            for ($i=0; $i<64; $i++) {
                if ($bin[$i] == 1) {
                    $arr[$i] += $weight;
                } else {
                    $arr[$i] -= $weight;
                }
            }
        }
        $simhash = '';
        foreach ($arr as $a) {
            if ($a > 0) {
                $simhash .= '1';
            } else {
                $simhash .= '0';
            }
        }

        return $simhash;
    }

    /**
     * 字符串转换成二进制字符
     * @param  string $str 需要转的字符串
     * @return array 由8位二进制串组成的数组
     */
    protected function str2bin($str)
    {
        $bin = '';
        for($i=0, $j=strlen($str); $i<$j; $i++){
            $bin .= str_pad(base_convert(ord($str[$i]), 10, 2), 8, 0, STR_PAD_LEFT);
        }
        return str_split($bin);
    }

}