<?php

namespace Admin\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use Zend\Session\Container;

 class UserregistrationController extends AbstractActionController
 {
     public function __construct() {

        $userSessionAdmin 	= 	new Container('username');
		$sessionidAdmin 	= 	$userSessionAdmin->offsetGet('adminID');
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        $dynamicPath = $protocol.$_SERVER['HTTP_HOST'];
        if($sessionidAdmin == "")
		{
		header("Location:".$dynamicPath."/adminlogin/login");
			exit;
		}
     }

     public function userdetailsAction(){

              $this->layout('layout/adminlayout');
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
		      $currentPageURL = $plugin->curPageURL();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action));
              $publisherdet = $modelPlugin->getpublisherTable()->joinquery();
		      return new ViewModel(array('publisherdet'=>$publisherdet));

     }
     public function loginasuserAction(){
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
		      $dynamicPath = $plugin->dynamicPath();
              $userid = $this->getEvent()->getRouteMatch()->getParam('id');
              $userSession = new Container('loginId');
              if($userSession->offsetExists('loginId')){
                  unset($userSession->loginId);
              }
              $userSession->loginId = $userid;

              return $this->redirect()->toUrl($dynamicPath . "/d/allcampaign");
     }
     public function usereditAction(){

              $this->layout('layout/adminlayout');
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
		      $currentPageURL = $plugin->curPageURL();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action));
              $userid = $this->getEvent()->getRouteMatch()->getParam('id');
		      $publisherdet = $modelPlugin->getpublisherTable()->edituserdetails(array('publisherId'=>$userid));
		      return new ViewModel(array('publisherdet'=>$publisherdet));

     }
     public function usereditsubmitAction(){
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
              $dynamicPath = $plugin->dynamicPath();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $userid = $phpprenevt->stringReplace($_POST['userid']);
              $userEmail = $phpprenevt->stringReplace($_POST['userEmail']);
              $userStatus = $phpprenevt->stringReplace($_POST['userStatus']);
              $checkbox = $phpprenevt->stringReplace($_POST['checkbox']);
              $query = array('PID'=>$userid);
              $fetchquery= $modelPlugin->getsubscriptionDetailsTable()->fetchall($query);
              if($userStatus == "Activated"){
                  if($checkbox == ""){
                      $payMode = $phpprenevt->stringReplace($_POST['payMode']); //subscription type
                      $autorenew = $phpprenevt->stringReplace($_POST['autorenew']); //autoRenew radio-button
                      $autoRenewDate = $phpprenevt->stringReplace($_POST['autoRenewDate']); //autoRenew date
                      $customDate = $phpprenevt->stringReplace($_POST['customDate']); //start date
                      $payMethod = $phpprenevt->stringReplace($_POST['payMethod']); //license plan
                      if($autorenew == "setAutoRenew"){
                          $autorenewDb = "1";
                          $autoRenewDateDb = $autoRenewDate;
                      }
                      else{
                          $autorenewDb = "0";
                          $autoRenewDateDb = "";
                      }

                      if($customDate != ""){
                          $startDate = $customDate;
                      }
                      else{
                          $startDate = date('Y-m-d');
                      }
                      $date = strtotime($startDate);
                      if($payMode == "Monthly"){
                              $endDate = date("Y-m-d", strtotime("+30 days", $date));
                      }elseif($payMode == "Yearly"){
                              $endDate = date("Y-m-d", strtotime("+365 days", $date));
                      }else{
                              $endDate = date("Y-m-d", strtotime("+14 days", $date));
                      }
                  }
                  else{
                      $startDate = date('Y-m-d');
                      $date ="2050-12-31";
                      $endDate = date("Y-m-d", strtotime($date));
                      $payMode = "Upto 2050";
                  }
                  if($fetchquery != (array())){
                      $subwhere=array('PID'=>$userid); $subdata=array('status'=>$userStatus,'subscriptionType'=>$payMode,'Subscriptiondate'=>$startDate,'expireDate'=>$endDate,'licensePlan'=>$payMethod,'autoRenew'=>$autorenewDb,'autoRenewDate'=>$autoRenewDateDb);
                      $subscriptiondet = $modelPlugin->getsubscriptionDetailsTable()->updateuserstatus($subdata,$subwhere);
                  }
                  else{
                      $insertsub=array('PID'=>$userid,'status'=>$userStatus,'subscriptionType'=>$payMode,'Subscriptiondate'=>$startDate,'expireDate'=>$endDate,'licensePlan'=>$payMethod);
                      $subscriptiondet = $modelPlugin->getsubscriptionDetailsTable()->saveAll($insertsub);
                  }
              }
              elseif($userStatus == "Deactivated"){
                      if($fetchquery != (array())){
                          $where = array('PID'=>$userid);
                          $data=array('status'=>$userStatus);
                          $deactivedet = $modelPlugin->getsubscriptionDetailsTable()->updateuserstatus($data,$where);
                      }
                      else{
                          $data=array('PID'=>$userid,'status'=>$userStatus);
                          $deactivedet = $modelPlugin->getsubscriptionDetailsTable()->saveAll($data);
                      }
                  $unpubdata = array('publishStatus'=>'unpublished');
                  $unpublish = $modelPlugin->getuserTemplateTable()->updateUserTemplate($unpubdata,$where);
              }
              return $this->redirect()->toRoute('userregistration', array(
				      'controller' => 'userregistration',
				      'action' => 'userdetails'));

     }
     public function userbackupAction(){
              $modelPlugin = $this->modelplugin();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $userid = $phpprenevt->stringReplace($_POST['hidden_id']);
              $fetchpubdetails = $modelPlugin->getpublisherTable()->joinquery(array('publisherId'=>$userid));

              $publisherdet = array('pubId'=>$fetchpubdetails[0]['publisherId'],'email'=>$fetchpubdetails[0]['email'],'extraEmail'=>$fetchpubdetails[0]['extraEmail'],'password'=>$fetchpubdetails[0]['password'],'fbName'=>$fetchpubdetails[0]['fbName'],'fbLastName'=>$fetchpubdetails[0]['fbLastName'],'fbId'=>$fetchpubdetails[0]['fbId'],'hometown'=>$fetchpubdetails[0]['hometown'],'fname'=>$fetchpubdetails[0]['fname'],'profileImage'=>$fetchpubdetails[0]['profileImage'],'phone'=>$fetchpubdetails[0]['phone'],'loginTime'=>$fetchpubdetails[0]['loginTime'],'regTime'=>$fetchpubdetails[0]['regTime'],'rating'=>$fetchpubdetails[0]['rating'],'fbuser'=>$fetchpubdetails[0]['fbuser'],'sfpuser'=>$fetchpubdetails[0]['sfpuser'],'Walkthrough'=>$fetchpubdetails[0]['Walkthrough'],'mailSendFlag'=>$fetchpubdetails[0]['mailSendFlag'],'checksum'=>$fetchpubdetails[0]['checksum'],'flag'=>'deleted by Admin');

              $savedeldetails = $modelPlugin->getpublisherbackupTable()->insertdeluser($publisherdet);

              $pid = array('PID'=>$userid);
              $fetchtemplate = $modelPlugin->getuserTemplateTable()->fetchall($pid);
              $unpubdata = array('publishStatus'=>'unpublished');
              $unpublish = $modelPlugin->getuserTemplateTable()->updateUserTemplate($unpubdata,$pid);
              $num = count($fetchtemplate);
              for ($i = 0; $i < $num; $i++) {
              $templatedata = array('PID'=>$fetchtemplate[$i]['PID'],'tempId'=>$fetchtemplate[$i]['tempId'],'appId'=>$fetchtemplate[$i]['appId'],'appSecret'=>$fetchtemplate[$i]['appSecret'],'campaignNumber'=>$fetchtemplate[$i]['campaignNumber'],'templateLink'=>$fetchtemplate[$i]['templateLink'],'campaignName'=>$fetchtemplate[$i]['campaignName'],'webUrl'=>$fetchtemplate[$i]['webUrl'],'facebookUrl'=>$fetchtemplate[$i]['facebookUrl'],'wordpressUrl'=>$fetchtemplate[$i]['wordpressUrl'],'domainUrl'=>$fetchtemplate[$i]['domainUrl'],'facebookNo'=>$fetchtemplate[$i]['facebookNo'],'facebookPage'=>$fetchtemplate[$i]['facebookPage'],'facebookTabName'=>$fetchtemplate[$i]['facebookTabName'],'publishStatus'=>$fetchtemplate[$i]['publishStatus'],'Duplicate'=>$fetchtemplate[$i]['Duplicate'],'pageId'=>$fetchtemplate[$i]['pageId'],'dashboardfolder'=>$fetchtemplate[$i]['dashboardfolder']);
              $savetempbkup = $modelPlugin->getusertemplatebackupTable()->saveUserdet($templatedata);
              }

              $deleteuser = $modelPlugin->getpublisherTable()->deletePublisher(array('publisherId'=>$userid));
              return $this->redirect()->toRoute('userregistration', array(
				      'controller' => 'userregistration',
				      'action'     => 'userdetails'));

     }
     public function userbackupdetAction(){

              $this->layout('layout/adminlayout');
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
              $currentPageURL = $plugin->curPageURL();
              $href = explode("/", $currentPageURL);
              $controller = @$href[3];
              $action = @$href[4];
              $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action));
              $publisherdet = $modelPlugin->getpublisherbackupTable()->fetchdeluser();
              return new ViewModel(array('publisherdet'=>$publisherdet));

     }
     public function adduserAction(){

              $this->layout('layout/adminlayout');
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
              $currentPageURL = $plugin->curPageURL();
              $href = explode("/", $currentPageURL);
              $controller = @$href[3];
              $action = @$href[4];
              $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action));
              return new ViewModel();

     }
     public function saveuserAction(){
              $plugin = $this->routeplugin();
              $modelPlugin = $this->modelplugin();
              $mailplugin = $this->mailplugin();
              $jsonArray = $plugin->jsondynamic();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $email =  $phpprenevt->stringReplace(trim($_POST['email']));
              $password = $phpprenevt->stringReplace(trim($_POST['password']));
              $password = password_hash($password,PASSWORD_BCRYPT);

              $usermailArray = array('email' => $email);
              $chekmail = $modelPlugin->getpublisherTable()->selectEmail($usermailArray);
              if (count($chekmail) == 0){

                   $startDate = date('Y-m-d');
                   $date = strtotime($startDate);
                   $endDate = date("Y-m-d", strtotime("+14 days", $date));
                   $datapub=array('email'=>$email, 'password'=>$password, 'fname'=>'Smartuser', 'sfpuser'=>"1",'regTime'=>date('Y-m-d h:i:s'), 'Walkthrough'=>"0", 'mailSendFlag'=>"1");
                   $lastinsertid = $modelPlugin->getpublisherTable()->saveAll($datapub);
                  //echo $lastinsertid; exit;
                   $datasub=array('PID'=>$lastinsertid,'licensePlan'=>'Free Trial','subscriptionType'=>'Monthly','status'=>"Activated", 'Subscriptiondate'=>$startDate,'Subscriptiontime'=>date('h:i:s'),'expireDate'=>$endDate);
                   $insertsub = $modelPlugin->getsubscriptionDetailsTable()->saveAll($datasub);

                  $coockievalue = password_hash($lastinsertid, PASSWORD_BCRYPT);
                  $cookieArray = array('cookievalue' => $coockievalue,"chkcookie"=> strrev($coockievalue));
                  $updateId = array('publisherId' => $lastinsertid);
                  $contentone = $modelPlugin->getpublisherTable()->updateuser($cookieArray, $updateId);

                   $dynamicPath = "http://" . $jsonArray['domain']['domain_name'];
                   $from = $jsonArray['sendgridaccount']['addfrom'];

                   $checknewmail = $modelPlugin->getpublisherTable()->selectEmail($usermailArray);
                   if (count($checknewmail) == 1){

                      $encryptedPassword = base64_encode("#$#" . base64_encode(base64_encode($lastinsertid . rand(10, 100)) . "###" . base64_encode($lastinsertid) . "###" . base64_encode($userid . rand(10, 100)) . "###" . base64_encode(base64_encode($lastinsertid . rand(10, 100)))) . "#$#");
                      $activationLink = $dynamicPath . "/Gallery/galleryview/" . $encryptedPassword;
                      $keyArray = array('mailCatagory' => 'R_MAIL');
                      $getMailStructure = $modelPlugin->getconfirmMailTable()->fetchall($keyArray);
                      $getmailbodyFromTable = $getMailStructure[0]['mailTemplate'];
                      $activationLinkreplace = str_replace("|ACTIVATIONLINK|", $activationLink, $getmailbodyFromTable);
                      $mailBody = str_replace("|DYNAMICPATH|", $dynamicPath, $activationLinkreplace);
                      $subject = "Confirm your email address";
                      $mailfunction = $mailplugin->confirmationmail($email, $from, $subject, $mailBody);

                    }
              }
              else{
                      echo "error";
              }
              exit;

     }
     public function restoreuserAction(){
              $plugin = $this->routeplugin();
              $modelPlugin = $this->modelplugin();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $id = $phpprenevt->stringReplace($_POST['deleteId']);
              $data=array('deleteId'=>$id);
              $passwrd = 'Testing1@';
              $passwrd = password_hash($passwrd,PASSWORD_BCRYPT);

              //check if email already exists(start)
              $fetchuserdata = $modelPlugin->getpublisherbackupTable()->fetchall($data);
              $email = array('email'=>$fetchuserdata[0]['email']);
              $chkemail = $modelPlugin->getpublisherTable()->fetchall($email);
              if(empty($chkemail)){
              //check if email already exists(end)

              $pubid = $fetchuserdata[0]['pubId'];
              $datainsert=array('email'=>$fetchuserdata[0]['email'],'password'=>$passwrd,'fname'=>$fetchuserdata[0]['fname']);
              $insertuser = $modelPlugin->getpublisherTable()->saveAll($datainsert);
              $dataid = array('PID'=>$insertuser);
              $where = array('PID'=>$pubid);
              $updatesub = $modelPlugin->getsubscriptionDetailsTable()->updateuserstatus($dataid,$where);
              $delbackup = $modelPlugin->getpublisherbackupTable()->deleteBackup($data);
              }
              else{
                  echo "error";
              }
              exit;
     }

 }
?>
