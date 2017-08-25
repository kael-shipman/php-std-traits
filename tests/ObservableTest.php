<?php

class ObservableTest extends \PHPUnit\Framework\TestCase {
    public function testEventSystem() {
        $t = new \Test\TestObservable();
        $t1 = new \Test\TestListener();
        $t->registerListener('test-field-set', $t1, 'handleTestEvent');

        // Setting testField on $t should trigger an event that sets testField on $t1
        $t1->setTestField(1);
        $this->assertEquals(1, $t1->getTestField());
        $this->assertNull($t1->getTestData());
        $t->setTestField(536);
        $this->assertEquals(536, $t1->getTestField());
        $this->assertNotNull($t1->getTestData());
    }
}

