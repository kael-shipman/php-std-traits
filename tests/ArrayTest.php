<?php

class ArrayTest extends \PHPUnit\Framework\TestCase {
    public function testIsArrayAccessible() {
        $t = new \Test\TestArray();
        $t['test'] = 1;
        $this->assertEquals(1, $t['test']);
    }

    public function testIsCountable() {
        $t = new \Test\TestArray();
        $t['test'] = 1;
        $this->assertEquals(1, count($t['test']));
    }

    public function testIsIterable() {
        $t = new \Test\TestArray();
        $t['test1'] = 1;
        $t['test2'] = 2;
        $t['test3'] = 3;

        $str = [];
        foreach($t as $k => $v) $str[] = "$k:$v";
        $str = implode(';', $str);
        $this->assertEquals('test1:1;test2:2;test3:3', $str);
    }
}

