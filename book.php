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

require_once(__DIR__.'/../../config.php');require_login(); 
global $DB, $OUTPUT, $PAGE;

$PAGE->set_pagelayout('base');
$PAGE->set_url('/mod/studentlibrary/book.php');

$PAGE->set_title("Список доступных книг");

echo $OUTPUT->header();

echo $OUTPUT->heading("Список доступных книг ЭБС");
if (is_siteadmin()) {
	$r='<table class="table table-bordered table-sm" id="tbl">';
	$books=$DB->get_records('studentlibrary_cat');
 foreach ($books as $book)
 {
	$r.='<tr><td>'.$book->book.'</td><td>'.$book->title.'</td></tr>'; 
 }
$r.="</tbody></table>";
}
else $r='<div class="alert alert-danger" role="alert">
 НЕТ ПРАВ ДЛЯ ПРОСМОТРА!
</div>';
echo $OUTPUT->box($r, "generalbox center clearfix");

echo $OUTPUT->footer();




