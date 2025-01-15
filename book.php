<?php
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




