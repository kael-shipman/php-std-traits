<?php
namespace Test;

class TestListener {
    protected $testField;
    protected $testData;

    public function setTestField($val) {
        $this->testField = $val;
    }

    public function getTestField() { return $this->testField; }
    public function getTestData() { return $this->testData; }

    public function handleTestEvent(\KS\EventInterface $e) {
        $this->setTestField($e->getTarget()->getTestField());
        $this->testData = $e->getData();
    }
}


