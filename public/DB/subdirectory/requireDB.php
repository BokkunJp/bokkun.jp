<?php

// PDOのサンプルコード

class DB {
    protected $dsn;
    protected $user;
    protected $password;

    protected $dbName;
    protected $tableName;
    protected $stmt;
    protected $query;

    public function __construct($dbName='', $tableName=null, $dbPass, $dbHost = 'localhost', $dbPort=5432) {
        try {
            if (!isset($tableName) && isset($dbName)) {
                $tableName = $dbName;
            }
            $this->tableName = $tableName;
            $this->dbName = $dbName;
            $this->user = $dbName;
            $this->password = $dbPass;
            $this->dsn = "pgsql:dbname=$this->dbName host=$dbHost port=$dbPort";
            $this->stmt = new PDO($this->dsn, $this->user, $this->password);
            // print('データベースの接続に成功しました。<br/>');
            $this->access = true;
        } catch (PDOException $e) {
            print('ERROR!! '.$e->getMessage());
            $this->access = false;
            die();
        }

}

    public function InitSequence($seq_id=1) {
        $this->sql = "select setval ('test_id_seq', :seq_id, false);";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute(array(':seq_id' => $seq_id));
        $exec = $sth->fetchAll();

        return $exec;
        
    }

    public function Insert($val) {

        try {
            $this->stmt->beginTransaction();                             // トランザクション開始
            
            if (empty($val)) {
                $this->stmt->rollback();
                error_reporting(E_STRICT);
            }
            $this->sql = "Insert into {$this->tableName}(val) values(:value)";
            $sth = $this->stmt->prepare($this->sql);
            $sth->execute(array(':value' => $val));
            $this->stmt->commit();                                      // コミット
        } catch (Exception $e) {
            print('ERROR!! '.$e->getMessage());
            $this->stmt->rollback();
            error_reporting(E_STRICT);            
         }
    }

    public function Update($id, $val) {

        try {
            $this->stmt->beginTransaction();                             // トランザクション開始

            if (empty($id) || empty($val)) {
                $this->stmt->rollback();
                error_reporting(E_STRICT);
            }
    
            $this->sql = "update {$this->tableName} set val=:value, updatetime=NOW(), updateday=NOW() where id= :id";
            $sth = $this->stmt->prepare($this->sql);
            $sth->execute(array(':value'=>$val, ':id' => $id));
    
            $this->stmt->commit();                                      // コミット
            
        } catch (Exception $e) {
            print('ERROR!! '.$e->getMessage());
            $this->stmt->rollback();
            error_reporting(E_STRICT);
        }
    }

    public function Select($id) {

        $this->sql = "select * from {$this->tableName} where id= :id";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute(array(':id' => $id));

        $exec = $sth->fetchAll();

        return $exec;
    }

    public function SelectAll() {

        $this->sql = "select * from {$this->tableName} order by id";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute(array());

        $ary = $sth->fetchAll();
        return $ary;
        
    }

    public function SelectIDMin() {
        $this->sql = "select MIN(ID) from {$this->tableName} group by id";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute();

        $ret = $sth->fetch();

        return $ret['min'];
        
    }

    public function SelectIDMax() {
        $this->sql = "select MAX(ID) from {$this->tableName} group by id";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute();

        $ret = $sth->fetch();

        return $ret['max'];
        
    }

    public function Delete($id) {
        try{            
            $this->stmt->beginTransaction();                             // トランザクション開始
            if (empty($id) || !$this->select($id)) {
                $this->stmt->rollback();
                error_reporting(E_STRICT);
                return -1;
            }
            $this->sql = "delete from {$this->tableName} where id= :id";
            $sth = $this->stmt->prepare($this->sql);
            $sth->execute(array(':id' => $id));
            $this->stmt->commit();                                      // コミット            

        } catch (Exception $e) {
            print('ERROR!! '.$e->getMessage());
            $this->stmt->rollback();
            error_reporting(E_STRICT);
            throw $e;
        }
        
    }
    public function DeleteAll() {
        try {  
            $this->stmt->beginTransaction();                             // トランザクション開始
            $this->sql = "delete from {$this->tableName}";
            $sth = $this->stmt->prepare($this->sql);
            $sth->execute();
            $this->stmt->commit();                                      // コミット            
        } catch (Exception $e) {
            print('ERROR!! '.$e->getMessage());
            $this->stmt->rollback();
            error_reporting(E_STRICT);
            throw $e;
        }
        $this->InitSequence();
    }
}
