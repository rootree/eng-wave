<?php
return array(
    'router'          => array(
        'routes' => array(
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'auth' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/api/auth/:action',
                    'defaults' => [
                        'controller' => 'Api\Controller\Auth',
                    ]
                ]
            ],
            'user' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/api/user/:action',
                    'defaults' => [
                        'controller' => 'Api\Controller\User',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ]
                ]
            ],
            'words' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/api/words[/:action][/:id]',
                    'defaults' => [
                        'controller' => 'Api\Controller\Words',
                        'action'     => 'index',
                    ]
                ],
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',

                        'controller' => 'Api\Controller\Words',
                        'options' => array(
                            'route'       => '/api/words/:action[/:id]',
                            'constraints' => array(
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id'         => '[0-9]*',
                            )
                        )
                    )
                )
            ],
            'groups' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/api/groups[/:action][/:id]',
                    'defaults' => [
                        'controller' => 'Api\Controller\Groups',
                        //'action'     => 'index',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]*',
                    ]
                ],
            ],
            'packages' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/api/packages/:action[/:id]',
                    'defaults' => [
                        'controller' => 'Api\Controller\Packages',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]*',
                    ]
                ],
            ],
            'strategies' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/api/strategies[/:action][/:id]',
                    'defaults' => [
                        'controller' => 'Api\Controller\Strategies',
                        //'action'     => 'index',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]*',
                    ]
                ],
            ],
            'downloads' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/api/downloads[/:action][/:id]',
                    'defaults' => [
                        'controller' => 'Api\Controller\Downloads',
                        'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'         => '[0-9]*',
                    ]
                ],
            ],
            'feedback' => [
                'type' => 'Segment',
                'options' => [
                    'route'    => '/api/feedback/add',
                    'defaults' => [
                        'controller' => 'Api\Controller\Feedback',
                        'action'     => 'add',
                    ]
                ],
            ],
        ),
    ),
    'service_manager' => array(),
    'controllers'     => array(
        'invokables' => array(
            'Api\Controller\Auth'       => 'Api\Controller\AuthController',
            'Api\Controller\Words'      => 'Api\Controller\WordsController',
            'Api\Controller\Groups'     => 'Api\Controller\GroupsController',
            'Api\Controller\Strategies' => 'Api\Controller\StrategiesController',
            'Api\Controller\Downloads'  => 'Api\Controller\DownloadsController',
            'Api\Controller\Feedback'   => 'Api\Controller\FeedbackController',
            'Api\Controller\User'       => 'Api\Controller\UserController',
            'Api\Controller\Packages'       => 'Api\Controller\PackagesController',
        ),
    ),
    'view_manager'    => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
        'strategies' => [
            'ViewJsonStrategy'
        ]
    ),
);