<?php

namespace Test\CoreTest;

use GoMoney\DB;

use GoMoney\DB_driver\PDO;
use PHPUnit\Framework\TestCase;

class DbPdoTest extends TestCase
{
    /**
     * @return PDO
     * @throws \Exception
     */
    public function testConnect()
    {
        $db = DB::connect('test');
        $this->assertNotEmpty($db);
        return $db;
    }

    /**
 * @depends testConnect
 * @param PDO $db
 * @return PDO
 * @throws \Exception
 */
    public function testCreateTable(PDO $db)
    {
        $table = "test";
        $sql = "CREATE TABLE " . $table . " (`id` int(11) unsigned NOT NULL AUTO_INCREMENT, `n` varchar(64) NOT NULL DEFAULT '', PRIMARY KEY (`id`)) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4;";
        $return = $db->exec($sql);
        $this->assertNotFalse($return !== FALSE);
        return $db;
    }

    /**
     * @depends testCreateTable
     * @param PDO $db
     * @return PDO
     * @throws \GoMoney\ErrorException
     */
    public function testInsert(PDO $db)
    {
        $id = $db->table('test')->insert(['n' => '1']);
        $this->assertNotEmpty($id);
        return $db;
    }

    /**
     * @depends  testInsert
     * @param PDO $db
     * @throws \GoMoney\ErrorException
     */
    public function testWhere(PDO $db) {
        $return = $db->where(['n' => '1'])->get();
        $this->assertNotEmpty($return);
    }

    /**
     * @depends testConnect
     * @param PDO $db
     * @throws \Exception
     */
    public function testDropTable(PDO $db)
    {
        $sql = "DROP TABLE test";
        $return = $db->exec($sql);
        $this->assertNotFalse($return !== FALSE);
    }
}