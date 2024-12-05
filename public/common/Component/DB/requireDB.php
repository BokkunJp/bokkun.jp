<?php

// PDOのサンプルコード
/*
    必要な引数：
    dbuser, dbname, dbpass, host(任意、デフォルトはローカル), port(任意)
*/
class DB
{
    protected $sql;
    protected $dsn;
    protected $user;
    protected $hash;

    protected $dbName;
    protected $tableName;
    protected $stmt;
    protected $query;

    use CommonTrait;

    public function __construct($dbName = 'bokkun', $dbPass = null, $dbHost = 'localhost', $dbPort = 5432)
    {
        try {
            $this->dbName = $dbName;
            $this->user = $dbName;
            $this->hash = password_hash($dbPass, PASSWORD_DEFAULT);
            $this->dsn = "pgsql:dbname={$this->dbName} host={$dbHost} port={$dbPort}";
            $this->stmt = new PDO($this->dsn, $this->user, $dbPass);
            // print_r('データベースの接続に成功しました。<br/>');
            return true;
        } catch (PDOException $e) {
            print_r("データベースの接続に失敗しました。");
            return false;
        }
    }

    public function setTable($tableName)
    {
        $this->tableName = $tableName;
    }

    public function setSequence($seq_id = 1, $id_name = 'test_db_id_seq')
    {
        $this->sql = "select setval ('{$id_name}', :{$id_name}, false);";
        $sth = $this->execQuery($id_name, [$seq_id]);
        $exec = $sth->fetchAll();

        return $exec;
    }

    private function setPlaceholder(array $colArray, $colFlg = false)
    {
        $newArray = [];

        foreach ($colArray as $_key => $_val) {
            $newArray[$_key] = "";
            if ($colFlg) {
                $newArray[$_key] .= $_val . "=";
            }
            $newArray[$_key] .= ":" . $_val;
        }

        return $newArray;
    }

    private function execQuery(string $colString = null, array $valArray = null)
    {
        try {
            $this->stmt->beginTransaction();                             // トランザクション開始

            // カラム文字列からカラム配列を生成
            $colArray = $this->moldData($colString);

            // SQL文をプリペア
            $sth = $this->stmt->prepare($this->sql);

            // 変数をバインド (SQL文にカラムを指定している場合)
            if ($colArray !== false) {
                foreach ($colArray as $_key => $_value) {
                    $sth->bindValue($colArray[$_key], $valArray[$_key]);
                }
            }

            // SQLの実行
            $sth->execute();
            $this->stmt->commit();                                      // コミット

            // ステートメントを返却
            return $sth;
        } catch (Exception $e) {
            // SQLの実行に失敗した場合はエラー
            print_r('ERRORの内容: ' . $e->getMessage());
            $session = new Public\Important\Session('db');
            $session->write("db-system-error", "ERROR!! SQLの実行に失敗しました。");
            $this->stmt->rollback();
            error_reporting(E_STRICT);
            return false;
        }
    }

    public function insert(array $cols, array $vals)
    {

        // カラム群からそれぞれのカラムのプレースホルダを生成し、それを文字列に成型
        $placeholder = $this->moldData($this->setSequence($cols));

        // カラムを文字列に成型
        $cols = $this->moldData($cols);

        // 実行するSQL
        $this->sql  = "insert into {$this->tableName}({$cols}) values({$placeholder})";

        // SQL文実行
        return $this->execQuery($cols, $vals);
    }

    public function Update($cols, $vals)
    {

        // カラムからプレースホルダを生成
        $placeholder = $this->moldData($this->setSequence($cols));

        // カラムを成型
        $cols = $this->moldData($cols);

        // 実行するSQL
        $this->sql  = "Update {$this->tableName} set {$cols}=:col, updatetime=NOW(), updateday=NOW() where id= :id";

        // SQL文実行
        return $this->execQuery($cols, $vals);
    }

    public function select($id)
    {
        $this->sql = "select * from {$this->tableName} where id= :id";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute(array(':id' => $id));

        $exec = $sth->fetchAll();

        return $exec;
    }

    public function selectNew(array $cols, array $vals)
    {

        // カラム群からそれぞれのカラムのプレースホルダを生成し、それを文字列に成型
        $placeholder = $this->moldData($this->setSequence($cols));

        // カラムを文字列に成型
        $cols = $this->moldData($cols);

        // 実行するSQL
        $this->sql  = "select {$cols}=:col From {$this->tableName}";

        // SQL文実行
        return $this->execQuery($cols, $vals);
    }

    public function selectAll()
    {
        $this->sql = "select * from {$this->tableName} order by id";
        $sth = $this->execQuery();

        $ary = $sth->fetchAll();
        return $ary;
    }

    public function selectMinId()
    {
        $this->sql = "select MIN(ID) from {$this->tableName}";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute();

        $ret = $sth->fetch();

        return $ret['min'];
    }

    public function selectMaxId()
    {
        $this->sql = "select MAX(ID) from {$this->tableName}";
        $sth = $this->execQuery();

        $ret = $sth->fetch();

        if (empty($ret)) {
            return 1;
        }

        return $ret['max'];
    }

    public function selectDataCount()
    {
        $this->sql = "select COUNT(*) from {$this->tableName}";
        $sth = $this->execQuery();

        if ($sth === false) {
            return false;
        }

        $ret = $sth->fetch();

        if (empty($ret)) {
            return 1;
        }

        return $ret['count'];
    }

    public function delete(array $cols, array $vals)
    {

        // where句生成
        $where = $this->moldData($this->setSequence($cols, true));

        // カラムを成型
        $cols = $this->moldData($cols);


        // 実行するSQL
        $this->sql  = "Delete from {$this->tableName} where {$where}";

        // SQL文実行
        return $this->execQuery($cols, $vals);
    }

    public function DeleteAll()
    {
        $this->sql  = "Delete From {$this->tableName}";

        $this->execQuery();
    }
}
