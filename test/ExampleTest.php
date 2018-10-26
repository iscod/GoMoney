<?php
/**
 * Created by PhpStorm.
 * User: ning
 * Date: 2018/10/25
 * Time: 下午6:53
 */

use PHPUnit\Framework\TestCase;

/**
 * Class StackTest
 */
class ExampleTest extends TestCase
{
    public function testCoreFile()
    {
        $this->assertDirectoryExists(CORE_PATH);
    }

    public function testApp()
    {
        $app = class_exists('GoMoney\App');
        $this->assertNotEmpty($app);
        return new GoMoney\App();
    }

//    /**
//     * @depends testApp
//     * @param object $app
//     */
//    public function testAppIndex(object $app){
//        $string = $app->init();
//        $this->assertStringEndsWith( $string, '</html>');
//    }

    public function testInput()
    {
        $input = class_exists('GoMoney\Input');
        $this->assertNotEmpty($input);
        return new GoMoney\Input();
    }

    /**
     * @depends testInput
     * @throws Exception
     */
    public function testInputArgv()
    {
        $argv = \GoMoney\InPut::argv();
        $this->assertNotEmpty($argv);
        $this->assertLessThan(count($argv), 1);
    }
}