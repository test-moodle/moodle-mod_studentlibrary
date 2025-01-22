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

function get_lib_url($book)
{
	require_once(__DIR__ . '/../../config.php');
	global $DB, $USER, $PAGE,  $SESSION;
	if (!empty($SESSION->lang)) {
		if ($SESSION->lang !== null) {
			$lang = $SESSION->lang;
		} else {
			$lang = 'ru';
		}
	} else {
		$lang = 'ru';
	}
	$serverapi = get_mod_config('serverapi');
	$org_id = $DB->get_record('config', array('name' => 'studentlibrary_idorg'))->value;
	$agr_id = $DB->get_record('config', array('name' => 'studentlibrary_norg'))->value;
	if (substr($serverapi, -1) !== '/') {
		$serverapi = $serverapi . '/';
	}
    /**
     * Получаем сессию организации
     * We get the organization's session
     */
	$SSr_O = getSSr_O($serverapi, $org_id, $agr_id);
	$url = $PAGE->url;
	$scheme = $url->get_scheme();
	$host = $url->get_host();
	$port = $url->get_port();
	
	if (strlen($port) > 0) {
		$url_api = $scheme . '://' . $host . ':' . $port . '/mod/studentlibrary/set_grade.php';
	} else {
		$url_api = $scheme . '://' . $host . '/mod/studentlibrary/set_grade.php';
	}
	/**
	 * Генерируем apikey
	 * Generating an apikey
	 */
	$userid = $USER->id;
	$courseid = $PAGE->course->id;
	$moduleid = required_param('id', PARAM_INT);
	$timecreated = time();
	$apikey =  hash('sha256', $org_id . $agr_id . $userid . $courseid . $moduleid . $timecreated); /* 64 */
	$result = new stdClass();
	$result->course = $courseid;
	$result->module = $moduleid;
	$result->user = $userid;
	$result->timecreated = $timecreated;
	$result->apikey = $apikey;
	/**
	 * Секунд в дне
	 * Seconds per day
	 */
	$unix_day = 86400;
	/**
	 * Удаление старых записей
	 * Deleting old records
	 */
	if ($DB->record_exists('studentlibrary_apikey', array('course' => $courseid, 'module' => $moduleid, 'user' => $userid))) {
		$table = 'studentlibrary_apikey';
		$select = 'course = :course AND module = :module AND user = :user AND timecreated < :timecreated';
		$param = array('course' => $courseid, 'module' => $moduleid, 'user' => $userid, 'timecreated' => ($timecreated - $unix_day));
		$DB->delete_records_select($table, $select, $param);
	}
    /**
     * Получаем сессию пользователя
     * Getting the user's session
     */
	$SSr_P = getSSr_P($serverapi, $SSr_O, $USER->id, str_replace(' ', '_', $USER->lastname), str_replace(' ', '_', $USER->firstname));
	if (strtolower(explode("/", $book)[0]) === 'switch_kit') {
		/**
		 * Получаем ссылку на Комплект
		 * We get a link to the Kit
		 */
		$getAccesURL = '';
		$book_id = explode("/", $book)[1];
		$getAccesURL = $serverapi . "db";
		$getAccesURLOprions = array(
			"SSr" => $SSr_O,
			"guide" => "session",
			"cmd" => "solve",
			"action" => "seamless_access",
			"id" => $USER->id,
			"value.kit_id" => $book_id
		);
		$getAccesURL = $getAccesURL . '?' . 'SSr=' . $SSr_O . '&guide=session&cmd=solve&action=seamless_access&id=' . $USER->id . '&value.kit_id=' . $book_id;
		
		if ($getAccesURL != '') {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_URL, $getAccesURL);
			/**
			 * Получаем сессию
			 * We get a session
			 */
			$RezXML = curl_exec($ch);
			curl_close($ch);
			$xml = simplexml_load_string($RezXML);
			$json = json_encode($xml);
			$array = json_decode($json, TRUE);
			$book_item = BuildSwitchKit($serverapi, $SSr_O, $book_id, $array["url"], $lang);
		}
	} else {
		/**
		 * Получаем ссылку на книгу
		 * We get a link to the book
		 */
		$getAccesURL = '';
		$book_id = explode("/", $book)[1];
		for ($i = 2; $i < count(explode("/", $book)); $i++) {
			$book_id = $book_id . "/" . explode("/", $book)[$i];
		}
		$getAccesURL = $serverapi . "db";
		if (explode("/", $book)[0] === 'doc') {
			$getAccesURLOprions = [
				"SSr" => $SSr_O,
				"guide" => "session",
				"cmd" => "solve",
				"action" => "seamless_access",
				"id" => $USER->id,
				"value.doc_id" => $book_id,
				"apikey" => $apikey,
				"url_api" => $url_api
			];
		} else {
			$getAccesURLOprions = [
				"SSr" => $SSr_O,
				"guide" => "session",
				"cmd" => "solve",
				"action" => "seamless_access",
				"id" => $USER->id,
				"value.book_id" => $book_id,
				"apikey" => $apikey,
				"url_api" => $url_api
			];
		}
		if ($getAccesURL != '') {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $getAccesURL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_POST, true);
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($getAccesURLOprions));
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-type: application/json',
				'Accept: application/json'
			));
			$SSrXML = curl_exec($ch);
			curl_close($ch);
			$xml = simplexml_load_string($SSrXML);
			$json = json_encode($xml);
			$array = json_decode($json, TRUE);
			$url = $array["url"];
			$DB->insert_record('studentlibrary_apikey', $result, false); /* записываем ключ */
		}
		$book_item = BuildBook($serverapi,  $SSr_P, $book, $url);
	}
	return $book_item;
}

