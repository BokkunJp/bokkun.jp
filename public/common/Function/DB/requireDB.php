<?php

// PDOのサンプルコード
/*
   必要な引数：
   dbuser, dbname, dbpass, host(任意、デフォルトはローカル), port(任意)
*/

class DB {
    protected $dsn;
    protected $user;
    protected $hash;

    protected $dbName;
    protected $tableName;
    protected $stmt;
    protected $query;

    public function __construct($dbName='bokkun', $tableName=null, $dbPass, $dbHost = 'localhost', $dbPort=5432) {
        try {
            if (!isset($tableName) && isset($dbName)) {
                $tableName = $dbName;
            }
            $this->tableName = $tableName;
            $this->dbName = $dbName;
            $this->user = $dbName;
            $this->hash = password_hash($dbPass, PASSWORD_DEFAULT);
            $this->dsn = "pgsql:dbname=$this->dbName host=$dbHost port=$dbPort";
            $this->stmt = new PDO($this->dsn, $this->user, $dbPass);
            // print_r('データベースの接続に成功しました。<br/>');
            $this->access = true;
        } catch (PDOException $e) {
            print_r('ERROR!! '.$e->getMessage());
            $this->access = false;
            die();
        }

}

    public function InitSequence($id_name='test_id_seq', $seq_id=1) {
        $this->sql = "select setval ('$id_name', :seq_id, false);";
        $sth = $this->stmt->prepare($this->sql);
        $sth->execute(array(':seq_id' => $seq_id));
        $exec = $sth->fetchAll();

        return $exec;

    }

    private function GetColumn() {
      $this->sql = "select * from {$this->tableName} limit 1";
      $sth = $this->stmt->prepare($this->sql);
      try {
        $sth->execute();

        $ary = $sth->fetchAll();

        foreach ($ary[FIRST] as $_key => $_val) {
          if (is_string($_key)) {
            $column[] = $_key;
          }
        }
      } catch (Exception $e) {
        print_r("ERROR! ". $e->getMessage());
        return false;
      }
      return $column;
    }

    /* Where句の条件をセットする。*/
    private function WhereSet(Array $cond=[], $order='id', $limit=-1) {
      var_Dump(count($cond));
      if (!empty($cond)) {
        $where = 'where ';
        $and = " AND ";
        foreach ($cond as $_key => $value) {
          $where .= $_key. " = ". $value;
          $where .= " AND ";
        }
        $where = substr_replace($where, '', strlen($where) - strlen($and), strlen($and));
      } else {
        $where = '';
      }
      return $where;
    }

    /* カラム名が一致するか検査する。*/
    private function ColmnNameValid($column) {
      $allColumns = $this->GetColumn();
      $ret = false;
      foreach ($allColumns as $_testCol) {
        if ($_testCol === $column) {
          $ret = true;
        }
      }

      return $ret;
    }

    public function Select(Array $cond=[], $order='id', $limit=-1) {

      $where = $this->WhereSet($cond, $order, $limit);    // where句の生成 (プレースホルダと合わせて生成)

      // 生成したwhere句から、プレースホルダの分離

      $this->sql = "select * from {$this->tableName}";

      $condArray = [];
      if (is_string($order)) {
        $this->sql .= " order by :order";
        $condArray[':order'] = $order;
      }

      if ($limit >= 0) {
        $this->sql .= " limit :limit";
        $condArray[':limit'] = $limit;
      }

      $sth = $this->stmt->prepare($this->sql);

      $sth->execute($condArray);

      $ary = $sth->fetchAll();
      // データの成形 (配列形式)
      foreach ($ary as $_key => $_val) {
        $ary[$_key] = $this->MoldData($_val);
      }
      return $ary;
    }

    private function MoldData($data, $NGWord=null) {
      $ret = array();
      foreach ($data as $_key => $_val) {
        if (is_string($_key) && $_key !== $NGWord) {
          $ret[$_key] = $_val;
        }
      }
      return $ret;
    }

    public function SelectAll($order='id', $limit=-1) {
      return $this->Select();
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
            print_r_r('ERROR!! '.$e->getMessage());
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
            print_r_r('ERROR!! '.$e->getMessage());
            $this->stmt->rollback();
            error_reporting(E_STRICT);
        }
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
            print_r('ERROR!! '.$e->getMessage());
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
            print_r('ERROR!! '.$e->getMessage());
            $this->stmt->rollback();
            error_reporting(E_STRICT);
            throw $e;
        }
        $this->InitSequence();
    }
}
