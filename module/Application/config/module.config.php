<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => __NAMESPACE__ . '\Controller\Homepage',
                        'action'     => 'index',
                    ),
                ),
            ),
            'download' => [
                'type'          => 'Segment',
                'options'       => array(
                    'route'    => '/download/:id',
                    'defaults' => array(
                        'controller'    => __NAMESPACE__ . '\Controller\Download',
                        'action'        => 'index',
                        'id'         => '[0-9]*',
                    )
                ),
            ],
            'download-by-hash' => [
                'type'          => 'Segment',
                'options'       => array(
                    'route'    => '/download/hash/:hash',
                    'defaults' => array(
                        'controller'    => __NAMESPACE__ . '\Controller\Download',
                        'action'     => 'hash',
                        'hash'       => '[a-zA-Z0-9_-]*',
                    )
                ),
            ],
        ),
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory'
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'annotation_driver' => array(
                'paths' => array(
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Model/Entity',
                    __DIR__ . '/../src/' . __NAMESPACE__ . '/Entity',
                ),

               // 'enable_default_entities' => false,
               // 'auto_mapping' =>  true
            ),
            'orm_default' => array(
                'drivers' => array(
                    // our entities
                    __NAMESPACE__ . '\Model\Entity' => 'annotation_driver',
                    // auto-generated doctrine entities
                    __NAMESPACE__ . '\Entity' => 'annotation_driver',
                ),
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            __NAMESPACE__ . '\Controller\Homepage' => __NAMESPACE__ . '\Controller\HomepageController',
            __NAMESPACE__ . '\Controller\Cli' => __NAMESPACE__ . '\Controller\CliController',
            __NAMESPACE__ . '\Controller\Download' => __NAMESPACE__ . '\Controller\DownloadController',
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
                'generate-strategies' => array(
                    'options' => array(
                        'route'    => 'generate-strategies',
                        'defaults' => array(
                            'controller' => __NAMESPACE__ . '\Controller\Cli',
                            'action'     => 'generateStrategies',
                        ),
                    ),
                ),
                'words-speaker' => array(
                    'options' => array(
                        'route'    => 'words-speaker',
                        'defaults' => array(
                            'controller' => __NAMESPACE__ . '\Controller\Cli',
                            'action'     => 'wordsSpeaker',
                        ),
                    ),
                ),
                'reset-demo-user' => array(
                    'options' => array(
                        'route'    => 'reset-demo-user',
                        'defaults' => array(
                            'controller' => __NAMESPACE__ . '\Controller\Cli',
                            'action'     => 'resetDemoUser',
                        ),
                    ),
                ),
            ),
        ),
    ),
    'view_helpers' => array(
        'invokables' => array(
            // 'formCollection' => 'Application\View\Helper\HorizontalCollection',
            // 'formRow'        => 'Application\View\Helper\FormRow',
        )
    ),
);
