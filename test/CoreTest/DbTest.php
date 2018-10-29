<?php

namespace Test\CoreTest;

use GoMoney\PDO;
use PHPUnit\Framework\TestCase;

class DbTest extends TestCase
{
    public function testConnect()
    {
        $pdo = PDO::connect('test');
        $this->assertNotEmpty($pdo);
        return $pdo;
    }

    /**
     * @depends testConnect
     * @param PDO $pdo
     * @return PDO
     * @throws \Exception
     * @throws \GoMoney\ErrorException
     */
    public function testCreateTable(PDO $pdo)
    {
        $table = "test";
        $sql = "CREATE TABLE " . $table . " (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, `name` varchar(64) NOT NULL DEFAULT '', PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;";
        $return = $pdo->exec($sql);
        $this->assertNotFalse($return !== FALSE);
        return $pdo::table($table);
    }

    /**
     * @depends testCreateTable
     * @param PDO $pdo
     * @throws \Exception
     */
    public function testInsert(PDO $pdo)
    {
        $id = $pdo->insert(['name' => 'goMoney']);
        $this->assertNotEmpty($id);
    }

    /**
     * @depends testCreateTable
     * @param PDO $pdo
     * @throws \Exception
     */
    public function testDropTable(PDO $pdo)
    {
        $sql = "DROP TABLE test";
        $return = $pdo->exec($sql);
        $this->assertNotFalse($return !== FALSE);
    }
}