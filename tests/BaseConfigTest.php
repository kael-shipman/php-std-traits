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

        $c = new BaseConfig(__DIR__.'/configs/validconfig.php', __DIR__.'/configs/validconfig.override.php');
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
        $c = new BaseConfig(__DIR__.'/configs/validconfig.php', __DIR__.'/configs/validconfig.override.php');
        $this->assertEquals($config['exec-profile'], $c->getExecutionProfile(), "Should return the correct execution profile");
    }

    public function testConfigTestsConfigWhenNotInProduction() {
    }

    public function testConfigDumpsConfigCorrectly() {
    }
}
