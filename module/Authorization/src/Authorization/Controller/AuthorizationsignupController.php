<?php

namespace Authorization\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Request;
use Zend\Session\Container;

class AuthorizationsignupController extends AbstractActionController {

    public function __construct() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    }
    public function emailsignupAction() {
        $plugin = $this->routeplugin();
        $dynamicPath = $plugin->dynamicPath();
        $modelPlugin = $this->modelplugin();
        $phpprenevt = $this->phpinjectionpreventplugin();  
        $checksum = $phpprenevt->stringReplace($_POST['checksum']);
        $email = $phpprenevt->stringReplace($_POST['eid']);
        $fname = $phpprenevt->stringReplace($_POST['fname']);
        $phone = $phpprenevt->stringReplace($_POST['phone']);
        $pass1 = password_hash($email, PASSWORD_BCRYPT);
        $userextramailarray = array('email' => $email);
        $todaysDate = date('Y-m-d');
        $expire = date('Y-m-d', strtotime($todaysDate . ' + 14 days'));
        $nextEmail = $modelPlugin->getpublisherTable()->selectEmail($userextramailarray);
        $insertdataarray = array('email' => $email, 'password' => $pass1, 'fname' => $fname, 'phone' => $phone, 'regTime' => date('y-m-d h:i:s'), 'sfpuser' => '1', 'checksum' => $checksum);

        
        if (count($nextEmail) > 0) {
            echo "404"; //THIS MEANS SFP USER ALREADY CONNECTED AS SECONDARY ACCOUNT
            exit;
        } else {
            if (trim($chkemail[0]['email']) == "") {
                $contentone = $modelPlugin->getpublisherTable()->saveAll($insertdataarray);
                $checkId = $modelPlugin->getpublisherTable()->selectEmail($userextramailarray);
               $subscriptionarray = array('PID' => $checkId[0]['publisherId'], 'status' => 'Activated', 'licensePlan' => 'Free Trial', 'Subscriptiondate' => $todaysDate, 'subscriptionType' => 'Monthly', 'Subscriptiontime' => date('h:i:s'), 'expireDate' => $expire);
                $savesubscription = $modelPlugin->getsubscriptionDetailsTable()->saveAll($subscriptionarray);
                echo $email;
                exit;
            } else {
                echo "alreadyexists";
                exit;
            }
        }
    }

// for get started
    public function updatepassForSignupAction() {
        $plugin = $this->routeplugin();
        $mailplugin = $this->mailplugin();
        $phpprenevt = $this->phpinjectionpreventplugin();  
        $jsonArray = $plugin->jsondynamic();
        $modelPlugin = $this->modelplugin();
        $email = trim($phpprenevt->stringReplace($_POST['eid']));
        $sourcepage =$phpprenevt->stringReplace($_POST['sourcepage']);
        $name_with_phoneno  =   trim($phpprenevt->stringReplace($_POST['name_with_phoneno']));
        $explodeDetails =   explode("|__|", $name_with_phoneno);
        $pass = trim($phpprenevt->stringReplace($_POST['pass']));
        $dynamicPath = $protocol . $jsonArray['domain']['domain_name'];
        $from = $jsonArray['sendgridaccount']['addfrom'];
        $pass = password_hash($pass, PASSWORD_BCRYPT);
        $updateArray = array();
        $usermailArray = array('email' => $email);
        $chekmail = $modelPlugin->getpublisherTable()->selectEmail($usermailArray);
        $newUserId = $chekmail[0]['publisherId'];
        $coockievalue = password_hash($newUserId, PASSWORD_BCRYPT);
        $cookieArray = array('cookievalue' => $coockievalue,"chkcookie"=> strrev($coockievalue));
        $publisherIdArray = array('publisherId' => $newUserId);
        $updatecookie = $modelPlugin->getpublisherTable()->updateuser($cookieArray, $publisherIdArray);
        
        $dataArrayUpdate = array('email' => $email, 'password' => $pass);
        
        $contentone = $modelPlugin->getpublisherTable()->updateuser($dataArrayUpdate, $publisherIdArray);
        $user_session = new Container('loginId');
        $user_session->loginId = $newUserId;
        $encryptedPassword = base64_encode("#$#" . base64_encode(base64_encode($newUserId . rand(10, 100)) . "###" . base64_encode($newUserId) . "###" . base64_encode($newUserId . rand(10, 100)) . "###" . base64_encode(base64_encode($newUserId . rand(10, 100)))) . "#$#");
          if($jsonArray['activecampaigns']['status'] == 'on')
        {
           $returnOutput   =   $this->signupToActivecampaignsOfEn($email,$explodeDetails[0],$explodeDetails[1],$sourcepage);
            $trackEvent     =   $this->trackSignupEvent($email);
            
        }
        if (count($chekmail) == 1) {
            $buttonclick = $dynamicPath . "/Gallery/galleryview/" . $encryptedPassword;
            $activationLink = "<a href='".$buttonclick."' style='background-color: #04ad6a; border: medium none; border-radius: 19px; padding: 12px; color: #fff; text-align: center; text-decoration: none; text-transform: uppercase;'>Click here</a>";
            
            $keyArray = array('mailCatagory' => 'R_MAIL');
            $getMailStructure = $modelPlugin->getconfirmMailTable()->fetchall($keyArray);
            $getmailbodyFromTable = $getMailStructure[0]['mailTemplate'];
            $activationLinkreplace = str_replace("|ACTIVATIONLINK|", $activationLink, $getmailbodyFromTable);
            $mailBody = str_replace("|DYNAMICPATH|", $dynamicPath, $activationLinkreplace);
            $subject = "Confirm your email address";
            $mailfunction = $mailplugin->confirmationmail($email, $from, $subject, $mailBody);
        }

        $output = json_decode($mailfunction);
        if (trim($output->message) === 'success') {

            $updateArray = array(
                'mailSendFlag' => '1'
            );
            $keyarray = array('publisherId' => $newUserId);
            $updatedValues = $modelPlugin->getpublisherTable()->updateuser($updateArray, $keyarray);
            echo $updatedValues;
        }
        echo $contentone;
        exit;
    }

