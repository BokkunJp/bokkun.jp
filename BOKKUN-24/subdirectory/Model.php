<?php
IncludeFiles(AddPath(AddPath(PUBLIC_COMMON_DIR, 'Component'), 'DB'));


/**
 * LoadDb
 *
 * @return mixed
 */
function LoadDb($dbName='bokkun_test', $dbPass='bX3Ht6Gk', $tableName='test_db') {
    $dbTest = new DB($dbName, $dbPass);
    $dbTest->SetTable($tableName);

    return $dbTest;
}

/**
 * InputData
 *
 * @param  array $val
 * @return mixed|boolean
 */
function InputData($val) {

    // $dbBokkun = new myPg('bokkun', 'bX3Ht6Gk', 'test_db');

    // $dbBokkunCG = new myPg('bokkun_cg', 'Pp47dNUZ', 'click');
    // $dbBokkunCG = new myPg('bokkun_cg', 'Pp47dNUZ', 'color_alpha');
    // $dbBokkunCG = new myPg('bokkun_cg', 'Pp47dNUZ', 'color_rgb');
    // $dbBokkunCG = new myPg('bokkun_cg', 'Pp47dNUZ', 'position_plane');
    // $dbBokkunCG = new myPg('bokkun_cg', 'Pp47dNUZ', 'position_zaxis');
    // $dbBokkunCG = new myPg('bokkun_cg', 'Pp47dNUZ', 'timestamp');

    // $dbTest = LoadDb();
    $dbTest = LoadDb(dbPass:'bokkun_test');

    $column = ['id', 'contents'];

    $id = $dbTest->SelectDataCount();
    $data = [$id + 1, $val];
    $dbTest->SetSequence($id+1);

    return $dbTest->Insert($column,  $data);
}

/**
 * SelectTable
 *
 * @param  mixed $cond
 * @return void
 */
function SelectTable($cond) {
    // $dbTest = LoadDb();
        $dbTest = LoadDb(dbPass:'bokkun_test');

}

function InitializeTable() {
    // $dbTest = LoadDb();

    $dbTest = LoadDb(dbPass:'bokkun_test');


    $dbTest->SetSequence();

    $dbTest->DeleteAll();
}

/**
 * DeleteTable
 *
 * @param  int $id
 * @return mixed
 */
function DeleteTable($id) {
    // $dbTest = LoadDb();
    $dbTest = LoadDb(dbPass:'bokkun_test');

    $column = ['id'];
    $data = [$id];

    $dbTest->Delete($column, $data);
}