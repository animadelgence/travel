<?php

namespace Admin\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use Zend\Session\Container;


 class TemplateController extends AbstractActionController
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

     public function templateviewAction(){

              $this->layout('layout/adminlayout');
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
		      $currentPageURL = $plugin->curPageURL();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action));
              $templatedet = $modelPlugin->gettemplateTable()->fetchall();
		      return new ViewModel(array('templatedet'=>$templatedet));

     }
     public function templateeditAction(){

              $this->layout('layout/adminlayout');
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
		      $currentPageURL = $plugin->curPageURL();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action));
              $id = $this->getEvent()->getRouteMatch()->getParam('id');
              $tempid = array('templateId'=>$id);
		      $templatedet = $modelPlugin->gettemplateTable()->fetchall($tempid);
		      return new ViewModel(array('templatedet'=>$templatedet));

     }
     public function updatetemplateAction(){
              $modelPlugin = $this->modelplugin();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $id = $phpprenevt->stringReplace($_POST['tempId']);
              $tempName = $phpprenevt->stringReplace(trim($_POST['tempName']));
              $fileupload = $phpprenevt->stringReplace($_POST['fileupload']);
              $tempid = array('templateId'=>$id);

              //check template name
              $tempnamechk = array('templateViewName' => $tempName);
              $chekname = $modelPlugin->gettemplateTable()->fetchall($tempnamechk);
              $chekid = $chekname[0]['templateId'];

                  if ($chekid == $id || empty($chekname)){

                  //image resize and upload {start}
                  $uploadPlugin = $this->imageupload();
                  $request1 = $this->getRequest()->getPost();
                  $request= $this->getRequest();
     	          $files =  $request->getFiles()->toArray();
     	          $fileName = $files["fileupload"]['name'];
     	          $fileType = $files["fileupload"]['type'];
		          $fileType = strtolower($fileType);
		          $fileSize = ($files["fileupload"]['size']/1024)/1024;
                  if($fileType == 'image/png'||$fileType == 'image/jpg'||$fileType == 'image/jpeg')
                  {
                      if (!is_dir($_SERVER['DOCUMENT_ROOT'] . '/template/'. $request1['tempFolder']."/")) {
                          @mkdir($_SERVER['DOCUMENT_ROOT'] . '/template/'. $request1['tempFolder']."/", 0777, true);
                          chmod($_SERVER['DOCUMENT_ROOT'] . '/template/'. $request1['tempFolder']."/", 0777);
                      }
				      $folderName =  '/template/'. $request1['tempFolder']."/";
                      $result = $uploadPlugin->resizeUpload($fileSize,$fileName,$files["fileupload"]['error'],$folderName);
                      $input = json_decode($result, true);
                      $data = array('templateImage' => $input['originalPath']);
                      $updatedimage = $modelPlugin->gettemplateTable()->updateTemp($data,$tempid);
                      $res['exterror'] = $input['exterror'];
                      $res['imagepath'] = $input['originalPath'];
                      echo json_encode($res);
			      }
			      else
     		      {
     			      $res['exterror']='Sorry, '.$fileName.' is invalid, allowed extensions are : jpg,jpeg,gif,png';
			          echo json_encode($res);
     		      }
                  //image resize and upload {end}

              $rate = $phpprenevt->stringReplace(trim($_POST['conversionRate']));
              $link = $phpprenevt->stringReplace(trim($_POST['publishLink']));
              $data = array('templateViewName'=>$tempName,'conversionRate'=>$rate,'liveLink'=>$link);
              $templatedet = $modelPlugin->gettemplateTable()->updateTemp($data,$tempid);
              }else{
                  echo "error";
              }
              exit;

     }
     public function deltemplateAction(){
              $modelPlugin = $this->modelplugin();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $tempid = $phpprenevt->stringReplace($_POST['hidden_id']);
              $deleteuser = $modelPlugin->gettemplateTable()->deleteTemp(array('templateId'=>$tempid));
              return $this->redirect()->toRoute('template', array(
				      'controller' => 'template',
				      'action'     => 'templateview'));

     }
     public function migratetemplateAction(){
              $plugin = $this->routeplugin();
              $templateplugin = $this->templateCopyplugin();
              $templateId = $this->getEvent()->getRouteMatch()->getParam('id');
              $dynamicPath =  $templateplugin->dynamicPath();
              $jsonArray = $plugin->jsondynamic();
              $migratetest = $jsonArray['migratetemplate']['Test'];
              $migratelive = $jsonArray['migratetemplate']['Live'];
              $param = $this->getEvent()->getRouteMatch()->getParam('pId');
              $templatedet = $templateplugin->gettemplateTable()->fetchall(array('templateId'=>$templateId));
              $tempname = $templatedet[0]['templateName'];
              if($param=="live"){
                  $target = $migratelive.$templatedet[0]['templateName'];
              }
              else{
              $target = $migratetest.$templatedet[0]['templateName'];
              }
              $source = $_SERVER['DOCUMENT_ROOT'].'/template/'.$templatedet[0]['templateName'];
              $tempcopy = $templateplugin->full_copy($source,$target);
              return $this->redirect()->toRoute('template', array(
				      'controller' => 'template',
				      'action'     => 'templateview'));

     }
     public function hidetemplateAction(){
              $modelPlugin = $this->modelplugin();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $id = $phpprenevt->stringReplace($_POST['tempId']);
              $tempid = array('templateId'=>$id);
              $data = array('approval'=>'0');
              $templatedet = $modelPlugin->gettemplateTable()->updateTemp($data,$tempid);
              echo $templatedet; exit;

     }
     public function showtemplateAction(){
              $modelPlugin = $this->modelplugin();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $id = $phpprenevt->stringReplace($_POST['tempId']);
              $tempid = array('templateId'=>$id);
              $data = array('approval'=>'1');
              $templatedet = $modelPlugin->gettemplateTable()->updateTemp($data,$tempid);
              echo $templatedet; exit;
     }

}
?>
