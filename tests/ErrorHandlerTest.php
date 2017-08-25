<?php

class ErrorHandlerTest extends \PHPUnit\Framework\TestCase {
    public function testErrors() {
        $t = new \Test\TestErrorHandler();

        $this->assertEquals(0, $t->numErrors());
        $this->assertEquals(0, $t->numErrors('testField'));
        $this->assertEquals([], $t->getErrors());
        $this->assertEquals([], $t->getErrors('testField'));

        $t->produceError('testField', 'Email is bad');
        $this->assertEquals(1, $t->numErrors());
        $this->assertEquals(1, $t->numErrors('testField'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Email is bad', serialize($t->getErrors('testField')));

        $t->produceError('testField', 'Email is required', 'email-required');
        $this->assertEquals(2, $t->numErrors());
        $this->assertEquals(2, $t->numErrors('testField'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Email is required', serialize($t->getErrors()));
        $this->assertContains('Email is required', serialize($t->getErrors('testField')));

        $t->produceError('testField', 'Email is seriously required', 'email-required');
        $this->assertEquals(2, $t->numErrors());
        $this->assertEquals(2, $t->numErrors('testField'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Email is seriously required', serialize($t->getErrors()));

        $t->deleteError('testField', 'email-required');
        $this->assertEquals(1, $t->numErrors());
        $this->assertEquals(1, $t->numErrors('testField'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Email is bad', serialize($t->getErrors('testField')));

        $t->produceError('testField2', 'Name is required', 'name-required');
        $this->assertEquals(2, $t->numErrors());
        $this->assertEquals(1, $t->numErrors('testField'));
        $this->assertEquals(1, $t->numErrors('testField2'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Name is required', serialize($t->getErrors()));

        $t->deleteAllErrors();
        $this->assertEquals(0, $t->numErrors());
        $this->assertEquals([], $t->getErrors());
    }
}

