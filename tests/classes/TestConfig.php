<?php
namespace Test;

class TestConfig extends \KS\WebappConfig implements TestConfigInterface
{
    public function getTest1(): string
    {
        return $this->get('test1');
    }

    public function getTest2(): string
    {
        return $this->get('test2');
    }
}

