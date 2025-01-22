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
 * @copyright   2025 shekhovtcev <plagin@geotar.ru>
 * @license     http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'studentlibrary';
$string['modulename'] = 'studentlibrary';
$string['modulename_help'] = 'studentlibrary module';
$string['pluginadministration'] = 'EBS SETTINGS STUDENTLIBRARY';

$string['studentlibraryname'] = 'NAME OF ITEM (BOOK) IN THE COURSE';

// new
$string['studentlibrary:studentlibraryhead'] = 'ESS configuration';
$string['studentlibrary:studentlibraryhead_desc'] = 'Specify the specified organization ID and contract label';
$string['studentlibrary:idorg'] = 'Organization ID';
$string['studentlibrary:idorg_desc'] = 'Organization ID received upon conclusion of the contract';
$string['studentlibrary:norg'] = 'Contract label';
$string['studentlibrary:norg_desc'] = 'Contract label received upon conclusion of the contract';
$string['studentlibrary:server'] = 'EBS server';
$string['studentlibrary:server_desc'] = 'EBS server for seamless transition';
$string['studentlibrary:search_button'] = 'Click to select materials';
$string['studentlibrary:headerContent'] = 'Search...';
$string['studentlibrary:kits_select'] = 'Kits';
$string['studentlibrary:div_select'] = 'Collections';
$string['studentlibrary:authors'] = 'Authors';
$string['studentlibrary:publisher'] = 'Publishing';
$string['studentlibrary:year'] = 'The year of publishing';
$string['studentlibrary:read'] = 'Read';
$string['studentlibrary:annotation'] = 'Annotation';
$string['studentlibrary:page'] = 'page';
$string['studentlibrary:addbook'] = 'Add book';
$string['studentlibrary:bookid'] = "BookID";
$string['studentlibrary:instruction'] = 'Instructions for working with the plugin "Student Consultant"';
$string['studentlibrary:find_material'] = 'Find material';
$string['studentlibrary:add_material'] = 'Add material';
$string['studentlibrary:search_bar'] = 'Search bar';
$string['studentlibrary:vedomost_all'] = 'EBS Statement';
$string['studentlibrary:vedomost_my'] = 'My academic performance at EBS';
$string['studentlibrary:t_user'] = 'User';
$string['studentlibrary:t_quests_all'] = 'Total questions';
$string['studentlibrary:t_quests_true'] = 'Correct answers';
$string['studentlibrary:t_url'] = 'Link to the report';
$string['studentlibrary:t_date'] = 'Date';
$string['studentlibrary:t_progress_report'] = 'Progress Report';
$string['studentlibrary:not_data'] = 'No data available';
$string['studentlibrary:link_to_the_kit'] = 'Link to the kit: ';
$string['studentlibrary:get_tree'] = 'Right for get tree catalog';
$string['studentlibrary:get_constructor'] = 'Right to access the service Studentlibrary';
$string['studentlibrary:addinstance'] = 'Right for get studentlibrary instance';