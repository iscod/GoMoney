<?php
namespace Test\CoreTest;
//use GoMoney\PDO;
use GoMoney\PDO;
use PHPUnit\Framework\TestCase;

class DbTest extends TestCase
{

    public function testConnect()
    {
        $pdo = PDO::connect();
        $this->assertNotEmpty($pdo);
        return $pdo;
    }
}