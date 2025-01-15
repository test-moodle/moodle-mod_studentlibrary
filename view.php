<?php
/**
 * Prints an instance of mod_studentlibrary.
 *
 * @package     mod_studentlibrary
 * @copyright   2020 itsup.biz
 */
require(__DIR__.'/../../config.php');
require_once(__DIR__.'/lib.php');
require_once(__DIR__.'/locallib.php');
$id = optional_param('id', 0, PARAM_INT);
$t  = optional_param('t', 0, PARAM_INT);
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
$event->add_record_snapshot('course', $course);
$event->add_record_snapshot('studentlibrary', $moduleinstance);
$event->trigger();
$PAGE->set_url('/mod/studentlibrary/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($moduleinstance->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($modulecontext);
$PAGE->requires->css('/mod/studentlibrary/css/style.css');
echo $OUTPUT->header();
$content=$moduleinstance->intro."<br>";
// $content.='<a href="'.get_lib_url($moduleinstance->booke,$moduleinstance->ised).'" target="_blank" >Нажмите чтобы перейти в учебное пособие</a>';
$content.=get_lib_url($moduleinstance->booke,$moduleinstance->ised);

// print_r(get_lib_url($moduleinstance->booke,$moduleinstance->ised));
echo $OUTPUT->box($content, "generalbox center clearfix");

echo $OUTPUT->footer();
