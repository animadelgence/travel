<?php

namespace Front\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\Session\Config\StandardConfig;
use Zend\Session\SessionManager;

class GalleryController extends AbstractActionController {

    public function galleryviewAction() {
            $this->layout('layout/layout.phtml');
            $plugin = $this->routeplugin();
            $currentPageURL = $plugin->curPageURL();
            $dynamicPath = $plugin->dynamicPath();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action , "dynamicPath"=>$dynamicPath));
            return new ViewModel(array('dynamicPath' => $dynamicPath));
        
    }
    public function tourAction() {
            $this->layout('layout/layout.phtml');
            $plugin = $this->routeplugin();
            $dynamicPath = $plugin->dynamicPath();
                $currentPageURL = $plugin->curPageURL();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action, "dynamicPath"=>$dynamicPath));
            return new ViewModel(array('dynamicPath' => $dynamicPath));
    }
    public function offerAction() {
            $this->layout('layout/layout.phtml');
            $plugin = $this->routeplugin();
            $dynamicPath = $plugin->dynamicPath();
                $currentPageURL = $plugin->curPageURL();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action, "dynamicPath"=>$dynamicPath));
            return new ViewModel(array('dynamicPath' => $dynamicPath));
    }
    public function blogAction() {
            $this->layout('layout/layout.phtml');
            $plugin = $this->routeplugin();
            $dynamicPath = $plugin->dynamicPath();
            $currentPageURL = $plugin->curPageURL();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action, "dynamicPath"=>$dynamicPath));
            return new ViewModel(array('dynamicPath' => $dynamicPath));
    }
    public function contactAction() {
            $this->layout('layout/layout.phtml');
            $plugin = $this->routeplugin();
                $currentPageURL = $plugin->curPageURL();
                $dynamicPath = $plugin->dynamicPath();
		      $href = explode("/", $currentPageURL);
		      $controller = @$href[3];
              $action = @$href[4];
		      $this->layout()->setVariables(array('controller'=>$controller,'action'=>$action, "dynamicPath"=>$dynamicPath));
            return new ViewModel(array('dynamicPath' => $dynamicPath));
    }
    

   

}

?>