function BuildBook($server, $ssr, $BookID,  $url)
{
	global $CFG, $SESSION;
	require_once($CFG->dirroot . '/course/moodleform_mod.php');
	require_once(__DIR__ . '/lib.php');
	if (explode("/", $BookID)[0] === 'book') {
		$book_id = explode("/", $BookID)[1];
		$getSesionURL = $server . 'db?SSr=' . $ssr . '&guide=book&cmd=data&id=' . $book_id . '&img_src_form=b64&on_cdata=1';
	} else if (explode("/", $BookID)[0] === 'doc') {
		$book_id = GetBookIdbyDocId($server, $ssr, explode("/", $BookID)[1]);
		$getSesionURL = $server . 'db?SSr=' . $ssr . '&guide=book&cmd=data&id=' . $book_id . '&img_src_form=b64&on_cdata=1';
	}
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $getSesionURL);
	/**
	 * Получаем сессию
	 * We get a session
	 */
	$SSrXML = curl_exec($ch);
	curl_close($ch);
	$xml = simplexml_load_string($SSrXML, null, LIBXML_NOCDATA);
	$json = json_encode($xml);
	$array = json_decode($json, TRUE);
	$meta_var = $array['meta']['var'];
	$chapters = $array['chapters']['chapter'];
	$authors = '';
	$annotation = '';
	$publisher = '';
	$year = '';
	for ($i = 0; $i < count($meta_var); $i++) {
		if ($meta_var[$i]['@attributes']['name'] === 'authors') {
			if ($xml->xpath('/book/meta/var[@name="authors"]/string[@language="' . $SESSION->lang . '"]')) {
				$authors = $xml->xpath('/book/meta/var[@name="authors"]/string[@language="' . $SESSION->lang . '"]')[0];
			} else if ($xml->xpath('/book/meta/var[@name="authors"]/string[@language="ru"]')) {
				$authors = $xml->xpath('/book/meta/var[@name="authors"]/string[@language="ru"]')[0];
			} else if ($xml->xpath('/book/meta/var[@name="authors"]/string[@language="en"]')) {
				$authors = $xml->xpath('/book/meta/var[@name="authors"]/string[@language="en"]')[0];
			}
		}
		if ($meta_var[$i]['@attributes']['name'] === 'annotation') {
			if ($xml->xpath('/book/meta/var[@name="annotation"]/string[@language="' . $SESSION->lang . '"]')) {
				$annotation = $xml->xpath('/book/meta/var[@name="annotation"]/string[@language="' . $SESSION->lang . '"]')[0];
			} else if ($xml->xpath('/book/meta/var[@name="annotation"]/string[@language="ru"]')) {
				$annotation = $xml->xpath('/book/meta/var[@name="annotation"]/string[@language="ru"]')[0];
			} else if ($xml->xpath('/book/meta/var[@name="annotation"]/string[@language="en"]')) {
				$annotation = $xml->xpath('/book/meta/var[@name="annotation"]/string[@language="en"]')[0];
			}
		}
		if ($meta_var[$i]['@attributes']['name'] === 'year') {
			if ($xml->xpath('/book/meta/var[@name="year"]/string[@language="' . $SESSION->lang . '"]')) {
				$year = $xml->xpath('/book/meta/var[@name="year"]/string[@language="' . $SESSION->lang . '"]')[0];
			} else if ($xml->xpath('/book/meta/var[@name="year"]/string[@language="ru"]')) {
				$year = $xml->xpath('/book/meta/var[@name="year"]/string[@language="ru"]')[0];
			} else if ($xml->xpath('/book/meta/var[@name="year"]/string[@language="en"]')) {
				$year = $xml->xpath('/book/meta/var[@name="year"]/string[@language="en"]')[0];
			}
		}
		if ($meta_var[$i]['@attributes']['name'] === 'publisher') {
			if ($xml->xpath('/book/meta/var[@name="publisher"]/string[@language="' . $SESSION->lang . '"]')) {
				$publisher = $xml->xpath('/book/meta/var[@name="publisher"]/string[@language="' . $SESSION->lang . '"]')[0];
			} else if ($xml->xpath('/book/meta/var[@name="publisher"]/string[@language="ru"]')) {
				$publisher = $xml->xpath('/book/meta/var[@name="publisher"]/string[@language="ru"]')[0];
			} else if ($xml->xpath('/book/meta/var[@name="publisher"]/string[@language="en"]')) {
				$publisher = $xml->xpath('/book/meta/var[@name="publisher"]/string[@language="en"]')[0];
			}
		}
	}
	$chapter_name = '';
	if (isset($chapters)) {
		for ($i = 0; $i < count($chapters); $i++) {
			if ($chapters[$i]['@attributes']['id'] === explode("/", $BookID)[1]) {
				$chapter_name .= ' ' . $chapters[$i]['string'];
			}
		}
	}
	if ($chapter_name !== '') {
		$chapter_name .= ' ' . get_string('studentlibrary:page', 'mod_studentlibrary') . ' ' . explode("/", $BookID)[2];
	}
	$publisher = GetPublisher($ssr, $publisher, $server);
	if (isset($array['meta']['attachments']['cash']['attach'][0])) {
		$img_src = $array['meta']['attachments']['cash']['attach'][0]['@attributes']['src'];
	} else {
		$img_src = $array['meta']['attachments']['cash']['attach']['@attributes']['src'];
	}
	$book_list_item = '';
	$book_list_item .= '<label class="radio-card">';
	$book_list_item .= '<div class="card-content-detail-wrapper">';
	if ($xml->xpath('/book/title/string[@language="' . $SESSION->lang . '"]')) {
		$book_list_item .= '<div class="titleH1">' . $xml->xpath('/book/title/string[@language="' . $SESSION->lang . '"]')[0] . '</div>';
	} else if ($xml->xpath('/book/title/string[@language="ru"]')) {
		$book_list_item .= '<div class="titleH1">' . $xml->xpath('/book/title/string[@language="ru"]')[0] . '</div>';
	} else if ($xml->xpath('/book/title/string[@language="en"]')) {
		$book_list_item .= '<div class="titleH1">' . $xml->xpath('/book/title/string[@language="en"]')[0] . '</div>';
	}
	
	$book_list_item .= '<div class="titleH2">' . $chapter_name . '</div>';
	$book_list_item .= '<div class="card-props">';
	$book_list_item .= '<div class="cover">';
	$book_list_item .= '<img src=' . $img_src . '></img>';
	$book_list_item .= '</div>';
	$book_list_item .= '<div class="props-list">';
	$book_list_item .= '<dl class="main-props">';
	$book_list_item .= '<dt class="ng-star-inserted">' . get_string('studentlibrary:authors', 'mod_studentlibrary') . ':</dt><dd class="ng-star-inserted authors">' . $authors . '</dd>';
	$book_list_item .= '<dt class="ng-star-inserted">' . get_string('studentlibrary:publisher', 'mod_studentlibrary') . ':</dt><dd class="ng-star-inserted publisher">' . $publisher . '</dd>';
	$book_list_item .= '<dt class="ng-star-inserted">' . get_string('studentlibrary:year', 'mod_studentlibrary') . ':</dt><dd class="ng-star-inserted year">' . $year . '</dd>';
	$book_list_item .= '<dt class="ng-star-inserted"><div class="read_button"><a href="' . $url . '" target="_blank" class="btn btn-primary" >' . get_string('studentlibrary:read', 'mod_studentlibrary') . '</a></div></dt><dd class="ng-star-inserted"></dd>';
	$book_list_item .= '</dl>';
	$book_list_item .= '<div class="doc_name"></div>';
	$book_list_item .= '</div>';
	$book_list_item .= '<div class="annotation"><p class="annotation_title">' . get_string('studentlibrary:annotation', 'mod_studentlibrary') . ':</p>';
	if ($annotation) {
		if (isset($annotation['p'])) {
			for ($p = 0; $p < count($annotation['p']); $p++) {
				$book_list_item .= '<p>' . $annotation['p'][$p] . '</p>';
			}
		} elseif (isset($annotation['div'])) {
			$book_list_item .= '<div>' . $annotation['div'] . '</div>';
		} else {
			$book_list_item .= '<div>' . $annotation . '</div>';
		}
	}
	$book_list_item .= '</div>';
	$book_list_item .= '</div>';
	$book_list_item .= '</div>';
	$book_list_item .= '</label>';
	return $book_list_item;
}