//for frgetpassword
    public function resetpassAction() {
        $plugin = $this->routeplugin();
        $jsonArray = $plugin->jsondynamic();
        $phpprenevt = $this->phpinjectionpreventplugin();  
        $modelPlugin = $this->modelplugin();
        $email = trim($phpprenevt->stringReplace($_POST['emailid']));
        $pass = trim($phpprenevt->stringReplace($_POST['pass']));
        $pass = password_hash($pass, PASSWORD_BCRYPT);
        $usermailArray = array('email' => $email);
        $chkemail = $modelPlugin->getpublisherTable()->selectEmail($usermailArray);
        $newUserId = $chkemail[0]['publisherId'];
        $dataArrayUpdate = array('email' => $email, 'password' => $pass, 'forgetpassword' => '','forgetpassTimestamp' => '');
        $publisherIdArray = array('publisherId' => $newUserId);
        $contentone = $modelPlugin->getpublisherTable()->updateuser($dataArrayUpdate, $publisherIdArray);
        $user_session = new Container('loginId');
        $user_session->loginId = $newUserId;

        $dynamicpath = $plugin->dynamicPath();
        if ($contentone == 1) {
            return $this->redirect()->toUrl($dynamicpath . "/d/allcampaign");
        }
    }
    public function signupToActivecampaignsOfEn ($email,$name,$phoneno,$sourcepage) {
        $url = 'https://smartfanpage305.api-us1.com';
        $params = array(
            'api_key'      => '909ab1183616dbdc4a3396645e51a845aced22dc3afb75fcca031f7ca6ed4be6d28bf492',
            'api_action'   => 'contact_add',
            'api_output'   => 'serialize',
        );
        
        if($sourcepage == "en"){
        $post = array(
            'email'                    => $email,
            'first_name'               => $name,
            'phone'                    => $phoneno,
            // assign to lists:
            'p[11]'                   => 11,
            'status[11]'              => 1, 
            'instantresponders[1]' => 1, 
        );
        }else{
            $post = array(
            'email'                    => $email,
            'first_name'               => $name,
            'phone'                    => $phoneno,
            // assign to lists:
            'p[11]'                   => 4,
            'status[11]'              => 1, 
            'instantresponders[1]' => 1, 
        );
        }
        $query = "";
        foreach( $params as $key => $value ) $query .= $key . '=' . urlencode($value) . '&';
        $query = rtrim($query, '& ');
        $data = "";
        foreach( $post as $key => $value ) $data .= $key . '=' . urlencode($value) . '&';
        $data = rtrim($data, '& ');
        $url = rtrim($url, '/ ');
        $api = $url . '/admin/api.php?' . $query;
        $request = curl_init($api); 
        curl_setopt($request, CURLOPT_HEADER, 0); 
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($request, CURLOPT_POSTFIELDS, $data);
        curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
        $response = (string)curl_exec($request);
        curl_close($request);
        $result = unserialize($response);
        return $result;
        exit;

    }
    
    public function trackSignupEvent($email) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, "https://trackcmp.net/event");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, array(
          "actid"       => "223392463",
          "key"         => "6e2f9128dd5766c43157229a1884c7e674eda788",
          "event"       => "Trialsignup",
          "eventdata"   => "New Sign In Ocurred",
          "visit"       => json_encode(array(
            // If you have an email address, assign it here.
              "email" => $email,
              )),
        ));
        $result = curl_exec($curl);
        if ($result !== false) {
          $result = json_decode($result);
          return $result;
         } else {return curl_error($curl);
        }
    }

}


