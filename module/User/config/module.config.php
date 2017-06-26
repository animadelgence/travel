<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
     'controllers' => array(
         'invokables' => array(
             'User\Controller\Usertest' => 'User\Controller\UsertestController',
             'User\Controller\Profile' => 'User\Controller\ProfileController'
         ),
     ),
    // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(
             // this is for controller
             'usertest' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/usertest[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'User\Controller\Usertest',
                         'action'     => 'index',
                     ),
                 ),
             ),
             // this is for test controller
             'Profile' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/p[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'User\Controller\Profile',
                         'action'     => 'useraccountdetails',
                     ),
                 ),
             ),
         ),
     ),
    

     'view_manager' => array(
		'template_map' => array(
			'layout/layout' => __DIR__ . '/../view/layout/userlayout.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
 );
