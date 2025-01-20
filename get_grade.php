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

require(__DIR__ . '/../../config.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');
global $DB, $USER, $OUTPUT, $PAGE;
$id = optional_param('id', 0, PARAM_INT);
$t  = optional_param('t', 0, PARAM_INT);
$rev = optional_param('rev', 0, PARAM_INT);
if ($id) {
    $cm             = get_coursemodule_from_id('studentlibrary', $id, 0, false, MUST_EXIST);
    $course         = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $moduleinstance = $DB->get_record('studentlibrary', array('id' => $cm->instance), '*', MUST_EXIST);
} else if ($t) {
    $moduleinstance = $DB->get_record('studentlibrary', array('id' => $n), '*', MUST_EXIST);
    $course         = $DB->get_record('course', array('id' => $moduleinstance->course), '*', MUST_EXIST);
    $cm             = get_coursemodule_from_instance('studentlibrary', $moduleinstance->id, $course->id, false, MUST_EXIST);
} else {
    print_error(get_string('missingidandcmid', 'mod_studentlibrary'));
}
require_login($course, true, $cm);
$modulecontext = context_module::instance($cm->id);
$event = \mod_studentlibrary\event\course_module_viewed::create(array(
    'objectid' => $moduleinstance->id,
    'context' => $modulecontext
));
$PAGE->set_pagelayout('base');
$PAGE->set_title($course->shortname . ': ' . $strurls);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($course->fullname, "/course/view.php?id=" . $course->id);
$PAGE->navbar->add($cm->name, "/mod/studentlibrary/view.php?id=" . $id);
$PAGE->requires->css('/mod/studentlibrary/css/style.css');
$context = context_course::instance($course->id);
echo $OUTPUT->header();
if ($rev == 1 && has_capability('moodle/course:update', $context, $USER->id)) {
    echo $OUTPUT->heading(get_string('studentlibrary:vedomost_all', 'mod_studentlibrary'));
    $userid = $USER->id;
    $moduleid = required_param('id', PARAM_INT);
    $activity_modules = $DB->get_record('course_modules', array('id' => $moduleid));
    $studentlibrary_results = $DB->get_records('studentlibrary_results', array('course' => $activity_modules->course, 'module' => $moduleid));
    if (count($studentlibrary_results) > 0) {
        $r = '<div class="table_rez"><table class="table table-bordered table-sm" id="tbl">';
        $r .= "<thead><th>".get_string('studentlibrary:t_user', 'mod_studentlibrary');
        $r .= "</th><th>".get_string('studentlibrary:t_quests_all', 'mod_studentlibrary');
        $r .= "</th><th>".get_string('studentlibrary:t_quests_true', 'mod_studentlibrary');
        $r .= "</th><th>".get_string('studentlibrary:t_url', 'mod_studentlibrary');
        $r .= "</th><th>".get_string('studentlibrary:t_date', 'mod_studentlibrary')."</th></thead><tbody>";
        foreach ($studentlibrary_results as $studentlibrary_result) {
            $user = $DB->get_record('user', array('id' => $studentlibrary_result->userid));
            $uu = '<td>' . $user->lastname . ' ' . $user->firstname . ' ' . $user->middlename . '</td>';
            $r .= "<tr>$uu<td>" . $studentlibrary_result->total . '</td><td>' . $studentlibrary_result->score  . '</td><td><a target="_blank" href="' . $studentlibrary_result->report  . '">'.get_string('studentlibrary:t_progress_report', 'mod_studentlibrary').'</a></td><td>' . date("Y-m-d H:i:s", $studentlibrary_result->modified) . '</td>';
        }
        $r .= "</tbody></table></div>";
        echo ($r);
    } else {
        echo get_string('studentlibrary:not_data', 'mod_studentlibrary');
    }
} else {
    echo $OUTPUT->heading(get_string('studentlibrary:vedomost_my', 'mod_studentlibrary'));
    $userid = $USER->id;
    $moduleid = required_param('id', PARAM_INT);
    $activity_modules = $DB->get_record('course_modules', array('id' => $moduleid));
    $studentlibrary_results = $DB->get_records('studentlibrary_results', array('course' => $activity_modules->course, 'module' => $moduleid, 'userid' => $userid));
    if (count($studentlibrary_results) > 0) {
        $r = '<div class="table_rez"><table class="table table-bordered table-sm" id="tbl">';
        $r .= "<thead><th>".get_string('studentlibrary:t_user', 'mod_studentlibrary');
        $r .= "</th><th>".get_string('studentlibrary:t_quests_all', 'mod_studentlibrary');
        $r .= "</th><th>".get_string('studentlibrary:t_quests_true', 'mod_studentlibrary');
        $r .= "</th><th>".get_string('studentlibrary:t_url', 'mod_studentlibrary');
        $r .= "</th><th>".get_string('studentlibrary:t_date', 'mod_studentlibrary')."</th></thead><tbody>";
        foreach ($studentlibrary_results as $studentlibrary_result) {
            $user = $DB->get_record('user', array('id' => $studentlibrary_result->userid));
            $uu = '<td>' . $user->lastname . ' ' . $user->firstname . ' ' . $user->middlename . '</td>';
            $r .= "<tr>$uu<td>" . $studentlibrary_result->total . '</td><td>' . $studentlibrary_result->score  . '</td><td><a target="_blank" href="' . $studentlibrary_result->report  . '">'.get_string('studentlibrary:t_progress_report', 'mod_studentlibrary').'</a></td><td>' . date("Y-m-d H:i:s", $studentlibrary_result->modified) . '</td>';
        }
        $r .= "</tbody></table></div>";
        echo ($r);
    } else {
        echo get_string('studentlibrary:not_data', 'mod_studentlibrary');
    }
}
echo $OUTPUT->footer();