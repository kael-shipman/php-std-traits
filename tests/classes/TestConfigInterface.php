<?php
namespace Test;

interface TestConfigInterface extends \KS\WebappConfigInterface
{
    public function getTest1(): string;
    public function getTest2(): string;
}

