<?php
/*+***********************************************************************************
 * Andrei Maximov
 *************************************************************************************/
require_once 'include/UI.php';
$uiWeb = new A5_UI();
$uiWeb->process(new A5_Request($_REQUEST));
