<?php

/**
* The main mod_studentlibrary configuration form.
*
* @package     mod_studentlibrary
* @copyright   2020 itsup.biz
*/

use mod_forum\local\factories\vault;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/course/moodleform_mod.php');
require_once(__DIR__ . '/lib.php');
require_once(__DIR__ . '/locallib.php');
/**
* Module instance settings form.
*
* @package    mod_studentlibrary
* @copyright  2020 itsup.biz
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/
class mod_studentlibrary_mod_form extends moodleform_mod
{
    /**
    * Defines forms elements
    */
    public function definition()
    {
        global $CFG, $DB, $PAGE, $USER, $SESSION;
        $serverapi = get_mod_config('serverapi');
        $org_id = $DB->get_record('config', array('name' => 'studentlibrary_idorg'))->value;
        $agr_id = $DB->get_record('config', array('name' => 'studentlibrary_norg'))->value;
        // $id_u = $USER->username;
        
        // Сервет КС не нужен так как получаем готовую ссылку на книгу
        // $server = $DB->get_record('config', array('name' => 'studentlibrary_server'))->value;
        // // $wwwroot = $CFG->wwwroot;
        // if (substr($server, -1) !== '/') {
        //     $server = $server . '/';
        // }
        if (substr($serverapi, -1) !== '/') {
            $serverapi = $serverapi . '/';
        }
        
        /*  Получаем сессию организации*/
        $SSr_O = getSSr_O($serverapi, $org_id, $agr_id);
        // print_r($SSr_O);
        // print('<br>');
        /*  Получаем сессию пользователя */
        // print_r('SSr_P: ');
        // print_r($SSr_P);
        // print('<br>');
        $SSr_P = getSSr_P($serverapi, $SSr_O, $USER->id, str_replace(' ', '_', $USER->lastname), str_replace(' ', '_', $USER->firstname));
        // print_r($SSr_P);
        // print('<br>');
        /*  Получаем комплект книг*/
        // print_r($array);
        // print('<br>');
        // die();
        
        $kitsList = getKitsList($serverapi, $SSr_P);
        
        $mform = $this->_form;
        $mform->addElement('header', 'general', get_string('general', 'form'));
        $mform->addElement('text', 'name', "Название", array('size' => '64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEANHTML);
        }
        
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'studentlibraryname', 'mod_studentlibrary');
        
        
        if ($CFG->branch >= 29) {
            $this->standard_intro_elements();
        } else {
            $this->add_intro_editor();
        }
        // region AddBook
        
        // $mform->addElement('header', 'AddBook', get_string('studentlibrary:addbook', 'mod_studentlibrary'));
        
        
        // $mform->hardFreeze('booke');
        
        $PAGE->requires->css('/mod/studentlibrary/css/style.css');
        $lang = 'ru';
        if (!empty($SESSION->lang)) {
            if($SESSION->lang !== null) {
                $lang =$SESSION->lang;
            } else {
                $lang = 'ru';
            }
        } else {
            $lang = 'ru';
        }
        $PAGE->requires->js_call_amd('mod_studentlibrary/find_form', 'init', [
            $serverapi,
            get_string('studentlibrary:kits_select', 'mod_studentlibrary'),
            get_string('studentlibrary:div_select', 'mod_studentlibrary'),
            get_string('studentlibrary:headerContent', 'mod_studentlibrary'),
            get_string('studentlibrary:find_material', 'mod_studentlibrary'),
            get_string('studentlibrary:add_material', 'mod_studentlibrary'),
            get_string('studentlibrary:search_bar', 'mod_studentlibrary'),
            $lang
        ]);
        $courseid = optional_param('course', 0, PARAM_INT);
        $section = optional_param('section', 0, PARAM_INT);
        if ($courseid !== 0) {
            $mform->addElement(
                'button',
                'search_button',
                get_string('studentlibrary:search_button', 'mod_studentlibrary'),
                array(
                    'id' => 'search_button',
                    'data-courseid' => $courseid,
                    'data-token' => '',
                    'data-section' => $section,
                    'data-service' => '',
                    'ssr_o' => $SSr_O,
                    'ssr_p' => $SSr_P,
                    'kitsList' => implode(",", $kitsList)
                    )
                );
            }
            $mform->addElement(
                'text',
                'booke',
                get_string('studentlibrary:bookid', 'mod_studentlibrary'),
                array(
                    'size' => '64',
                    'ssr_o' => $SSr_O,
                    'ssr_p' => $SSr_P,
                    )
                );
                if (!empty($CFG->formatstringstriptags)) {
                    $mform->setType('booke', PARAM_TEXT);
                } else {
                    $mform->setType('booke', PARAM_CLEANHTML);
                }
                $mform->addElement('html', '<div><a target="_blank" href="https://www.studentlibrary.ru/ru/pages/plagin.html">' . get_string('studentlibrary:instruction', 'mod_studentlibrary') . '</a></div>');
                // $this->standard_grading_coursemodule_elements();
                $this->standard_coursemodule_elements();
                $this->add_action_buttons();
            }
        }
        