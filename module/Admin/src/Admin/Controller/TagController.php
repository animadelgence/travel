<?php

namespace Admin\Controller;

 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;
 use Zend\Session\Container;

 class TagController extends AbstractActionController
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

     public function viewtagAction(){

              $this->layout('layout/adminlayout');
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
		      $currentPageURL = $plugin->curPageURL();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action));              $tagdata = $modelPlugin->getsmartfanpageTagTable()->fetchall();
              return new ViewModel(array('tagdata'=>$tagdata));

     }
     public function edittagAction(){

              $this->layout('layout/adminlayout');
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
		      $currentPageURL = $plugin->curPageURL();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action));
              $tagid = $this->getEvent()->getRouteMatch()->getParam('id');
		      $tagdata = $modelPlugin->getsmartfanpageTagTable()->fetchall(array('SFPtagId'=>$tagid));
		      return new ViewModel(array('tagdata'=>$tagdata));

     }
     public function tageditsubmitAction(){
              $modelPlugin = $this->modelplugin();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $plugin = $this->routeplugin();
              $tagid = $phpprenevt->stringReplace($_POST['tagid']);
              $tagName = $phpprenevt->stringReplace($_POST['tagName']);
              $tagDesc = $phpprenevt->stringReplace($_POST['tagDesc']);
              $replaceCode = $phpprenevt->stringReplace($_POST['replaceCode']);         $tagdata=array('tagName'=>$tagName,'tagDescription'=>$tagDesc,'replaceCode'=>$replaceCode);
              $where = array('SFPtagId'=>$tagid);
              $edittag= $modelPlugin->getsmartfanpageTagTable()->updatetag($tagdata,$where);
              return $this->redirect()->toRoute('tag', array(
				'controller' => 'tag',
				'action'     => 'viewtag'));
     }
     public function deltagAction(){
              $modelPlugin = $this->modelplugin();
              $plugin = $this->routeplugin();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $tagid = $phpprenevt->stringReplace($_POST['hidden_id']);
              $where = array('SFPtagId'=>$tagid);
              $deltagdata= $modelPlugin->getsmartfanpageTagTable()->deletetag($where);
              return $this->redirect()->toRoute('tag', array(
				'controller' => 'tag',
				'action'     => 'viewtag'));
     }
     public function addtagAction(){

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
     public function savetagAction(){
              $modelPlugin = $this->modelplugin();
              $phpprenevt = $this->phpinjectionpreventplugin();
              $newtagName = $phpprenevt->stringReplace($_POST['newtagName']);
              $newtagDesc = $phpprenevt->stringReplace($_POST['newtagDesc']);
              $newrepCode = $phpprenevt->stringReplace($_POST['newrepCode']);
              $query = array('tagName'=>$newtagName);
              $fetchtag = $modelPlugin->getsmartfanpageTagTable()->fetchall($query);
              if(empty($fetchtag)){
                      $data = array('tagName'=>$newtagName, 'tagDescription'=>$newtagDesc, 'replaceCode'=>$newrepCode);
                      $insertdata = $modelPlugin->getsmartfanpageTagTable()->inserttag($data);
              }
              else{
                      echo "error";
              }
              exit;

     }

 }
?>
