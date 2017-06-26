<?php

namespace Authorization\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Http\PhpEnvironment\Request;
use Zend\Session\Container;
class ErrorController extends AbstractActionController {
	public function unpublishAction()
	{
		$plugin = $this->routeplugin();
		$dynamicPath = $plugin->dynamicPath();
		$viewModel = new ViewModel(array('dynamicPath'=>$dynamicPath));
		$viewModel->setTerminal(true);
		return $viewModel;
	}
}
?>
