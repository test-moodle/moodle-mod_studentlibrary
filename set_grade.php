<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Plugin administration pages are defined here.
 *
 * @package     mod_studentlibrary
 * @category    admin
 * @copyright   2025 <plagin@geotar.ru>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Получение данных
require(__DIR__.'/../../config.php');
global $DB, $USER, $CFG;

if (isset($_GET['apikey'])){
    /** 
     * Проверяю что ключ есть в базе
     * I'm checking that the key is in the database.
     * */
    $apikey_exists = $DB->record_exists('studentlibrary_apikey', array('apikey' => $_GET['apikey']));
    if ($apikey_exists){
        $apikey_data = $DB->get_record('studentlibrary_apikey', array('apikey' => $_GET['apikey']), '*', MUST_EXIST);
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