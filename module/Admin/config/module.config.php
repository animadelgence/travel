<?php

return array(
     'controllers' => array(
         'invokables' => array(
             'Admin\Controller\Adminlogin' => 'Admin\Controller\AdminloginController',
             'Admin\Controller\Userregistration' => 'Admin\Controller\UserregistrationController',
             'Admin\Controller\Template' => 'Admin\Controller\TemplateController',
             'Admin\Controller\Language' => 'Admin\Controller\LanguageController',
             'Admin\Controller\Tag' => 'Admin\Controller\TagController'
         ),
     ),
    // The following section is new and should be added to your file
     'router' => array(
         'routes' => array(

            // This is for adminlogin controller
             'adminlogin' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/adminlogin[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                         'id'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                     ),
                     'defaults' => array(
                         'controller' => 'Admin\Controller\Adminlogin',
                         'action'     => 'index',
                     ),
                 ),
             ),

            //This is for Userregistration controller
              'userregistration' => array(
                 'type'    => 'segment',
                 'options' => array(
                     'route'    => '/userregistration[/:action][/:id]',
                     'constraints' => array(
                         'action' => '[a-zA-Z][a-zA-Z0-9_-]*',

                     ),
                     'defaults' => array(
                         'controller' => 'Admin\Controller\Userregistration',
                         'action'     => 'index',
                     ),
                 ),
             ),

            //This is for Template controller
               'template' => array(
                  'type'    => 'segment',
                  'options' => array(
                      'route'    => '/template[/:action][/:id][/:pId]',
                      'constraints' => array(
                          'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                      ),
                      'defaults' => array(
                          'controller' => 'Admin\Controller\Template',
                          'action'     => 'index',
                      ),
                  ),
              ),

            //This is for Language controller
               'language' => array(
                    'type'    => 'segment',
                    'options' => array(
                        'route'    => '/language[/:action][/:id]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'     => '[0-9]+',
                        ),
                        'defaults' => array(
                            'controller' => 'Admin\Controller\Language',
                            'action'     => 'index',
                        ),
                    ),
                ),

             //This is for Tag controller
               'tag' => array(
                    'type'    => 'segment',
                    'options' => array(
                        'route'    => '/tag[/:action][/:id]',
                        'constraints' => array(
                            'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            'id'     => '[0-9]+',
                        ),
                        'defaults' => array(
                            'controller' => 'Admin\Controller\Tag',
                            'action'     => 'index',
                        ),
                    ),
                ),

         ),
     ),

	 'view_manager' => array(
         'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/adminlayout.phtml',

		),
		 'template_path_stack' => array(
            __DIR__ . '/../view',
        ),

    ),
    'controller_plugins' => array(
        'invokables' => array(
            'usertrackplugin' => 'Plugin\Controller\Plugin\usertrackplugin'
        )
    ),
 );
