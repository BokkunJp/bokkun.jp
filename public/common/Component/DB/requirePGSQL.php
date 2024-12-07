<?php

class myPg
{
    private $sql;
    private $tableName;
    private $dbName;
    private $dbUser;
    private $pg_dsn;
    private $pg_con;

    use CommonTrait;

    public function __construct($dbName='bokkun', $dbPass = null, $tableName=null, $dbHost = 'localhost', $dbPort=5432)
    {
        try {
            $this->dbName = $dbName;
            $this->pg_dsn = "dbname={$this->dbName} user={$this->dbName} host={$dbHost} port={$dbPort} password={$dbPass}";
            $this->pg_con = pg_pconnect($this->pg_dsn);
            // $this->stmt = new PDO($this->dsn, $this->user, $dbPass);
            // print_r('データベースの接続に成功しました。<br/>');
            return true;
        } catch (Exception $e) {
            print_r('ERROR!! '.$e->getMessage());
            return false;
        }
    }

    public function setTable($tableName)
    {
        $this->tableName = $tableName;
    }

    private function setSequence(array $colArray)
    {
        $newArray = [];

        foreach ($colArray as $_key => $_val) {
            $newArray[$_key] = ":". $_val;
        }

        return $newArray;
    }

    private function execQuery($col, $val, $sql)
    {
        try {
            pg_query($this->pg_con, 'BEGIN;');                             // トランザクション開始

            // データがない場合は終了
            if (is_null($val)) {
                pg_query($this->pg_con, 'ROLLBACK;');
                error_reporting(E_STRICT);
            }

            // カラムからプレースホルダを生成
            $placeholder = $this->moldData($col);


            $this->sql = $sql;

            if (pg_connection_busy($this->pg_con)) {
                pg_send_prepare($this->pg_con, 'pg_query', $this->sql);
                foreach ($placeholder as $_key => $_value) {
                    // $sth->bindValue($placeholder[$_key], $val[$_key]);
                }
                // $sth->execute();
            }
            pg_query($this->pg_con, 'COMMIT;');                                      // コミット
        } catch (Exception $e) {
            print_r('INSERT ERROR!! : '.$e->getMessage());
            pg_query($this->pg_con, 'ROLLBACK;');
            error_reporting(E_STRICT);
        }
    }

    public function insert($cols, $vals)
    {
        // カラムからプレースホルダを生成
        $placeholder = $this->moldData($this->setSequence($cols));

        // カラムを成型
        $cols = $this->moldData($cols);

        $sql  = "insert into {$this->tableName}({$cols}) values({$placeholder})";

        $this->execQuery($cols, $vals, $sql);
    }

    public function __destruct()
    {
        pg_close($this->pg_con);
    }
}
