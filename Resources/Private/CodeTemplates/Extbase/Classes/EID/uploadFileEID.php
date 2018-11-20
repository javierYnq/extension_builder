<?php

date_default_timezone_set('America/Santiago');

/**
 * Created by PhpStorm.
 * User: Francisco Llanquipichun
 * Date: 26-06-14
 * Time: 09:43 AM
 */

if (!defined ('PATH_typo3conf')) die ('Access denied.');

\TYPO3\CMS\Frontend\Utility\EidUtility::initTCA();

$id = isset($HTTP_GET_VARS['id'])?$HTTP_GET_VARS['id']:0;
header('Content-Type: application/json');

$TSFE = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Frontend\Controller\TypoScriptFrontendController', $TYPO3_CONF_VARS, $id, '0', 1);
$GLOBALS['TSFE'] = $TSFE;
$GLOBALS['TSFE']->initFEuser(); // Get FE User Information
$GLOBALS['TSFE']->fetch_the_id();
$GLOBALS['TSFE']->getPageAndRootline();
$GLOBALS['TSFE']->initTemplate();
$GLOBALS['TSFE']->tmpl->getFileName_backPath = PATH_site;
$GLOBALS['TSFE']->forceTemplateParsing = 1;
$GLOBALS['TSFE']->getConfigArray();
$GLOBALS['TSFE']->register['hello'] = 1;

$file = $_FILES['file']; // archivo
$temporalDirectory = PATH_site.'uploads/uploadFile/';

//almacenado del archivo
$filename = basename($file['name']);

$result = array();
$error = false;
$filePath = "";

//valida la existencia del directorio, si no existe lo crea
if (!is_dir(dirname($temporalDirectory.$filename))) {
    error_log(mkdir(dirname($temporalDirectory.$filename), 0777, true));
}

//valida la existencia del archivo
if(file_exists($temporalDirectory.$filename)){
    $filename = date('dmYhms').'_'.basename($file['name']);
}

if (move_uploaded_file($_FILES['file']['tmp_name'], $temporalDirectory.$filename)){
    $filePath = $temporalDirectory.$filename;
    $error = false;
    header($_SERVER['SERVER_PROTOCOL'] . ' 200 Ok', true, 200);

}else{
    $error = true;
    header($_SERVER['SERVER_PROTOCOL'] . ' 500 Internal Server Error', true, 500);
}

$result = array(
    'error' => $error,
    'filePath' => $filePath
);

print json_encode($result);


