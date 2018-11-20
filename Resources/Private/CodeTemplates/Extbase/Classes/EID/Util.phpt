<?php
namespace {espacioNombre};


use DateTime;
use TYPO3\CMS\Saltedpasswords\SaltedPasswordService;

/**
 * Class Util
 * @package CEISUFRO\CeisEjemplo\EID
 */
class Util
{
    /**
     * Check register typo3 frontend user and password
     *
     * @param string $username FE username
     * @param string $password FE password
     * @return bool
     */
    public static function checkUser($username, $password) {

        $loginData = array(
            'username' => $username,
            'uident_text' => $password,
            'status' => 'login',
        );

        $GLOBALS['TSFE']->fe_user->checkPid = ''; //do not use a particular pid
        $info = $GLOBALS['TSFE']->fe_user->getAuthInfoArray();
        $user = $GLOBALS['TSFE']->fe_user->fetchUserRecord($info['db_user'], $loginData['username']);

        if (isset($user) && $user != '') {
            $authBase = new SaltedPasswordService();
            $ok = $authBase->compareUident($user, $loginData);
            if ($ok) {
                //login successfull
                $GLOBALS['usuario'] = $user;
                $check = TRUE;
            } else {
                $check = FALSE;
            }
        } else {
            $check = FALSE;
        }
        return $check;
    }

    /**
     * True if frontend user is login
     * la sesión debe corresponder al mismo sitio, tambien diferencia subdominio:
     * http//:www.sitio.com != http//:sitio.com
     *
     * @return bool true si tiene sesión activa
     */
    public static function checkSession() {
        if($GLOBALS['TSFE']->fe_user->user !== NULL || $GLOBALS['TSFE']->loginUser == TRUE) {
            return TRUE;
        }else{
            return FALSE;
        }
    }

    /**
     * Retuns TRUE
     * add var X-API-KEY on ext_conf_template.txt
     *
     * @param string $apiKey header api Key
     * @return bool
     */
    public static function checkHeaderApiKey($apiKey) {
        $vars = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['ceis_ejemplo']);
        if( isset($vars['X-API-KEY']) ){
            return $vars['X-API-KEY'] == $apiKey ? TRUE : FALSE;
        }else{
            return FALSE;
        }
    }

    /**
     * Transform date string to \DateTime
     *
     * @param string $date format d-m-Y (dd-mm-yyyy)
     * @return DateTime|null
     */
    public function transformDate($date) {
        if($date !== NULL) {
            $dateResponse = date('d-m-Y',strtotime(stripslashes($date)));
            /** @var DateTime $dateResponse */
            return new DateTime($dateResponse);
        }else{
            return  NULL;
        }
    }

    /**
     * Envia mensaje de repuesta del servicio con estandar JSend
     * https://labs.omniti.com/labs/jsend
     *
     * @param string $status 'success', 'error' or 'fail'
     * @param mixed $data
     * @param int $code número de error
     * @param string $msg message
     */
    public function JSendResponse($status = 'success',$data = NULL, $code = 0, $msg = '') {
        $response = array(
            'status' => $status,
            'data' => $data
        );
        if($status == 'error') {
            $response['code'] = $code;
            $response['message'] = $msg;
            error_log("$status $code: $msg");
        }
        echo json_encode($response);
    }

}