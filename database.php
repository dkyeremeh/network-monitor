<?php

use Illuminate\Support\Str;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish
    | to use as your default connection for all database work. Of course
    | you may use many connections at once using the Database library.
    |
    */

    'default' => denv('DB_CONNECTION', "sqlite"),

    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by Laravel is shown below to make development simple.
    |
    |
    | All database work in Laravel is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [

        'sqlite' => [
            'driver' => 'sqlite',
            'url' => denv('DATABASE_URL'),
            'database' => $ROOT . "/" . denv('DB_DATABASE'),
            'prefix' => '',
            'foreign_key_constraints' => denv('DB_FOREIGN_KEYS', true),
        ],

        'mysql' => [
            'driver' => 'mysql',
            'url' => denv('DATABASE_URL'),
            'host' => denv('DB_HOST', '127.0.0.1'),
            'port' => denv('DB_PORT', '3306'),
            'database' => denv('DB_DATABASE', 'forge'),
            'username' => denv('DB_USERNAME', 'forge'),
            'password' => denv('DB_PASSWORD', ''),
            'unix_socket' => denv('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null,
            'options' => extension_loaded('pdo_mysql') ? array_filter([
                PDO::MYSQL_ATTR_SSL_CA => denv('MYSQL_ATTR_SSL_CA'),
            ]) : [],
        ],

        'pgsql' => [
            'driver' => 'pgsql',
            'url' => denv('DATABASE_URL'),
            'host' => denv('DB_HOST', '127.0.0.1'),
            'port' => denv('DB_PORT', '5432'),
            'database' => denv('DB_DATABASE', 'forge'),
            'username' => denv('DB_USERNAME', 'forge'),
            'password' => denv('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
            'schema' => 'public',
            'sslmode' => 'prefer',
        ],

        'sqlsrv' => [
            'driver' => 'sqlsrv',
            'url' => denv('DATABASE_URL'),
            'host' => denv('DB_HOST', 'localhost'),
            'port' => denv('DB_PORT', '1433'),
            'database' => denv('DB_DATABASE', 'forge'),
            'username' => denv('DB_USERNAME', 'forge'),
            'password' => denv('DB_PASSWORD', ''),
            'charset' => 'utf8',
            'prefix' => '',
            'prefix_indexes' => true,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Migration Repository Table
    |--------------------------------------------------------------------------
    |
    | This table keeps track of all the migrations that have already run for
    | your application. Using this information, we can determine which of
    | the migrations on disk haven't actually been run in the database.
    |
    */

    'migrations' => 'migrations',

    /*
    |--------------------------------------------------------------------------
    | Redis Databases
    |--------------------------------------------------------------------------
    |
    | Redis is an open source, fast, and advanced key-value store that also
    | provides a richer body of commands than a typical key-value system
    | such as APC or Memcached. Laravel makes it easy to dig right in.
    |
    */

    'redis' => [

        'client' => denv('REDIS_CLIENT', 'phpredis'),

        'options' => [
            'cluster' => denv('REDIS_CLUSTER', 'redis'),
            'prefix' => denv('REDIS_PREFIX', Str::slug(denv('APP_NAME', 'laravel'), '_').'_database_'),
        ],

        'default' => [
            'url' => denv('REDIS_URL'),
            'host' => denv('REDIS_HOST', '127.0.0.1'),
            'password' => denv('REDIS_PASSWORD', null),
            'port' => denv('REDIS_PORT', '6379'),
            'database' => denv('REDIS_DB', '0'),
        ],

        'cache' => [
            'url' => denv('REDIS_URL'),
            'host' => denv('REDIS_HOST', '127.0.0.1'),
            'password' => denv('REDIS_PASSWORD', null),
            'port' => denv('REDIS_PORT', '6379'),
            'database' => denv('REDIS_CACHE_DB', '1'),
        ],

    ],

];

