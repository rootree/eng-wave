<?php

return array(
    'doctrine' => array(
        'connection' => array(
            'orm_default' => array(
                'params' => array(
                    'host' => 'localhost',
                    'user' => 'root',
                    'password' => '111111',
                ),
            ),
        ),
        'configuration' => array(
            'orm_default' => array(
                'metadata_cache' => 'array',
                'query_cache'    => 'array',
                'result_cache'   => 'array'
            )
        ),
    )
);

