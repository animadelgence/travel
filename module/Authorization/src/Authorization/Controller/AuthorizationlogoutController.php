<?php

namespace Authorization\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Request;
use Zend\Session\Container;
use Zend\Session\Config\StandardConfig;
use Zend\Session\SessionManager;

class AuthorizationlogoutController extends AbstractActionController {

    protected $sessionid;

    public function __construct() {

        $userSession = new Container('loginId');
        $this->sessionid = $userSession->offsetGet('loginId');
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $dynamicPath = $protocol.$_SERVER['HTTP_HOST'];
        if($this->sessionid =="")
		{
		header("Location:".$dynamicPath);
			exit;
		}
    }

    public function logoutuserAction() {
        $user_session->loginId = ($_SESSION['loginId']);
        $user_session = new \Zend\Session\Container('loginId');
        unset($user_session->loginId);
        setcookie('siteAuth', '', time() - 1, '/');
        $plugin = $this->routeplugin();
        $dynamicPath = $plugin->dynamicPath();
        return $this->redirect()->toUrl($dynamicPath);
        //exit;
    }

    public function deactivateUserAction() {
        $plugin = $this->routeplugin();
        $modelPlugin = $this->modelplugin();
        $rowSets = $modelPlugin->getpublisherTable()->selectEmail();
        $noOfNonVerifiedUsers = count($rowSets);
        $currentTime = time();
        echo 'publisherId  -------  noofdays  --------  mailSendFlag <br>';
        for ($i = 0; $i < $noOfNonVerifiedUsers; $i++) {
            $arrayUserid = array('PID' => $rowSets[$i]['publisherId']);
            $arrayPubid = array('publisherId ' => $rowSets[$i]['publisherId']);
            $from = strtotime($rowSets[$i]['regTime']);
            $difference = $currentTime - $from;
            $noofdays = floor($difference / (60 * 60 * 24));
            $mailFlagSend = ($rowSets[$i]['mailSendFlag']);
            echo $rowSets[$i]['publisherId'] . "-------" . $noofdays . '--------' . $rowSets[$i]['mailSendFlag'] . '<br>';
            if ($noofdays > 14 && $noofdays <= 21 && $mailFlagSend != 11) {
                $datadetails = array('status' => 'Deactivated');
                $pubdatadetails = array('mailSendFlag' => 10);
                $updatePublisherMailSendFlag = $modelPlugin->getpublisherTable()->updatePublisherDetails($pubdatadetails, $arrayPubid);
                $verifyUserStatus = $modelPlugin->getsubscriptionDetailsTable()->updateuserstatus($datadetails, $arrayUserid);
            } else if ($noofdays > 14 && $mailFlagSend != 11) {
                $array = array('publisherId' => $rowSets[$i]['publisherId']);

                $savedeldetails = $modelPlugin->getpublisherTable()->deletePublisher($array);
                $publisherDetails = array('pubId' => $rowSets[$i]['publisherId'], 'email' => $rowSets[$i]['email'], 'extraEmail' => $rowSets[$i]['extraEmail'], 'password' => $rowSets[$i]['password'], 'fbName' => $rowSets[$i]['fbName'], 'fbLastName' => $rowSets[$i]['fbLastName'], 'fbId' => $rowSets[$i]['fbId'], 'hometown' => $rowSets[$i]['hometown'], 'fname' => $rowSets[$i]['fname'], 'profileImage' => $rowSets[$i]['profileImage'], 'phone' => $rowSets[$i]['phone'], 'loginTime' => $rowSets[$i]['loginTime'], 'regTime' => $rowSets[$i]['regTime'], 'rating' => $rowSets[$i]['rating'], 'fbuser' => $rowSets[$i]['fbuser'], 'sfpuser' => $rowSets[$i]['sfpuser'], 'Walkthrough' => $rowSets[$i]['Walkthrough'], 'mailSendFlag' => $rowSets[$i]['mailSendFlag'], 'checksum' => $rowSets[$i]['checksum']);
                $savedeldetails = $modelPlugin->getpublisherbackupTable()->insertdeluser($publisherDetails);
            }
        }
    }
}

?>
