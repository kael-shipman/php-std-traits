<?php

use \KS\BaseConfig;

class BaseConfigTest extends \PHPUnit\Framework\TestCase {
    public function testConfigRequiresBothConfigFilesToBeSpecified() {
        try {
            $c = new BaseConfig(__DIR__.'/configs/validconfig.php');
            $this->fail("Should have thrown an argument count error.");
        } catch (ArgumentCountError $e) {
            $this->assertTrue(true, "This is the correct behavior");
        }

        $c = new BaseConfig(__DIR__.'/configs/validconfig.php', __DIR__.'/configs/emptyconfig.php');
        $this->assertTrue($c instanceof \KS\BaseConfigInterface, "Should successfully instantiate config");
    }

    public function testConfigRequiresDefaultFileToExist() {
        try {
            $c = new BaseConfig(__DIR__.'/configs/bunkconfig.php', __DIR__.'/configs/bunkconfig.override.php');
            $this->fail("Should have thrown an exception.");
        } catch (\KS\NonexistentFileException $e) {
            $this->assertTrue(true, "This is the correct behavior");
        }
    }

    public function testConfigDoesntRequireOverrideToExist() {
        $c = new BaseConfig(__DIR__.'/configs/validconfig.php', __DIR__.'/configs/bunkconfig.override.php');
        $this->assertTrue($c instanceof \KS\BaseConfigInterface, "Should successfully instantiate config");
    }

    public function testConfigRequiresFilesToReturnArrays() {
        try {
            $c = new BaseConfig(__DIR__.'/configs/nonarrayconfig.php', __DIR__.'/configs/nonarrayconfig.override.php');
            $this->fail("Should have thrown an exception.");
        } catch (\KS\ConfigFileFormatException $e) {
            $this->assertTrue(true, "This is the correct behavior");
        }

        try {
            $c = new BaseConfig(__DIR__.'/configs/validconfig.php', __DIR__.'/configs/nonarrayconfig.override.php');
            $this->fail("Should have thrown an exception.");
        } catch (\KS\ConfigFileFormatException $e) {
            $this->assertTrue(true, "This is the correct behavior");
        }
    }

    public function testConfigGetsExecutionProfile() {
        $config = require __DIR__.'/configs/validconfig.php';
        $c = new BaseConfig(__DIR__.'/configs/validconfig.php', __DIR__.'/configs/emptyconfig.php');
        $this->assertEquals($config['exec-profile'], $c->getExecutionProfile(), "Should return the correct execution profile");
    }

    public function testConfigTestsConfigExceptInProduction()
    {
        // First in development
        try {
            $c = new \Test\TestConfig(__DIR__.'/configs/missingconfig.php', __DIR__.'/configs/emptyconfig.php');
            $this->fail("Should have thrown MissingConfigException");
        } catch (\KS\MissingConfigException $e) {
            $this->assertContains("`test2`", $e->getMessage());
        }

        // Now in production
        $c = new \Test\TestConfig(__DIR__.'/configs/missingconfig.prod.php', __DIR__.'/configs/emptyconfig.php');
        $this->assertTrue(true, "Successfully instantiated missing config without errors");
    }

    public function testConfigDumpsConfigCorrectly() {
        $this->markTestIncomplete();
    }

    public function testCanReloadConfig()
    {
        copy(__DIR__.'/configs/validconfig.php', __DIR__.'/configs/tmpconfig.php');
        $c = new \Test\TestConfig(__DIR__.'/configs/tmpconfig.php', __DIR__.'/configs/emptyconfig.php');
        $this->assertEquals('some val', $c->getTest1());
        copy(__DIR__.'/configs/altconfig.php', __DIR__.'/configs/tmpconfig.php');
        $c->reload();
        $this->assertEquals('alt val', $c->getTest1());
        unlink(__DIR__.'/configs/tmpconfig.php');
    }
}
