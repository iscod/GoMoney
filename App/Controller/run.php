<?php
// namespace App\Controller;
date_default_timezone_set('Asia/Shanghai');

include_once __DIR__ . '/../Helper/ApiHandler.php';
include_once __DIR__ . '/../Helper/XueApiHandler.php';
//include_once __DIR__ . '/../../Core/Common.php';

use App\Helper\XueApiHandler;

/**
 *
 */
class Collect
{

    function __construct()
    {
        # code...
    }

    function handle()
    {
        $this->commit('SZ000651');
    }

    function commit(string $symbol = "SZ000651", int $page = 1, int $count = 10)
    {
        $url = "https://xueqiu.com/statuses/search.json";
        $param = ["count" => $count, "comment" => "0", "symbol" => $symbol, "hl" => "0", "source" => "all", "sort" => "", "page" => $page, "q" => ""];
        $body = XueApiHandler::get($url, $param, __DIR__ . '/../Helper/cacert.pem');
        $json = json_decode($body, true);
        var_dump($json);
        $list = $json['list'];
//        var_dump($list);


        foreach ($list as $key => $value) {
            $is_exists = where(['comment_id' => $value['id'], 'user_id' => $value['user_id']]);

            if (!$is_exists) {
                $comment_filename = date('YmdHis') . md5(date('YmdHis') . $value['id']) . $value['id'] . '.json';
                $install = [
                    'comment_id' => $value['id'],
                    'user_id' => $value['user_id'] ?? 0,
                    'symbol_id' => $value['symbol_id'] ?? '',
                    'title' => $value['title'] ?? '',
                    'description' => $value['description'],
                    'comment_filename' => $comment_filename,
                    'comment_at' => date('Y-m-d H:i:s', (int)($value['created_at'] / 1000)),
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ];

                $dir = __DIR__ . '/' . date('Y-m-d') . '/';
                gm_file_put_contents($dir . '/' . $comment_filename, json_encode($value), FILE_APPEND);

                $id = install($install);

                if ($id) {
                    echo "[→]Install ok\n", "[→]Id: ", $value['id'], "\n", "[→]Description: ", $value['description'], "\n";
                }
            }
        }
    }
}

function field_exists($table_name)
{

}


function result_array()
{
    if (count($this->result_array) > 0) {
        return $this->result_array;
    }

    // In the event that query caching is on, the result_id variable
    // will not be a valid resource so we'll simply return an empty
    // array.
    if (!$this->result_id OR $this->num_rows === 0) {
        return array();
    }

    if (($c = count($this->result_object)) > 0) {
        for ($i = 0; $i < $c; $i++) {
            $this->result_array[$i] = (array)$this->result_object[$i];
        }

        return $this->result_array;
    }

    is_null($this->row_data) OR $this->data_seek(0);
    while ($row = $this->_fetch_assoc()) {
        $this->result_array[] = $row;
    }

    return $this->result_array;
}

function result_object()
{
    if (count($this->result_object) > 0) {
        return $this->result_object;
    }

    if (!$this->result_id OR $this->num_rows === 0) {
        return array();
    }

    if (($c = count($this->result_array)) > 0) {
        for ($i = 0; $i < $c; $i++) {
            $this->result_object[$i] = (object)$this->result_array[$i];
        }

        return $this->result_object;
    }

    is_null($this->row_data) OR $this->data_seek(0);
    while ($row = $this->_fetch_object()) {
        $this->result_object[] = $row;
    }

    return $this->result_object;
}

function where($data, $fetch_style = PDO::FETCH_BOTH)
{
    $keys = array_keys($data);
    $fields = '`' . implode('` =? AND `', $keys) . '` =?';
    $sql = 'select * from comment where ' . $fields;
    try {
        $dbh = new PDO('mysql:host=127.0.0.1;dbname=money', 'root', 'ntl41891', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
        $tmt = $dbh->prepare($sql);
        $return = $tmt->execute(array_values($data));

        if (!$return) {
            throw new PDOExecption("Error Processing Request" . json_encode($tmt->errorInfo()), $tmt->errorCode());
        }
        $row = $tmt->fetchAll($fetch_style);
        return $row;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "</br>";
    }
}

function install($data)
{
    $keys = array_keys($data);
    //拼接插入行key
    $fields = '`' . implode('`, `', $keys) . '`';
    $placeholder = trim((str_repeat('?,', count($keys))), ',');
    $dbh = new PDO('mysql:host=127.0.0.1;dbname=money', 'root', 'ntl41891', array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES UTF8'));
    $sql = 'INSERT INTO comment ( ' . $fields . ' ) VALUES (' . $placeholder . ')';
    $tmt = $dbh->prepare('INSERT INTO comment ( ' . $fields . ' ) VALUES (' . $placeholder . ')');

    try {
        $dbh->beginTransaction();
        $return = $tmt->execute(array_values($data));
        $id = $dbh->lastInsertId();
        $dbh->commit();

        if (!$return) {
            throw new PDOExecption("Error Processing Request" . json_encode($tmt->errorInfo()), $tmt->errorCode());
        }

        return $id;
    } catch (PDOExecption $e) {
        $dbh->rollback();
        print "Error!: " . $e->getMessage() . "</br>";
    }
}


$a = new Collect();
$a->handle();

echo "→ → START SEARCH...\n";
$i = 1;
while (true) {
    sleep('5');
    echo '.';
    $a->handle();
}
echo "→ → END SEARCH...\n";




