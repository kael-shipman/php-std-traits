<?php
namespace Test;

class TestCliConfig extends \KS\AbstractCliConfig
{
    public function getTest1(): string
    {
        return $this->get('test1');
    }

    public function getTest2(): string
    {
        return $this->get('test2');
    }

    public function getTest3(): string
    {
        return $this->get('test3');
    }

    public function getTest4(): string
    {
        return $this->get('test4');
    }

    public function getTest5(): string
    {
        return $this->get('test5');
    }
}


