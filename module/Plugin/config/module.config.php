<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

return array(
    'controllers' => array(
        'invokables' => array(
            'Plugin\Controller\Plugintest' => 'Plugin\Controller\PlugintestController'
        ),
    ),
    // The following section is new and should be added to your file
    'router' => array(
        'routes' => array(
            // this is for controller
            'Plugintest' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/Plugintest[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Plugin\Controller\Plugintest',
                        'action' => 'index',
                    ),
                ),
            ),
        // this is for test controller
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'Login' => __DIR__ . '/../view',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'modelplugin' => 'Plugin\Controller\Plugin\modelplugin',
            'routeplugin' => 'Plugin\Controller\Plugin\routeplugin',
            'imageupload' => 'Plugin\Controller\Plugin\imageupload',
            'mailplugin' => 'Plugin\Controller\Plugin\mailplugin',
            'templateCopyplugin' => 'Plugin\Controller\Plugin\templateCopyplugin',
            'webpublish' => 'Plugin\Controller\Plugin\webpublish',
            'facebookpublish' => 'Plugin\Controller\Plugin\facebookpublish',
            'usertrackplugin' => 'Plugin\Controller\Plugin\usertrackplugin',
            'attachmentplugin' => 'Plugin\Controller\Plugin\attachmentplugin',
            'mailcampaignplugin' => 'Plugin\Controller\Plugin\mailcampaignplugin',
            'phpinjectionpreventplugin' => 'Plugin\Controller\Plugin\phpinjectionpreventplugin'
        )
    ),
);
