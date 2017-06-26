<?php

namespace Admin;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Admin\Model\admin;
use Admin\Model\adminTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach('dispatch', array($this, 'loadConfiguration'), MvcEvent::EVENT_DISPATCH_ERROR, function($e) {
            $result = $e->getResult();
            $result->setTerminal(TRUE);
        }, 100);
        $eventManager->attach('dispatch.error',array($this,'handleError'), 100);
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }
    public function loadConfiguration(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $controller = $e->getRouteMatch()->getParam('controller');
        if (0 !== strpos($controller, __NAMESPACE__, 0)) {
            return;
        }
        $action = $e->getRouteMatch()->getParam('action');
        if ($action == 'not-found') {
            //if not this module
           include_once(dirname(dirname(dirname(__FILE__))).'/module/Authorization/view/authorization/error/error.php');
        }
        $exceptionstrategy = $sm->get('ViewManager')->getExceptionStrategy();
        $exceptionstrategy->setExceptionTemplate('error/index');
    }
    public function handleError(MvcEvent $e)
	{
        $error  = $e->getError();
        include_once(dirname(dirname(dirname(__FILE__))).'/module/Authorization/view/authorization/error/error.php');
		//...handle the exception...     maybe log it and redirect to another page,
		//or send an email that an exception occurred...
	}
     public function getAutoloaderConfig()
     {
         return array(
             'Zend\Loader\StandardAutoloader' => array(
                 'namespaces' => array(
                     __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                 ),
             ),
         );
     }

     public function getConfig()
     {
         return include __DIR__ . '/config/module.config.php';
     }

     public function getServiceConfig()
     {
        return array(
            'factories' => array('Admin\Model\adminTable' => function($sm) {
                    $tableGateway = $sm->get('adminTableGateway');
                    $table = new adminTable($tableGateway);
                    return $table;
                },
                'adminTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new admin());
                    return new TableGateway('admin', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
?>
