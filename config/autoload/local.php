<?php
/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */

return [

    'store' => [
        'path' => '/data/eng/static/',
        'url' => 'http://eng.local/',
        'silence-file' => '/data/eng/data/MP3/1secV2.mp3',
    ],

    'audit' => [
        'enabled' => true,
    ],

    'caches' => [
        'cache.filesystem' => [
            'adapter' => 'filesystem',
            'options' => [
                'cache_dir' => 'data/cache/',
                'key_pattern' => '/^[a-z0-9_\+\-\[\]\$\\\\]*$/Di'
            ],
            'plugins' => [
                'exception_handler' => [
                    'throw_exceptions' => false,
                ],
                'serializer'
            ],
        ],
    ],

    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'strategies' => array(
            'ViewJsonStrategy', // register JSON renderer strategy
            //'ViewFeedStrategy', // register Feed renderer strategy
        ),
    ],

];
