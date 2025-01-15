<?php
function xmldb_studentlibrary_uninstall(){
	require_once(__DIR__.'/../../../config.php');
	global $DB;
	
$ff=$DB->get_record('config', array('name' =>'studentlibraryfile'))->value;


$fs = get_file_storage();
 
$fileinfo = array(
    'component' => 'core',     
    'filearea' => 'ebslist',    
    'itemid' => 0,               
    'contextid' => 1, 
    'filepath' => '/',          
    'filename' => $ff); 
 
$file = $fs->get_file($fileinfo['contextid'], $fileinfo['component'], $fileinfo['filearea'],
                      $fileinfo['itemid'], $fileinfo['filepath'], $fileinfo['filename']);
					  
if ($file) {
    $file->delete();
}

return true;
}