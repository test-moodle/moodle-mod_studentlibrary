<?php
// Получение данных
require(__DIR__.'/../../config.php');
global $DB, $USER, $CFG;

if (isset($_GET['apikey'])){
    /** Прверяю что ключ есть в базе */
    $apikey_exists = $DB->record_exists('studentlibrary_apikey', array('apikey' => $_GET['apikey']));
    if ($apikey_exists){
        $apikey_data = $DB->get_record('studentlibrary_apikey', array('apikey' => $_GET['apikey']), '*', MUST_EXIST);
        // print_r($apikey_data);
        $result = new stdClass();
        $result->course=$apikey_data->course;
        $result->module=$apikey_data->module;
        $result->userid=$apikey_data->user;
        $result->total=$_GET['total'];
        $result->score=$_GET['score'];
        $result->report=$_GET['report'];
        $result->modified=time();
        $lastinsertid = $DB->insert_record('studentlibrary_results', $result,false);

        $DB->delete_records('studentlibrary_apikey', array('apikey' => $_GET['apikey']), '*', MUST_EXIST);
        print_r('{"status":"ok"}');
        // header('Location: view.php?id='+'$apikey_data->module');
        $urltogo= $CFG->wwwroot.'/mod/studentlibrary/view.php?id='.$apikey_data->module;
        redirect($urltogo);
    }else{
        $urltogo= $CFG->wwwroot.'/';
        redirect($urltogo);
    }
}else{
    $urltogo= $CFG->wwwroot.'/';
    redirect($urltogo);
}

/*
if(!isset($_GET['mid'])) $mid=0;

$result = new stdClass();
$result->mid=$mid;
$result->login=$_GET['Login'];
$result->module=$_GET['module'];
$result->total=$_GET['total'];
$result->score=$_GET['score'];
$result->report=$_GET['report'];
$result->modified=time();

$lastinsertid = $DB->insert_record('studentlibrary_results', $result,false);
/**
* Получаю ключ из запроса
* Поиск в базе ключа
* Сравнение ключей
* Если ключ найден 
* * то записать в базу пришедшие данные
* * ответить что все хорошо
* Если нет то не ответать
* * ответить что ключ не верен или не актуален
*/