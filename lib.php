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

defined('MOODLE_INTERNAL') || die();

/**
 * Return if the plugin supports $feature.
 *
 * @param string $feature Constant representing the feature.
 * @return true | null True if the feature is supported, null otherwise.
 */
function studentlibrary_supports($feature) {
    switch ($feature) {
        case FEATURE_GRADE_HAS_GRADE:
            return true;
        case FEATURE_MOD_INTRO:
            return true;
        default:
            return null;
    }
}

/**
 * Saves a new instance of the mod_studentlibrary into the database.
 *
 * Given an object containing all the necessary data, (defined by the form
 * in mod_form.php) this function will create a new instance and return the id
 * number of the instance.
 *
 * @param object $moduleinstance An object from the form.
 * @param mod_studentlibrary_mod_form $mform The form.
 * @return int The id of the newly inserted record.
 */
function studentlibrary_add_instance($moduleinstance, $mform = null) {
    global $DB;
    $moduleinstance->timecreated = time();
    $id = $DB->insert_record('studentlibrary', $moduleinstance);
    return $id;
}

/**
 * Updates an instance of the mod_studentlibrary in the database.
 *
 * Given an object containing all the necessary data (defined in mod_form.php),
 * this function will update an existing instance with new data.
 *
 * @param object $moduleinstance An object from the form in mod_form.php.
 * @param mod_studentlibrary_mod_form $mform The form.
 * @return bool True if successful, false otherwise.
 */
function studentlibrary_update_instance($moduleinstance, $mform = null) {
    global $DB;
    $moduleinstance->timemodified = time();
    $moduleinstance->id = $moduleinstance->instance;
    return $DB->update_record('studentlibrary', $moduleinstance);
}

/**
 * Removes an instance of the mod_studentlibrary from the database.
 *
 * @param int $id Id of the module instance.
 * @return bool True if successful, false on failure.
 */
function studentlibrary_delete_instance($id) {
    global $DB;
    $exists = $DB->get_record('studentlibrary', ['id' => $id]);
    if (!$exists) {
        return false;
    }
    $DB->delete_records('studentlibrary', ['id' => $id]);
    return true;
}

/**
 * Is a given scale used by the instance of mod_studentlibrary?
 *
 * This function returns if a scale is being used by one mod_studentlibrary
 * if it has support for grading and scales.
 *
 * @param int $moduleinstanceid ID of an instance of this module.
 * @param int $scaleid ID of the scale.
 * @return bool True if the scale is used by the given mod_studentlibrary instance.
 */
