<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
     'controllers' => array(
         'invokables' => array(
             'Authorization\Controller\Authorizationlogin' => 'Authorization\Controller\AuthorizationloginController',
             'Authorization\Controller\Authorizationsignup' => 'Authorization\Controller\AuthorizationsignupController',
             'Authorization\Controller\Authorizationlogout' => 'Authorization\Controller\AuthorizationlogoutController',
			 'Authorization\Controller\Error' => 'Authorization\Controller\ErrorController',
             'Authorization\Controller\Oldregistration' => 'Authorization\Controller\OldregistrationController'
            
         ),
     ),
    // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             // this is for loginpurpose
             'authorizationlogin' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/al[/:action][/:id][/:pId][/:devId]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'Authorization\Controller\Authorizationlogin',
                         'action'     => 'login',
                     ),
                 ),
             ),
             // this is for signin purpose
             'authorizationsignup' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/as[/:action][/:id][/:pId][/:devId]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'Authorization\Controller\Authorizationsignup',
                         'action'     => 'emailsignup',
                     ),
                 ),
             ),
             // this is for logout purpose
             'authorizationlogout' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/ao[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'Authorization\Controller\Authorizationlogout',
                         'action'     => 'logoutuser',
                     ),
                 ),
             ),
			  'Error' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/Error[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'Authorization\Controller\Error',
                         'action'     => '404',
                     ),
                 ),
             ),
            'Oldregistration' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/Oldregistration[/:action][/:id][/:pId][/:devId]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'Authorization\Controller\Oldregistration',
                         'action'     => '404',
                     ),
                 ),
             ),
             
         ),
     ),
    
    

'view_manager' => array(
         'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/galleryautholayout.phtml',

		),
		 'template_path_stack' => array(
            __DIR__ . '/../view',
        ),

    ),
 );
