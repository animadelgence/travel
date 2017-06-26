<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Session\Config\StandardConfig;
use Zend\Session\SessionManager;

class ProfileController extends AbstractActionController {

    protected $publisherTable;
    protected $sessionid;
    public function __construct()
    {
        $userSession 	= 	new Container('loginId');
        $this->sessionid 	= 	$userSession->offsetGet('loginId');
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $dynamicPath = $protocol.$_SERVER['HTTP_HOST'];
        if($this->sessionid =="")
        {
        header("Location:".$dynamicPath);
            exit;
        }
    }

    public function useraccountdetailsAction() {
        $this->layout('layout/userlayout.phtml');
        $modelPlugin = $this->modelplugin();
        $plugin = $this->routeplugin();
        $dynamicPath = $plugin->dynamicPath();
        $jsonArray = $plugin->jsondynamic();
        $currentPageURL = $plugin->curPageURL();
        $currentPageURL = $plugin->curPageURL();
        $href = explode("/", $currentPageURL);
        $controller = @$href[3];
        $action = @$href[4];
        $pluginTrack = $this->usertrackplugin();
        $addtoUserTracker =  $pluginTrack->fulldaytrack($this->sessionid,$action,0);
        if ($this->sessionid != "") {
            $conditionpublisherarray = array('publisherId' => $this->sessionid);
            $result = $modelPlugin->getpublisherTable()->joinquery($conditionpublisherarray);
            $loggedInUserName = "";
            $ProfilePicture = "";
            $email = "";
            $loggedInUserName = $result[0]['fname'];
            if ($result[0]['fbId'] != "") {
                //$loggedInUserName = $result[0]['fbName'] . " " . $result[0]['fbLastName'];
                if ($result[0]['profileImage'] == '') {
                    $ProfilePicture = "https://graph.facebook.com/" . $result[0]['fbId'] . "/picture?width=135&height=135";
                } else {
                    $ProfilePicture = $result[0]['profileImage'];
                }
            } else {
                //$loggedInUserName = $result[0]['fname'];
                if ($result[0]['profileImage'] == '') {
                    $ProfilePicture = $dynamicPath . "/img/user/profile-default.png";
                } else {
                    $ProfilePicture = $result[0]['profileImage'];
                }
            }
            if ($result[0]['email'] == "undefined") {
                $email = "";
            } else {
                $email = $result[0]['email'];
            }
            $todayDate = date("Y-m-d");
            $expireDate = $result[0]['expireDate'];
            $date1 = date_create($todayDate);
            $date2 = date_create($expireDate);
            $diff = date_diff($date1, $date2);
            $remainingdays = $diff->format("%a days");
            $this->layout()->setVariables(array('sid' => $this->sessionid, 'dynamicPath' => $dynamicPath, 'ProfilePicture' => $ProfilePicture, 'loggedInUserName' => $loggedInUserName, 'controller' => $controller, 'action' => $action,'userNotify'=>$result[0]['redirectuser'],'jsonArray'=>$jsonArray));

            return new ViewModel(array('dynamicPath' => $dynamicPath, 'sid' => $this->sessionid, 'result' => $result, 'remainingdays' => $remainingdays, 'loggedInUserName' => $loggedInUserName, 'ProfilePicture' => $ProfilePicture, 'email' => $email, 'jsonArray' => $jsonArray));
        } else {
            $this->redirect()->toRoute('application/default');
        }
    }

    public function changedetailsAction() {

        $plugin = $this->routeplugin();
        $modelPlugin = $this->modelplugin();
        $phpprenevt = $this->phpinjectionpreventplugin();  
        $conditionpublisherarray = array('publisherId' => $this->sessionid);
        $publishdetails = $modelPlugin->getpublisherTable()->selectEmail($conditionpublisherarray);
        //print_r($publishdetails);exit;
        $fullName = $phpprenevt->stringReplace($_POST['accountName']);
        $splitFullNameBySpace = explode(" ", $fullName);
        $firstName = $splitFullNameBySpace[0];
        
        array_shift($splitFullNameBySpace);
        $lastname = implode($splitFullNameBySpace, " ");
        //echo $firstName."||".$lastname;exit;
        
            $data = array(
                'fbName' => $firstName,
                'fbLastName' => $lastname,
                'phone' => $phpprenevt->stringReplace($_POST['phoneNo']),
                'fname' =>$fullName
            );
            $updatePublisherData = $modelPlugin->getpublisherTable()->updateuser($data, $conditionpublisherarray);
       
        
        exit;
    }

    public function changepasswordAction() {
        $plugin = $this->routeplugin();
        $modelPlugin = $this->modelplugin();
        $phpprenevt = $this->phpinjectionpreventplugin();  
        $response = array();
        $conditionpublisherarray = array('publisherId' => $this->sessionid);
        $currentpasword = $phpprenevt->stringReplace($_POST['currentPassword']);
        $publisherDetails = $modelPlugin->getpublisherTable()->selectEmail($conditionpublisherarray);

        $passcheck = password_verify($currentpasword, $publisherDetails[0]['password']);
        //echo $pass;exit;
        //if ($publisherDetails[0]['password'] != $pass) {
        if ($passcheck == false) {
            $response['error'] = 1;
            $response['errorMessage'] = "You current password is wrong";
        } else {
            //$passnew = $plugin->encrypt_decrypt('encrypt', $_POST['newPassword']);
            $passnew = password_hash($phpprenevt->stringReplace($_POST['newPassword']), PASSWORD_BCRYPT);
            $data = array(
                'password' => $passnew
            );
            $updateData = $modelPlugin->getpublisherTable()->updateuser($data, $conditionpublisherarray);
            $response['success'] = 1;
            $response['successMessage'] = "Password updated";
        }

        echo json_encode($response);
        exit;
    }

    public function saveimageAction() {
        $plugin = $this->routeplugin();
        $modelPlugin = $this->modelplugin();
        $uploadPlugin = $this->imageupload();
        $res = array();
        $request1 = $this->getRequest()->getPost();
        $filename = $request1['filename'];
        //$getParam = $request1['param'];
        $request = $this->getRequest();
        $files = $request->getFiles()->toArray();
        $fileName = $files[$filename]['name'];
        //print_r($fileName);exit;
        $fileType = $files[$filename]['type'];
        $fileType = strtolower($fileType);
        $fileSize = ($files[$filename]['size'] / 1024) / 1024;
       
            $PID = $this->sessionid;
            if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/img/profileImage/' . $PID)) {
                @mkdir($_SERVER['DOCUMENT_ROOT'] . '/img/profileImage/' . $PID, 0777, true);
                chmod($_SERVER['DOCUMENT_ROOT'] . '/img/profileImage/' . $PID, 0777);
            }
            $folderName = '/img/profileImage/' . $PID . '/';
            
            $result = $uploadPlugin->upload($fileSize, $fileName, $files[$filename]['error'], $folderName,"",$fileType);
            $input = json_decode($result, true);
            if($input['exterror'] == "0"){
                $data = array('profileImage' => $input['originalPath']);
                $sid = array('publisherId' => $PID);
                $updatedimage = $modelPlugin->getpublisherTable()->updateuser($data, $sid);
                $res['imagepath'] = $input['originalPath'];
            }
            $res['exterror'] = $input['exterror'];
            echo json_encode($res);

        exit;
    }

}
