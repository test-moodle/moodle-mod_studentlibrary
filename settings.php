<?php

/**
 * Plugin administration pages are defined here.
 *
 * @package     mod_studentlibrary
 * @category    admin
 * @copyright   2020 itsup.biz
 */

defined('MOODLE_INTERNAL') || die();
require_once __DIR__ . '/lib.php';

if ($ADMIN->fulltree) {

     require_once($CFG->dirroot . '/mod/studentlibrary/lib.php');
     
     $settings->add(
          new admin_setting_heading(
               'studentlibraryhead',
               get_string('studentlibrary:studentlibraryhead', 'mod_studentlibrary'),
               get_string('studentlibrary:studentlibraryhead_desc', 'mod_studentlibrary')
          )
     );
     $settings->add(
          new admin_setting_configtext(
               'studentlibrary_idorg',
               get_string('studentlibrary:idorg', 'mod_studentlibrary'),
               get_string('studentlibrary:idorg_desc', 'mod_studentlibrary'),
               '0000'
          )
     );
     $settings->add(
          new admin_setting_configtext(
               'studentlibrary_norg',
               get_string('studentlibrary:norg', 'mod_studentlibrary'),
               get_string('studentlibrary:norg_desc', 'mod_studentlibrary'),
               '0000'
          )
     );
     // Сервет КС не нужен так как получаем готовую ссылку на книгу
     // $settings->add(
     //      new admin_setting_configtext(
     //           'studentlibrary_server',
     //           get_string('studentlibrary:server', 'mod_studentlibrary'),
     //           get_string('studentlibrary:server_desc', 'mod_studentlibrary'),
     //           'https://www.studentlibrary.ru/'
     //      )
     // );

     // $settings->add(
     //      new admin_setting_configtext(
     //           'studentlibrary_serverapi',
     //           get_string('studentlibrary:serverapi', 'mod_studentlibrary'),
     //           get_string('studentlibrary:serverapi_desc', 'mod_studentlibrary'),
     //           'http://gate22.studentlibrary.ru/'
     //      )
     // );
     
     // $settings->add(
     //      new  admin_setting_description(
     //           'studentlibrarylink',
     //           'Список доступных книг',
     //           '<a href="/mod/studentlibrary/book.php">просмотреть</a>'
     //      )
     // );
     // $item = new  admin_setting_configstoredfile(
     //      'studentlibraryfile',
     //      'Загрузить новый список доступных книг',
     //      'используйте для загрузки нового списка',
     //      'ebslist'
     // );
     // $item->set_updatedcallback('studentlibrary_file');
     // $settings->add($item);
}
