<?php

declare(strict_types=1);

return [
    'paths' => [
        'migrations' => '%%PHINX_CONFIG_DIR%%/config/database/migrations',
        'seeds' => '%%PHINX_CONFIG_DIR%%/config/database/seeds',
    ],
    'environments' => [
        'default_migration_table' => 'migrations',
        'default_database' => 'production',
        'production' => [
            'adapter' => 'mysql',
            'host' => getenv('DB_HOST'),
            'name' => getenv('DB_DATABASE'),
            'user' => getenv('DB_USER'),
            'pass' => getenv('DB_PASSWORD'),
            'port' => '3306',
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_general_ci',
        ],
    ],
    'version_order' => 'creation',
];