function GetPublisher($ssr, $publisherId, $server)
{
	$getPublisherURL = $server . 'db?SSr=' . $ssr . '&guide=publishers&cmd=data&id=' . $publisherId . '&build_in_data=1&on_cdata=0';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $getPublisherURL);
	/**
	 * Получаем сессию
	 * We get a session
	 */
	$PublisherXML = curl_exec($ch);
	curl_close($ch);
	$xml = simplexml_load_string($PublisherXML);
	$json = json_encode($xml);
	$array = json_decode($json, TRUE);
	$publishers_field = $array['field'];
	if ($publishers_field) {
		for ($i = 0; $i < count($publishers_field); $i++) {
			if ($publishers_field[$i]['@attributes']['id'] === 'name') {
				$publishers = $publishers_field[$i]['string'];
			}
		}
	} else {
		$publishers = '';
	}
	return ($publishers);
}

function GetBookIdbyDocId($server, $ssr, $BookID)
{
	$master_book_data_URL = $server . 'db?SSr=' . $ssr . '&guide=doc&cmd=data&id=' . $BookID . '&tag=master_book_data';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $master_book_data_URL);
	/** 
	 * Получаем данные книги по главе
	 * We get the book data by chapter
	*/
	$RezXMLString = curl_exec($ch);
	curl_close($ch);
	$xml = simplexml_load_string($RezXMLString);
	$result = $xml->xpath('/doc/book');
	if (isset($result[0])) {
		return $result[0]->attributes()->id;
	} else {
		return null;
	}
};

