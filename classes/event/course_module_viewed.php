<?php

/**
 * Plugin event classes are defined here.
 *
 * @package     mod_studentlibrary
 * @copyright   2020 itsup.biz
 */

namespace mod_studentlibrary\event;

defined('MOODLE_INTERNAL') || die();

/**
 * The course_module_viewed event class.
 *
 * @package    mod_test
 * @copyright  2020 itsup.biz
 */
 
class course_module_viewed extends \core\event\course_module_viewed {

    // For more information about the Events API, please visit:
    // https://docs.moodle.org/dev/Event_2
	    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['objecttable'] = 'studentlibrary';
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_PARTICIPATING;
    }

    public static function get_objectid_mapping() {
        return array('db' => 'studentlibrary', 'restore' => 'studentlibrary');
    }

}
