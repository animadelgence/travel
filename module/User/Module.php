<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace User;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use User\Model\publisher;
use User\Model\publisherTable;
use User\Model\publisherbackup;
use User\Model\publisherbackupTable;
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
            //if not this module
            return;
        }
        $action = $e->getRouteMatch()->getParam('action');
        if ($action == 'not-found') {
            //if not this module
            include_once(dirname(dirname(dirname(__FILE__))).'/module/Authorization/view/authorization/error/error.php');
        }
        //if this module
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
             /*'Zend\Loader\ClassMapAutoloader' => array(
                 __DIR__ . '/autoload_classmap.php',
             ),*/
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
     { //added by Poulami
        return array(
            'factories' => array(
                'User\Model\publisherTable' => function($sm) {
                    $tableGateway = $sm->get('publisherTableGateway');
                    $table = new publisherTable($tableGateway);
                    return $table;
                },
                'publisherTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new publisher());
                    return new TableGateway('publisher', $dbAdapter, null, $resultSetPrototype);
                },
                'User\Model\publisherbackupTable' => function($sm) {
                    $tableGateway = $sm->get('publisherbackupTableGateway');
                    $table = new publisherbackupTable($tableGateway);
                    return $table;
                },
                'publisherbackupTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new publisherbackup());
                    return new TableGateway('publisherbackup', $dbAdapter, null, $resultSetPrototype);
                },


            ),
        );
    }
}
?>
