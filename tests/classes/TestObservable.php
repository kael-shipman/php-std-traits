<?php
namespace Test;

class TestObservable {
    use \KS\ObservableTrait;

    protected $testField;

    public function setTestField($val) {
        $this->testField = $val;
        $this->triggerEvent(new \KS\Event('test-field-set'), [ 'test-data' => 5 ]);
    }

    public function getTestField() { return $this->testField; }
}

