<?php

/**
 * Display information about all the mod_studentlibrary modules in the requested course.
 *
 * @package     mod_studentlibrary
 * @copyright   2020 itsup.biz
 */

require(__DIR__.'/../../config.php');

require_once(__DIR__.'/lib.php');

$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', array('id' => $id), '*', MUST_EXIST);
require_course_login($course);

$coursecontext = context_course::instance($course->id);

$event = \mod_studentlibrary\event\course_module_instance_list_viewed::create(array(
    'context' => $modulecontext
));
$event->add_record_snapshot('course', $course);
$event->trigger();

$PAGE->set_url('/mod/studentlibrary/index.php', array('id' => $id));
$PAGE->set_title(format_string($course->fullname));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($coursecontext);

echo $OUTPUT->header();

$modulenameplural = get_string('modulenameplural', 'mod_studentlibrary');
echo $OUTPUT->heading($modulenameplural);

$studentlibrarys = get_all_instances_in_course('studentlibrary', $course);

if (empty($studentlibrarys)) {
    notice(get_string('nonewmodules', 'mod_studentlibrary'), new moodle_url('/course/view.php', array('id' => $course->id)));
}

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($course->format == 'weeks') {
    $table->head  = array(get_string('week'), get_string('name'));
    $table->align = array('center', 'left');
} else if ($course->format == 'topics') {
    $table->head  = array(get_string('topic'), get_string('name'));
    $table->align = array('center', 'left', 'left', 'left');
} else {
    $table->head  = array(get_string('name'));
    $table->align = array('left', 'left', 'left');
}

foreach ($studentlibrarys as $studentlibrary) {
    if (!$studentlibrary->visible) {
        $link = html_writer::link(
            new moodle_url('/mod/studentlibrary/view.php', array('id' => $studentlibrary->coursemodule)),
            format_string($studentlibrary->name, true),
            array('class' => 'dimmed'));
    } else {
        $link = html_writer::link(
            new moodle_url('/mod/studentlibrary/view.php', array('id' => $studentlibrary->coursemodule)),
            format_string($studentlibrary->name, true));
    }

    if ($course->format == 'weeks' or $course->format == 'topics') {
        $table->data[] = array($studentlibrary->section, $link);
    } else {
        $table->data[] = array($link);
    }
}

echo html_writer::table($table);
echo $OUTPUT->footer();
