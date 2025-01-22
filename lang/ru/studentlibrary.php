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
$string['modulename'] = 'ЭБС КС';
$string['modulename_help'] = 'ЭБС STUDENTLIBRARY';
$string['pluginadministration'] = 'НАСТРОЙКИ ЭБС STUDENTLIBRARY';

$string['studentlibraryname'] = 'НАЗВАНИЕ ЭЛЕМЕНТА (КНИГИ) В КУРСЕ';

// new
$string['studentlibrary:studentlibraryhead'] = 'Конфигурация ЭБС';
$string['studentlibrary:studentlibraryhead_desc'] = 'Задайте заданный ИД организации и метку договора';
$string['studentlibrary:idorg'] = 'ИД Организации';
$string['studentlibrary:idorg_desc'] = 'ИД организации полученный при заключении договора';
$string['studentlibrary:norg'] = 'Метка договора';
$string['studentlibrary:norg_desc'] = 'Метка договора полученный при заключении договора';
$string['studentlibrary:server'] = 'Сервер ЭБС';
$string['studentlibrary:server_desc'] = 'Сервер ЭБС для бесшовного перехода';
$string['studentlibrary:search_button'] = 'Нажмите для выбора материалов';
$string['studentlibrary:headerContent'] = 'Поиск...';
$string['studentlibrary:kits_select'] = 'Комплекты';
$string['studentlibrary:div_select'] = 'Коллекции';
$string['studentlibrary:authors'] = 'Авторы';
$string['studentlibrary:publisher'] = 'Издательство';
$string['studentlibrary:year'] = 'Год издания';
$string['studentlibrary:read'] = 'Читать';
$string['studentlibrary:annotation'] = 'Аннотация';
$string['studentlibrary:page'] = 'стр.';
$string['studentlibrary:addbook'] = 'Добавить книгу';
$string['studentlibrary:bookid'] = "ИД книги";
$string['studentlibrary:instruction'] = 'Инструкция по работе с плагином "Консультант студента"';
$string['studentlibrary:find_material'] = 'Найти материал';
$string['studentlibrary:add_material'] = 'Добавить материал';
$string['studentlibrary:search_bar'] = 'Строка поиска';
$string['studentlibrary:vedomost_all'] = 'Ведомость ЭБС';
$string['studentlibrary:vedomost_my'] = 'Моя успеваемость в ЭБС';
$string['studentlibrary:t_user'] = 'Пользователь';
$string['studentlibrary:t_quests_all'] = 'Всего вопросов';
$string['studentlibrary:t_quests_true'] = 'Правильных ответов';
$string['studentlibrary:t_url'] = 'Ссылка на отчет';
$string['studentlibrary:t_date'] = 'Дата';
$string['studentlibrary:t_progress_report'] = 'Отчет о прохождении';
$string['studentlibrary:not_data'] = 'Нет данных';
$string['studentlibrary:link_to_the_kit'] = 'Ссылка на комплект: ';
$string['studentlibrary:get_tree'] = 'Право получать дерево каталога';
$string['studentlibrary:get_constructor'] = 'Право получать доступ к сервису Консультант студента';
$string['studentlibrary:addinstance'] = 'Право создавать инстансы модуля Консультант студента';