function getSSr_O($serverapi, $org_id, $agr_id)
{
	/**
	 * Получаем сессию организации
	 * We get the organization's session
	 */
	$getSesionURL = $serverapi . "join?org_id=" . $org_id . "&agr_id=" . $agr_id . "&app=plugin_moodle";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $getSesionURL);
	/**
	 * Получаем сессию
	 * We get a session
	 */
	$SSrXML = curl_exec($ch);
	curl_close($ch);
	$xml = simplexml_load_string($SSrXML);
	$json = json_encode($xml);
	$array = json_decode($json, TRUE);
	$SSr_O = $array["code"];
	return $SSr_O;
}

function getSSr_P($serverapi, $SSr_O, $USER_id, $USER_lastname, $USER_firstname)
{
	/**
	 * Получаем сессию пользователя
	 * Getting the user's session
	 */
	$getSesionURL = $serverapi . "db?SSr=" . $SSr_O . "&guide=session&cmd=solve&action=seamless_access&id=" . $USER_id . '&value.FamilyName.ru=' . $USER_lastname . '&value.NameAndFName.ru=' . $USER_firstname;
	$ch2 = curl_init();
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch2, CURLOPT_URL, $getSesionURL);
	/**
	 * Получаем сессию
	 * We get a session
	 */
	$SSrXML = curl_exec($ch2);
	curl_close($ch2);
	$xml = simplexml_load_string($SSrXML);
	$json = json_encode($xml);
	$array = json_decode($json, TRUE);
	$SSr_P = $array["code"];
	return $SSr_P;
}

function getKitsList($serverapi, $SSr_P)
{
	/**
	 * Получаем комплект книг
	 * We get a set of books
	 */
	$kitsURL = $serverapi . "db?SSr=" . $SSr_P . "&guide=sengine&cmd=sel&tag=all_agreement_kits";
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $kitsURL);
	$kitsListXML = curl_exec($ch);
	curl_close($ch);
	$xml = simplexml_load_string($kitsListXML);
	$json = json_encode($xml);
	$array = json_decode($json, TRUE);
	$all_agreement_kits = $array["all_agreement_kits"];
	$kitsList = [];
	foreach ($all_agreement_kits as $kits) {
		$kitID = $kits["@attributes"]["id"];
		array_push($kitsList, $kitID);
	}
	return $kitsList;
}

function BuildSwitchKit($server, $ssr, $kit_id, $url, $lang)
{
	/**
	 * Получаем комплект книг
	 * We get a set of books
	 */
	$kitDataURL = $server . "db?SSr=" . $ssr . "&guide=sengine&cmd=sel&tag=kit_content&kit=" . $kit_id;
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $kitDataURL);
	$kitDataXML = curl_exec($ch);
	curl_close($ch);
	$xml = simplexml_load_string($kitDataXML, null, LIBXML_NOCDATA);
	$kitName = '';
	if ($xml->xpath('/document/name/string[@language="' . $lang . '"]')) {
		$kitName = $xml->xpath('/document/name/string[@language="' . $lang . '"]');
	} else if ($xml->xpath('/document/name/string[@language="ru"]')) {
		$kitName = $xml->xpath('/name/string[@language="ru"]');
	} else if ($xml->xpath('/document/name/string[@language="en"]')) {
		$kitName = $xml->xpath('/document/name/string[@language="en"]');
	}
	return '<div class="titleH2"><p>'.get_string('studentlibrary:link_to_the_kit', 'mod_studentlibrary').'<a target="_blank" href="' . $url . '">' . $kitName[0] . '</a></p></div>';
}
