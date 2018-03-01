<?php
namespace KS;

class CliConfigTest extends \PHPUnit\Framework\TestCase {
    public function testCanInstantiateWithNothing()
    {
        $config = new \Test\TestCliConfig([]);
        $this->assertInstanceOf("Test\\TestCliConfig", $config);
    }

    public function testCanInstantiateWithConfigArray()
    {
        $config = new \Test\TestCliConfig([[
            'test1' => 'one',
            'test2' => 'two',
        ]]);
        $this->assertEquals("one", $config->getTest1());
        $this->assertEquals("two", $config->getTest2());
        try {
            $config->getTest3();
        } catch (InvalidConfigException $e) {
            $this->assertTrue(true, "This is the expected behavior");
        }
    }

    public function testFullUseCase()
    {
        $config = new \Test\TestCliConfig([
            __DIR__.'/cli-test-configs/config',
            __DIR__.'/cli-test-configs/config.d',
            [
                'test5' => 'six'
            ]
        ]);

        $tests = [
            '1' => 'one',
            '2' => 'two',
            '3' => 'three',
            '4' => 'four',
            '5' => 'six',
        ];

        foreach($tests as $n => $str) {
            $method = "getTest$n";
            $this->assertEquals($str, $config->$method());
        }
    }

    public function testOptionals()
    {
        try {
            $config = new \Test\TestCliConfig(
                [
                    __DIR__.'/cli-test-configs/config',
                    __DIR__.'/cli-test-configs/non-existent',
                ]
            );
        } catch (MissingConfigFileException $e) {
            $this->assertEquals(__DIR__.'/cli-test-configs/non-existent', $e->getMissingPath());
        }

        $config = new \Test\TestCliConfig(
            [
                __DIR__.'/cli-test-configs/config',
                __DIR__.'/cli-test-configs/non-existent',
            ],
            [
                __DIR__.'/cli-test-configs/non-existent',
            ]
        );

        $this->assertTrue(true, "Correct: this shouldn't have thrown an exception");
    }
}