function studentlibrary_scale_used($moduleinstanceid, $scaleid) {
    global $DB;
    if ($scaleid && $DB->record_exists('studentlibrary', ['id' => $moduleinstanceid, 'grade' => -$scaleid])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Checks if scale is being used by any instance of mod_studentlibrary.
 *
 * This is used to find out if scale used anywhere.
 *
 * @param int $scaleid ID of the scale.
 * @return bool True if the scale is used by any mod_studentlibrary instance.
 */
function studentlibrary_scale_used_anywhere($scaleid) {
    global $DB;
    if ($scaleid && $DB->record_exists('studentlibrary', ['grade' => -$scaleid])) {
        return true;
    } else {
        return false;
    }
}

/**
 * Creates or updates grade item for the given mod_studentlibrary instance.
 *
 * Needed by {@see grade_update_mod_grades()}.
 *
 * @param stdClass $moduleinstance Instance object with extra cmidnumber and modname property.
 * @param bool $reset Reset grades in the gradebook.
 * @return void.
 */
function studentlibrary_grade_item_update($moduleinstance, $reset = false) {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');
    $item = [];
    $item['itemname'] = clean_param($moduleinstance->name, PARAM_NOTAGS);
    $item['gradetype'] = GRADE_TYPE_VALUE;
    if ($moduleinstance->grade > 0) {
        $item['gradetype'] = GRADE_TYPE_VALUE;
        $item['grademax']  = $moduleinstance->grade;
        $item['grademin']  = 0;
    } else if ($moduleinstance->grade < 0) {
        $item['gradetype'] = GRADE_TYPE_SCALE;
        $item['scaleid']   = $moduleinstance->grade;
    } else {
        $item['gradetype'] = GRADE_TYPE_NONE;
    }
    if ($reset) {
        $item['reset'] = true;
    }
    grade_update('mod/studentlibrary', $moduleinstance->course, 'mod', 'studentlibrary', $moduleinstance->id, 0, null, $item);
}

/**
 * Delete grade item for given mod_studentlibrary instance.
 *
 * @param stdClass $moduleinstance Instance object.
 * @return grade_item.
 */
function studentlibrary_grade_item_delete($moduleinstance) {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');
    return grade_update(
        'mod/studentlibrary',
        $moduleinstance->course,
        'mod',
        'studentlibrary',
        $moduleinstance->id,
        0,
        null,
        ['deleted' => 1],
    );
}

/**
 * Update mod_studentlibrary grades in the gradebook.
 *
 * Needed by {@see grade_update_mod_grades()}.
 *
 * @param stdClass $moduleinstance Instance object with extra cmidnumber and modname property.
 * @param int $userid Update grade of specific user only, 0 means all participants.
 */
function studentlibrary_update_grades($moduleinstance, $userid = 0) {
    global $CFG;
    require_once($CFG->libdir . '/gradelib.php');
    $grades = [];
    grade_update('mod/studentlibrary', $moduleinstance->course, 'mod', 'studentlibrary', $moduleinstance->id, 0, $grades);
}
/**
 * Extend_settings_navigation.
 *
 * Needed by {@see studentlibrary_extend_settings_navigation()}.
 *
 * @param stdClass $settingsnav .
 * @param int $stnode .
 */
function studentlibrary_extend_settings_navigation($settingsnav,  $stnode) {
    global $USER, $PAGE, $DB;
    $course = $DB->get_record('course', ['id' => $PAGE->cm->course], '*', MUST_EXIST);
    $context = context_course::instance($course->id);
    if (has_capability('moodle/course:update', $context, $USER->id)) {
        $stnode->add(
            get_string('studentlibrary:vedomost_all', 'mod_studentlibrary'),
            '/mod/studentlibrary/get_grade.php?id=' . $PAGE->cm->id . '&rev=1'
        );
    } else {
        $stnode->add(
            get_string('studentlibrary:vedomost_my', 'mod_studentlibrary'),
            '/mod/studentlibrary/get_grade.php?id=' . $PAGE->cm->id
        );
    }
}

/**
 * Function studentlibrary_file
 */
function studentlibrary_file() {
    require_once(__DIR__ . '/../../config.php');
    global $DB, $CFG;
    require_once("$CFG->libdir/phpspreadsheet/vendor/autoload.php");
    $ff = $DB->get_record('config', ['name' => 'studentlibraryfile'])->value;
    $fs = get_file_storage();
    $fileinfo = [
        'component' => 'core',
        'filearea' => 'ebslist',
        'itemid' => 0,
        'contextid' => 1,
        'filepath' => '/',
        'filename' => $ff,
    ];

    $file = $fs->get_file(
        $fileinfo['contextid'],
        $fileinfo['component'],
        $fileinfo['filearea'],
        $fileinfo['itemid'],
        $fileinfo['filepath'],
        $fileinfo['filename'],
    );

    if ($file) {
        $fn = $CFG->dataroot . '/temp/' . $ff;
        unlink($fn);
        $file->copy_content_to($fn);
        if (file_exists($fn) && filesize($fn)) {
            switch (pathinfo($fn)['extension']) {
                case "xls":
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
                    break;
                case "xlsx":
                    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                    break;
                default:
                    return;
                    break;
            }
            $spreadsheet = $reader->load($fn);
            $worksheet = $spreadsheet->getActiveSheet();

            $hr = $worksheet->getHighestRow();
            $highestcolumn = $worksheet->gethighestcolumn();
            $hi = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestcolumn);
            for ($col = 1; $col <= $hi; ++$col) {
                $value = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                switch ($value) {
                    case "book":
                        $i0 = $col;
                        break;
                    case "title":
                        $i1 = $col;
                        break;
                }
            }
            $DB->delete_records('studentlibrary_cat');
            $books = [];
            for ($row = 2; $row <= $hr; ++$row) {
                $r = new stdClass();
                if (strlen($worksheet->getCellByColumnAndRow($i0, $row)->getValue()) < 2 || strlen($worksheet->getCellByColumnAndRow($i1, $row)->getValue()) < 1) {
                    continue;
                }
                $r->book = $worksheet->getCellByColumnAndRow($i0, $row)->getValue();
                $r->title = mb_substr($worksheet->getCellByColumnAndRow($i1, $row)->getValue(), 0, 254);
                $books[] = $r;
            }
            if (!empty($books)) {
                $DB->insert_records('studentlibrary_cat', $books);
            }
        }
    }
    return;
}

/**
 * Get mod config.
 *
 * @param string $name .
 * @return string.
 */
function get_mod_config($name) {
    $plugin = new \stdClass();
    require(__DIR__ . '/version.php');
    return $plugin->$name;
}
