<?php

class ErrorHandlerTest extends \PHPUnit\Framework\TestCase {
    public function testErrors() {
        $t = new \Test\TestErrorHandler();

        $this->assertFalse($t->hasErrors());
        $this->assertFalse($t->hasErrors('testField'));
        $this->assertEquals(0, $t->numErrors());
        $this->assertEquals(0, $t->numErrors('testField'));
        $this->assertEquals([], $t->getErrors());
        $this->assertEquals([], $t->getErrors('testField'));

        $t->produceError('testField', null, 'Email is bad');
        $this->assertTrue($t->hasErrors());
        $this->assertTrue($t->hasErrors('testField'));
        $this->assertFalse($t->hasErrors('testField2'));
        $this->assertEquals(1, $t->numErrors());
        $this->assertEquals(1, $t->numErrors('testField'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Email is bad', serialize($t->getErrors('testField')));

        $t->produceError('testField', 'email-required', 'Email is required');
        $this->assertTrue($t->hasErrors());
        $this->assertTrue($t->hasErrors('testField'));
        $this->assertFalse($t->hasErrors('testField2'));
        $this->assertEquals(2, $t->numErrors());
        $this->assertEquals(2, $t->numErrors('testField'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Email is required', serialize($t->getErrors()));
        $this->assertContains('Email is required', serialize($t->getErrors('testField')));

        $t->produceError('testField', 'email-required', 'Email is seriously required');
        $this->assertTrue($t->hasErrors());
        $this->assertTrue($t->hasErrors('testField'));
        $this->assertFalse($t->hasErrors('testField2'));
        $this->assertEquals(2, $t->numErrors());
        $this->assertEquals(2, $t->numErrors('testField'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Email is seriously required', serialize($t->getErrors()));

        $t->deleteError('testField', 'email-required');
        $this->assertTrue($t->hasErrors());
        $this->assertTrue($t->hasErrors('testField'));
        $this->assertFalse($t->hasErrors('testField2'));
        $this->assertEquals(1, $t->numErrors());
        $this->assertEquals(1, $t->numErrors('testField'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Email is bad', serialize($t->getErrors('testField')));

        $t->produceError('testField2', 'Name is required', 'name-required');
        $this->assertTrue($t->hasErrors());
        $this->assertTrue($t->hasErrors('testField'));
        $this->assertTrue($t->hasErrors('testField2'));
        $this->assertEquals(2, $t->numErrors());
        $this->assertEquals(1, $t->numErrors('testField'));
        $this->assertEquals(1, $t->numErrors('testField2'));
        $this->assertContains('Email is bad', serialize($t->getErrors()));
        $this->assertContains('Name is required', serialize($t->getErrors()));

        $t->deleteAllErrors();
        $this->assertFalse($t->hasErrors());
        $this->assertFalse($t->hasErrors('testField'));
        $this->assertFalse($t->hasErrors('testField2'));
        $this->assertEquals(0, $t->numErrors());
        $this->assertEquals([], $t->getErrors());
    }
}

