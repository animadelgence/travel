<?php

namespace Authorization\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Request;
use Zend\Session\Container;

class OldregistrationController extends AbstractActionController {

    public function registerAction(){
       $modelPlugin = $this->modelplugin();
       $dynamicPath = $modelPlugin->dynamicPath();
       $getEmail = $_GET['email'];
       $getPassword = $_GET['password'];
       $usermailArray = array('email' => $getEmail);
       $chekmail = $modelPlugin->getpublisherTable()->selectEmail($usermailArray);
       if (count($chekmail) == 0){
       $getName= $_GET['name'];
       $mailVerification = $_GET['mailsendflag'];
       $getSubscriptionStartdate = $_GET['substart'];
       $getSubscriptionEnddate = $_GET['subend'];
       $getSubscriptionStatus = $_GET['substatus'];
       $password = password_hash($getPassword,PASSWORD_BCRYPT);
        $datapub=array('email'=>$getEmail, 'password'=>$password, 'fname'=>$getName, 'sfpuser'=>"1",'regTime'=>date('Y-m-d h:i:s'), 'Walkthrough'=>"0", 'mailSendFlag'=>$mailVerification,'redirectuser'=>'old');
        $lastinsertid = $modelPlugin->getpublisherTable()->saveAll($datapub);
        $datasub=array('PID'=>$lastinsertid,'status'=>  $getSubscriptionStatus, 'Subscriptiondate'=>$getSubscriptionStartdate,'expireDate'=>$getSubscriptionEnddate);
        $insertsub = $modelPlugin->getsubscriptionDetailsTable()->saveAll($datasub);

              $userSession = new Container('loginId');
              if($userSession->offsetExists('loginId')){
                  unset($userSession->loginId);
              }
              $userSession->loginId = $lastinsertid;
              return $this->redirect()->toUrl($dynamicPath . "/d/allcampaign");
        }
        else
        {
          $passcheck = password_verify($getPassword, $chekmail[0]['password']);
           if ($passcheck == true) {
            $userSession = new Container('loginId');
              if($userSession->offsetExists('loginId')){
                  unset($userSession->loginId);
              }
              $userSession->loginId = $chekmail[0]['publisherId'];
              return $this->redirect()->toUrl($dynamicPath . "/d/allcampaign");
           }
            else
            {
                return $this->redirect()->toUrl($dynamicPath);
            }
        }
    }

}

