<?php

// PDOのサンプルコード
/*
    必要な引数：
    dbuser, dbname, dbpass, host(任意、デフォルトはローカル), port(任意)
*/
class DB
{
    protected $dsn;
    protected $user;
    protected $hash;

    protected $dbName;
    protected $tableName;
    protected $stmt;
    protected $query;

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
            print_r('ERROR!! ' . $e->getMessage());
            return false;
        }
    }

    public function SetTable($tableName)
    {
        $this->tableName = $tableName;
    }

    public function SetSequence($seq_id = 1, $id_name = 'test_db_id_seq')
    {
        $this->sql = "select setval ('{$id_name}', :{$id_name}, false);";
        $sth = $this->SQLExec($id_name, [$seq_id]);
        $exec = $sth->fetchAll();

        return $exec;
    }

    private function SetPlaceholder(array $colArray, $colFlg = false)
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

    private function SQLExec(string $colString = null, array $valArray = null)
    {
        try {
            $this->stmt->beginTransaction();                             // トランザクション開始

            // カラム文字列からカラム配列を生成
            $colArray = MoldData($colString);

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
            $sess = new PublicSetting\Session();
            $sess->Write("db-system-error", "ERROR!! SQLの実行に失敗しました。");
            $this->stmt->rollback();
            error_reporting(E_STRICT);
            return false;
        }
    }

    public function Insert(array $cols, array $vals)
    {

        // カラム群からそれぞれのカラムのプレースホルダを生成し、それを文字列に成型
        $placeholder = MoldData($this->SetPlaceholder($cols));

        // カラムを文字列に成型
        $cols = MoldData($cols);

        // 実行するSQL
        $this->sql  = "Insert into {$this->tableName}({$cols}) values({$placeholder})";

        // SQL文実行
        return $this->SQLExec($cols, $vals);
    }

    public function Update($cols, $vals)
    {

        // カラムからプレースホルダを生成
        $placeholder = MoldData($this->SetPlaceholder($cols));

        // カラムを成型
        $cols = MoldData($cols);

        // 実行するSQL
        $this->sql  = "Update {$this->tableName} set {$cols}=:col, updatetime=NOW(), updateday=NOW() where id= :id";

        // SQL文実行
        return $this->SQLExec($cols, $vals);
    }

    public function Select($id)
    {
        $this->sql = "Select * from {$this->tableName} where id= :id";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute(array(':id' => $id));

        $exec = $sth->fetchAll();

        return $exec;
    }

    public function newSelect(array $cols, array $vals)
    {

        // カラム群からそれぞれのカラムのプレースホルダを生成し、それを文字列に成型
        $placeholder = MoldData($this->SetPlaceholder($cols));

        // カラムを文字列に成型
        $cols = MoldData($cols);

        // 実行するSQL
        $this->sql  = "Select {$cols}=:col From {$this->tableName}";

        // SQL文実行
        return $this->SQLExec($cols, $vals);
    }

    public function SelectAll()
    {
        $this->sql = "select * from {$this->tableName} order by id";
        $sth = $this->SQLExec();

        $ary = $sth->fetchAll();
        return $ary;
    }

    public function SelectIDMin()
    {
        $this->sql = "select MIN(ID) from {$this->tableName}";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute();

        $ret = $sth->fetch();

        return $ret['min'];
    }

    public function SelectIDMax()
    {
        $this->sql = "select MAX(ID) from {$this->tableName}";
        $sth = $this->SQLExec();

        $ret = $sth->fetch();

        if (empty($ret)) {
            return 1;
        }

        return $ret['max'];
    }

    public function SelectDataCount()
    {
        $this->sql = "select COUNT(*) from {$this->tableName}";
        $sth = $this->SQLExec();

        if ($sth === false) {
            return false;
        }

        $ret = $sth->fetch();

        if (empty($ret)) {
            return 1;
        }

        return $ret['count'];
    }

    public function Delete(array $cols, array $vals)
    {

        // where句生成
        $where = MoldData($this->SetPlaceholder($cols, true));

        // カラムを成型
        $cols = MoldData($cols);


        // 実行するSQL
        $this->sql  = "Delete from {$this->tableName} where {$where}";

        // SQL文実行
        return $this->SQLExec($cols, $vals);
    }

    public function DeleteAll()
    {
        $this->sql  = "Delete From {$this->tableName}";

        $this->SQLExec();
    }
}
