<?php

/* 
 * @Author: Maitrayee and Rajyasree
 * @Date:   2017-02-2 16:46:35
 * @Last Modified by: Maitrayee
 * @Last Modified time: 2017-04-25 12:52:26
 * @version : 1.0.0
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


namespace Authorization\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Request;
use Zend\Session\Container;

class AuthorizationloginController extends AbstractActionController {

    protected $sessionid;

    public function __construct() {

        $userSession = new Container('loginId');
        $this->sessionid = $userSession->offsetGet('loginId');
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    }

    public function loginAction() {
        
        /* function for checking the user mail password */
       
        $modelPlugin = $this->modelplugin();
        $dynamicPath = $modelPlugin->dynamicPath();
        $phpprenevt = $this->phpinjectionpreventplugin();  
        $uname = $phpprenevt->stringReplace($_POST['login_email']);
        $pass = $phpprenevt->stringReplace($_POST['login_password']);
        $remember = $phpprenevt->stringReplace($_POST['autologin']);
        if ($remember == "") {
            $remember = 0;
        }
        $dataarrayforvalidation = array('email' => $uname);
        $contentone = $modelPlugin->getpublisherTable()->selectEmail($dataarrayforvalidation);
        //print_r($_COOKIE);exit;
        $passcheck = password_verify($pass, $contentone[0]['password']);
        $usid = $contentone[0]['publisherId'];
        if (empty($contentone[0]['cookievalue'])) {
            $coockievadd = password_hash($usid, PASSWORD_BCRYPT);
            $cookieArray = array('cookievalue' => $coockievadd, "chkcookie" => strrev($coockievadd));
            $publisherIdArray = array('publisherId' => $usid);
            $updatecookie = $modelPlugin->getpublisherTable()->updateuser($cookieArray, $publisherIdArray);
            $coockievalue = $coockievadd;
            $cookiecheck = $coockievadd;
        } else {
            $coockievalue = $contentone[0]['cookievalue'];
            $cookiecheck = strrev($contentone[0]['chkcookie']);
        }

        $chkcookie = password_verify($usid, $coockievalue);
        $chkcookiewithcookie = password_verify($usid, $cookiecheck);
        $publisherId = array('PID' => $usid);
        $currentTime = time();
        $from = strtotime($contentone[0]['regTime']);
        $difference = $currentTime - $from;
        $no_of_days = floor($difference / (60 * 60 * 24));
        $remainingdays = (14 - $no_of_days);
        $res['noOfDays'] = $remainingdays;
        $res['mailFlagSend'] = $contentone[0]['mailSendFlag'];

        $checkStatus = $modelPlugin->getsubscriptionDetailsTable()->fetchall($publisherId);

        if (!empty($checkStatus)) {
            $res['userStatus'] = $checkStatus[0]['status'];
        }
        //check cookie and password then let them go
        if ($chkcookiewithcookie == true && $chkcookie == true && $passcheck == true) {
            $res['noOfContetnone'] = $passcheck;
        } else {
            $res['noOfContetnone'] = false;
        }
        $res['userId'] = $usid;

        $ip = $_SERVER['REMOTE_ADDR']? : ($_SERVER['HTTP_X_FORWARDED_FOR']? : $_SERVER['HTTP_CLIENT_IP']);
        $tempNam = $phpprenevt->stringReplace($_POST['tempNamlogin']);
        $res['tempNam'] = $tempNam;


        if ((!empty($contentone)) && ($contentone[0]['mailSendFlag']) != 10 && $passcheck == true && $chkcookiewithcookie == true && $chkcookie == true) {

             if (($contentone[0]['mailSendFlag'] == '1' && $remainingdays >= 0) || $contentone[0]['mailSendFlag'] == '11') {
                $user_session = new Container('loginId');
                $user_session->loginId = $usid;
                $config_username = password_hash($uname, PASSWORD_BCRYPT);
                if ($remember == 1) {
                    $cookie_name = 'siteAuth';  
                    $cookie_time = (3600 * 24 * 30); // 30 days
                    $password_hash = password_hash($pass, PASSWORD_BCRYPT);
                    $key = '1234447890123450';
                    $encrypted = $this->encrypt($usid, $key);
                    $newversion =base64_encode($encrypted);
                    
                     setcookie($cookie_name, 'usid=' . $newversion . '&usr=' . $config_username . '&hash=' . $password_hash, time() + $cookie_time, "/");
                   
                }
            }
            $mailflag = $contentone[0]['mailSendFlag'];
        }
        if (($mailflag == 1 && $remainingdays >= 0) || $mailflag == 11) {

            if ($tempNam) {
                $res['redirection'] = "editor";
            } else {
                $res['redirection'] = "dashboard";
            }
        }

        echo json_encode($res);
        exit;
    }


    public function resendmailAction() {

        /* for activationmail send */
        
        $modelPlugin = $this->modelplugin();
        $mailplugin = $this->mailplugin();
        $phpprenevt = $this->phpinjectionpreventplugin();  
        $jsonArray = $modelPlugin->jsondynamic();
        $userid = $this->sessionid;
        $dynamicPath = $protocol . $jsonArray['domain']['domain_name'];
        $from = $jsonArray['sendgridaccount']['addfrom'];

        $encryptedPassword = base64_encode("#$#" . base64_encode(base64_encode($userid . rand(10, 100)) . "###" . base64_encode($userid) . "###" . base64_encode($userid . rand(10, 100)) . "###" . base64_encode(base64_encode($userid . rand(10, 100)))) . "#$#");
        $buttonclick = $dynamicPath . "/Gallery/galleryview/" . $encryptedPassword;
        $mail_link = "<a href='" . $buttonclick . "' style='background-color: #04ad6a; border: medium none;  border-radius: 19px; padding: 12px; color: #fff; text-align: center; text-decoration: none; text-transform: uppercase;'>Click here</a>";

        if (empty($userid)) {
            $userid = $phpprenevt->stringReplace($_POST['loginId']);
        }
        $publisheridarray = array('publisherId' => $userid);
        $selectid = $modelPlugin->getpublisherTable()->selectEmail($publisheridarray);
        $mail = $selectid[0]['email'];
        $keyArray = array('mailCatagory' => 'R_MAIL');
        $getMailStructure = $modelPlugin->getconfirmMailTable()->fetchall($keyArray);
        $getmailbodyFromTable = $getMailStructure[0]['mailTemplate'];

        $activationLinkreplace = str_replace("|ACTIVATIONLINK|", $mail_link, $getmailbodyFromTable);
        $mailBody = str_replace("|DYNAMICPATH|", $dynamicPath, $activationLinkreplace);
        $subject = "Confirm your email address";
        $mailfunction = $mailplugin->confirmationmail($mail, $from, $subject, $mailBody);
        $res['response'] = 1;
        echo json_encode($res);
        exit;
    }

    public function forgetpasswordAction() {
        
        /* functionality for recovering password */
        
        $modelPlugin = $this->modelplugin();
        $dynamicPath = $modelPlugin->dynamicPath();
        $phpprenevt = $this->phpinjectionpreventplugin();  
        $mailplugin = $this->mailplugin();
        $jsonArray = $modelPlugin->jsondynamic();
        $email = $phpprenevt->stringReplace($_POST['eid']);
        $publisheremailarray = array('email' => $email);
        $chkemail = $modelPlugin->getpublisherTable()->selectEmail($publisheremailarray);
        $currentDatetime = strtotime(date("Y-m-d h:i:s"));
        $restpassallow = 0;
        if ($chkemail[0]['forgetpassTimestamp']) {
            $databaseDatetime = strtotime($chkemail[0]['forgetpassTimestamp']);
            $all = $currentDatetime - $databaseDatetime;
            $day = round(($all % 604800) / 86400);
            $hours = round((($all % 604800) % 86400) / 3600);
            $m = round(((($all % 604800) % 86400) % 3600) / 60);

            if ($day <= 0) {
                if ($hours <= 0) {
                    if ($m <= 15) {
                        $restpassallow = 1;
                        $contentone['minutes'] = 15 - $m;
                    }
                }
            }
        }
        if (count($chkemail) == 0) {
            $contentone['data'] = 0;
        } else if ($restpassallow == 1) {
            $contentone['data'] = 2;
        } else {
            $id = $chkemail[0]["publisherId"];
            $pass1 = password_hash($email, PASSWORD_BCRYPT);
            $arraypass = str_replace('/', '', $pass1);
            $buttonclick = $dynamicPath . "/Gallery/galleryview/resetpassword/" . $arraypass;
            $mail_link = "<a href='" . $buttonclick . "' style='background-color: #04ad6a; border: medium none; border-radius: 19px; padding: 12px; color: #fff; text-align: center; text-decoration: none; text-transform: uppercase;'>Click here</a>";
            $subject = "[Smartfanpage] Set your password";
            $from = $jsonArray['sendgridaccount']['addfrom'];
            $keyArray = array('mailCatagory' => 'F_MAIL');
            $getMailStructure = $modelPlugin->getconfirmMailTable()->fetchall($keyArray);
            $getmailbodyFromTable = $getMailStructure[0]['mailTemplate'];
            $mailLinkreplace = str_replace("|MAILLINK|", $mail_link, $getmailbodyFromTable);
            $mailBody = str_replace("|DYNAMICPATH|", $dynamicPath, $mailLinkreplace);
            $fogetPasswordMail = $mailplugin->confirmationmail($email, $from, $subject, $mailBody);
            $keyArray = array('publisherId' => $id);
            $dataForForget = array('forgetpassword' => $arraypass, 'forgetpassTimestamp' => date("Y-m-d h:i:s"));
            $contentone1 = $modelPlugin->getpublisherTable()->updateuser($dataForForget, $keyArray);
            $contentone['data'] = $contentone1;
            $user_session->loginId = ($_SESSION['loginId']);
            $user_session = new \Zend\Session\Container('loginId');
            $user_session->getManager()->destroy();
        }
        echo json_encode($contentone);
        exit;
    }
    public function encrypt($data, $key){
    return base64_encode(
    mcrypt_encrypt(
        MCRYPT_RIJNDAEL_128,
        $key,
        $data,
        MCRYPT_MODE_CBC,
        "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0"
    )
);
    }
}

?>
