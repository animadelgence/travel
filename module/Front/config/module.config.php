<?php

/*
* To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/

return array(
	'controllers' => array(
		'invokables' => array(
			'Front\Controller\Gallery' => 'Front\Controller\GalleryController'
		),
	),
	// The following section is new and should be added to your file
	'router' => array(
		'routes' => array(
			'home' => array(
				'type' => 'Literal',
				'options' => array(
					'route'    => '/',
					'defaults' => array(
						'__NAMESPACE__' => 'Front\Controller',
						'controller' => 'Front\Controller\Gallery',
						'action'     => 'galleryview',
					),
				),
			),
			'Gallery' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/Gallery[/:action][/:id][/:pId][/:devId]',
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
					'defaults' => array(
						'controller' => 'Front\Controller\Gallery',
						'action'     => 'galleryview',
					),
				),
			),
			
		),
	),

	'view_manager' => array(
		'template_map' => array(
			'layout/layout' => __DIR__ . '/../view/layout/gallerylayout.phtml',
		),
		'template_path_stack' => array(
			__DIR__ . '/../view',
		),
	),
);
