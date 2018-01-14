<?php
namespace Test;

interface TestConfigInterface extends \KS\BaseConfigInterface
{
    public function getTest1(): string;
    public function getTest2(): string;
}

