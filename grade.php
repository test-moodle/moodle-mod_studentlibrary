<?php

/**
 * Redirect the user to the appropiate submission related page.
 *
 * @package     mod_studentlibrary
 * @category    grade
 * @copyright   2020 itsup.biz

 */

require(__DIR__.'/../../config.php');

$id = required_param('id', PARAM_INT);
$itemnumber = optional_param('itemnumber', 0, PARAM_INT);
$userid = optional_param('userid', 0, PARAM_INT);
redirect('view.php?id='.$id);
