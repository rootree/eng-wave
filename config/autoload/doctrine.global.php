<?php

return array(
    'doctrine' => array(
        'driver' => array(
            'annotation_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'metadata_cache' => 'filesystem',
                'query_cache'    => 'filesystem',
                'result_cache'   => 'filesystem'
            )
        ),
        'connection' => array(
            'orm_default' => array(
                'driverClass' => 'Doctrine\DBAL\Driver\PDOMySql\Driver',
                'params' => array(
                    'host'     => 'localhost',
                    'port'     => '3306',
                    'dbname'   => 'eng',
                    'charset'  => 'utf8',
                    'collate'  => 'utf8_general_ci',
                ),
            ),
        ),
         'authentication' => array(
            'orm_default' => array(
                'object_manager' => 'Doctrine\ORM\EntityManager',
                'identity_class' => 'Application\Model\Entity\User',
                'identity_property' => 'email',
                'credential_property' => 'password',
                'credentialCallable' => function ($userObj, $password) {
                    /** @var \Application\Model\Entity\User $userObj */
                    return ($userObj->getPassword() === md5($password));
                }
            ),
        ),
    )
);