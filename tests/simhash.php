<?php
/**
 * 使用测试前首先使用 composer install
 */
use simhash\simHash;

require dirname(__DIR__) . '/vendor/autoload.php';

class simHashForTest extends simHash
{
    public function getHash($words)
    {
        return parent::getHash($words);
    }

    public function str2bin($str)
    {
        return parent::str2bin($str);
    }

    public function hammingDistance($a, $b)
    {
        return parent::hammingDistance($a, $b);
    }
}


class simhashTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        $this->SimHash = new simHashForTest();
    }

    public function testGet()
    {
        $hash = $this->SimHash->get(array('你好' => 1, '世界'=> 1));
        $this->assertEquals(array(
            'hash' => '80208d88e246800',
            'bin_hash' => '0000100000000010000010001101100010001110001001000110100000000000',
            'seg' => array(
                '2050',
                '2264',
                '36388',
                '26624'
            )
        ),$hash);
    }

    /**
     * 测试海明距离
     */
    public function testHammingDistance()
    {
        $this->assertEquals($this->SimHash->hammingDistance('12345', '12346'), 1);
    }

    /**
     * 测试相似
     */
    public function testSimilar()
    {
        $hash1 = $this->SimHash->get(array('你好' => 4, '世界'=> 5, '这是' => 3, '我们' => 1));
        $hash2 = $this->SimHash->get(array('你好' => 4, '世界'=> 5, '这是' => 3, '我门' => 1));
        $this->assertEquals(0, $this->SimHash->hammingDistance($hash1['bin_hash'], $hash2['bin_hash']));
    }
}