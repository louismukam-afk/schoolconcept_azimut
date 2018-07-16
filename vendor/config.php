<?php return array (
  'app' => 
  array (
    'debug' => true,
    'url' => 'http://localhost',
    'timezone' => 'Africa/Brazzaville',
    'locale' => 'fr',
    'fallback_locale' => 'fr',
    'key' => 'mgzBsVBARBwT6taHJx8vjcjmY17GXUbY',
    'cipher' => 'rijndael-128',
    'log' => 'daily',
    'providers' => 
    array (
      0 => 'Illuminate\\Foundation\\Providers\\ArtisanServiceProvider',
      1 => 'Illuminate\\Auth\\AuthServiceProvider',
      2 => 'Illuminate\\Bus\\BusServiceProvider',
      3 => 'Illuminate\\Cache\\CacheServiceProvider',
      4 => 'Illuminate\\Foundation\\Providers\\ConsoleSupportServiceProvider',
      5 => 'Illuminate\\Routing\\ControllerServiceProvider',
      6 => 'Illuminate\\Cookie\\CookieServiceProvider',
      7 => 'Illuminate\\Database\\DatabaseServiceProvider',
      8 => 'Illuminate\\Encryption\\EncryptionServiceProvider',
      9 => 'Illuminate\\Filesystem\\FilesystemServiceProvider',
      10 => 'Illuminate\\Foundation\\Providers\\FoundationServiceProvider',
      11 => 'Barryvdh\\LaravelIdeHelper\\IdeHelperServiceProvider',
      12 => 'Illuminate\\Hashing\\HashServiceProvider',
      13 => 'Illuminate\\Mail\\MailServiceProvider',
      14 => 'Illuminate\\Pagination\\PaginationServiceProvider',
      15 => 'Illuminate\\Pipeline\\PipelineServiceProvider',
      16 => 'Illuminate\\Queue\\QueueServiceProvider',
      17 => 'Illuminate\\Redis\\RedisServiceProvider',
      18 => 'Illuminate\\Auth\\Passwords\\PasswordResetServiceProvider',
      19 => 'Illuminate\\Session\\SessionServiceProvider',
      20 => 'Illuminate\\Translation\\TranslationServiceProvider',
      21 => 'Illuminate\\Validation\\ValidationServiceProvider',
      22 => 'Illuminate\\View\\ViewServiceProvider',
      23 => 'Intervention\\Image\\ImageServiceProvider',
      24 => 'gesecole\\Providers\\AppServiceProvider',
      25 => 'gesecole\\Providers\\BusServiceProvider',
      26 => 'gesecole\\Providers\\ConfigServiceProvider',
      27 => 'gesecole\\Providers\\EventServiceProvider',
      28 => 'gesecole\\Providers\\RouteServiceProvider',
    ),
    'aliases' => 
    array (
      'App' => 'Illuminate\\Support\\Facades\\App',
      'Artisan' => 'Illuminate\\Support\\Facades\\Artisan',
      'Auth' => 'Illuminate\\Support\\Facades\\Auth',
      'Blade' => 'Illuminate\\Support\\Facades\\Blade',
      'Bus' => 'Illuminate\\Support\\Facades\\Bus',
      'Cache' => 'Illuminate\\Support\\Facades\\Cache',
      'Config' => 'Illuminate\\Support\\Facades\\Config',
      'Cookie' => 'Illuminate\\Support\\Facades\\Cookie',
      'Crypt' => 'Illuminate\\Support\\Facades\\Crypt',
      'DB' => 'Illuminate\\Support\\Facades\\DB',
      'Eloquent' => 'Illuminate\\Database\\Eloquent\\Model',
      'Event' => 'Illuminate\\Support\\Facades\\Event',
      'File' => 'Illuminate\\Support\\Facades\\File',
      'Hash' => 'Illuminate\\Support\\Facades\\Hash',
      'Image' => 'Intervention\\Image\\Facades\\Image',
      'Input' => 'Illuminate\\Support\\Facades\\Input',
      'Inspiring' => 'Illuminate\\Foundation\\Inspiring',
      'Lang' => 'Illuminate\\Support\\Facades\\Lang',
      'Log' => 'Illuminate\\Support\\Facades\\Log',
      'Mail' => 'Illuminate\\Support\\Facades\\Mail',
      'Password' => 'Illuminate\\Support\\Facades\\Password',
      'Queue' => 'Illuminate\\Support\\Facades\\Queue',
      'Redirect' => 'Illuminate\\Support\\Facades\\Redirect',
      'Redis' => 'Illuminate\\Support\\Facades\\Redis',
      'Request' => 'Illuminate\\Support\\Facades\\Request',
      'Response' => 'Illuminate\\Support\\Facades\\Response',
      'Route' => 'Illuminate\\Support\\Facades\\Route',
      'Schema' => 'Illuminate\\Support\\Facades\\Schema',
      'Session' => 'Illuminate\\Support\\Facades\\Session',
      'Storage' => 'Illuminate\\Support\\Facades\\Storage',
      'URL' => 'Illuminate\\Support\\Facades\\URL',
      'Validator' => 'Illuminate\\Support\\Facades\\Validator',
      'View' => 'Illuminate\\Support\\Facades\\View',
    ),
  ),
  'auth' => 
  array (
    'driver' => 'eloquent',
    'model' => 'gesecole\\User',
    'table' => 'users',
    'password' => 
    array (
      'email' => 'emails.password',
      'table' => 'password_resets',
      'expire' => 60,
    ),
  ),
  'cache' => 
  array (
    'default' => 'file',
    'stores' => 
    array (
      'apc' => 
      array (
        'driver' => 'apc',
      ),
      'array' => 
      array (
        'driver' => 'array',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'cache',
        'connection' => NULL,
      ),
      'file' => 
      array (
        'driver' => 'file',
        'path' => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\storage/framework/cache',
      ),
      'memcached' => 
      array (
        'driver' => 'memcached',
        'servers' => 
        array (
          0 => 
          array (
            'host' => '127.0.0.1',
            'port' => 11211,
            'weight' => 100,
          ),
        ),
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'connection' => 'default',
      ),
    ),
    'prefix' => 'laravel',
  ),
  'compile' => 
  array (
    'files' => 
    array (
      0 => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\app\\Providers\\AppServiceProvider.php',
      1 => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\app\\Providers\\BusServiceProvider.php',
      2 => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\app\\Providers\\ConfigServiceProvider.php',
      3 => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\app\\Providers\\EventServiceProvider.php',
      4 => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\app\\Providers\\RouteServiceProvider.php',
    ),
    'providers' => 
    array (
    ),
  ),
  'database' => 
  array (
    'fetch' => 8,
    'default' => 'mysql',
    'connections' => 
    array (
      'sqlite' => 
      array (
        'driver' => 'sqlite',
        'database' => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\storage/database.sqlite',
        'prefix' => '',
      ),
      'mysql' => 
      array (
        'driver' => 'mysql',
        'host' => 'localhost',
        'database' => 'schoolconcept',
        'username' => 'schoolconcept',
        'password' => 'school-concept',
        'charset' => 'utf8',
        'collation' => 'utf8_unicode_ci',
        'prefix' => '',
        'strict' => false,
      ),
      'pgsql' => 
      array (
        'driver' => 'pgsql',
        'host' => 'localhost',
        'database' => 'schoolconcept',
        'username' => 'schoolconcept',
        'password' => 'school-concept',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
      ),
      'sqlsrv' => 
      array (
        'driver' => 'sqlsrv',
        'host' => 'localhost',
        'database' => 'eyJpdiI6InlSNHlkUHBTRDRScm5GaUwwbllXOXc9PSIsInZhbHVlIjoiczJkRXVyQ2F4Q1llSkhCbVp1S1wvS1ZUcUpHRStvNWw4UGJDTFYzc205Yzg9IiwibWFjIjoiNmY5NDY3MTc1NGE5OGQxNzQzM2I5MjJhOWE5ODMzMGU3MDE5ZjlhNmUyNDE1ZGJlNGY5ZGY5ZmJhOGMxZTAxOSJ9',
        'username' => 'eyJpdiI6InlSNHlkUHBTRDRScm5GaUwwbllXOXc9PSIsInZhbHVlIjoiczJkRXVyQ2F4Q1llSkhCbVp1S1wvS1ZUcUpHRStvNWw4UGJDTFYzc205Yzg9IiwibWFjIjoiNmY5NDY3MTc1NGE5OGQxNzQzM2I5MjJhOWE5ODMzMGU3MDE5ZjlhNmUyNDE1ZGJlNGY5ZGY5ZmJhOGMxZTAxOSJ9',
        'password' => 'eyJpdiI6ImhMcHdQRU9UVDVySUhwbk90VWhHbnc9PSIsInZhbHVlIjoiT2xrelUyNTh6MytPaFBFN25TaVY4WDJOczVjbnFrREJhMElUb3l0eEFkND0iLCJtYWMiOiI5ZDAxNGNjMDRiMTNhYzZhNzllYzg2MjIwOWY0ZGU4MjllNjUxMDhjOTk0ZjRiNGM1MzhmZGNiZDZkNDE3OGM0In0=',
        'prefix' => '',
      ),
    ),
    'migrations' => 'migrations',
    'redis' => 
    array (
      'cluster' => false,
      'default' => 
      array (
        'host' => '127.0.0.1',
        'port' => 6379,
        'database' => 0,
      ),
    ),
  ),
  'env' => 
  array (
    'key' => 'yxrpi7PCloUxCVxw',
  ),
  'filesystems' => 
  array (
    'default' => 'local',
    'cloud' => 's3',
    'disks' => 
    array (
      'local' => 
      array (
        'driver' => 'local',
        'root' => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\storage/app',
      ),
      's3' => 
      array (
        'driver' => 's3',
        'key' => 'your-key',
        'secret' => 'your-secret',
        'region' => 'your-region',
        'bucket' => 'your-bucket',
      ),
      'rackspace' => 
      array (
        'driver' => 'rackspace',
        'username' => 'your-username',
        'key' => 'your-key',
        'container' => 'your-container',
        'endpoint' => 'https://identity.api.rackspacecloud.com/v2.0/',
        'region' => 'IAD',
        'url_type' => 'publicURL',
      ),
    ),
  ),
  'mail' => 
  array (
    'driver' => 'smtp',
    'host' => 'mailtrap.io',
    'port' => '2525',
    'from' => 
    array (
      'address' => NULL,
      'name' => NULL,
    ),
    'encryption' => 'tls',
    'username' => NULL,
    'password' => NULL,
    'sendmail' => '/usr/sbin/sendmail -bs',
    'pretend' => false,
  ),
  'queue' => 
  array (
    'default' => 'sync',
    'connections' => 
    array (
      'sync' => 
      array (
        'driver' => 'sync',
      ),
      'database' => 
      array (
        'driver' => 'database',
        'table' => 'jobs',
        'queue' => 'default',
        'expire' => 60,
      ),
      'beanstalkd' => 
      array (
        'driver' => 'beanstalkd',
        'host' => 'localhost',
        'queue' => 'default',
        'ttr' => 60,
      ),
      'sqs' => 
      array (
        'driver' => 'sqs',
        'key' => 'your-public-key',
        'secret' => 'your-secret-key',
        'queue' => 'your-queue-url',
        'region' => 'us-east-1',
      ),
      'iron' => 
      array (
        'driver' => 'iron',
        'host' => 'mq-aws-us-east-1.iron.io',
        'token' => 'your-token',
        'project' => 'your-project-id',
        'queue' => 'your-queue-name',
        'encrypt' => true,
      ),
      'redis' => 
      array (
        'driver' => 'redis',
        'queue' => 'default',
        'expire' => 60,
      ),
    ),
    'failed' => 
    array (
      'database' => 'mysql',
      'table' => 'failed_jobs',
    ),
  ),
  'services' => 
  array (
    'mailgun' => 
    array (
      'domain' => '',
      'secret' => '',
    ),
    'mandrill' => 
    array (
      'secret' => '',
    ),
    'ses' => 
    array (
      'key' => '',
      'secret' => '',
      'region' => 'us-east-1',
    ),
    'stripe' => 
    array (
      'model' => 'gesecole\\User',
      'secret' => '',
    ),
  ),
  'session' => 
  array (
    'driver' => 'file',
    'lifetime' => 120,
    'expire_on_close' => false,
    'encrypt' => false,
    'files' => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\storage/framework/sessions',
    'connection' => NULL,
    'table' => 'sessions',
    'lottery' => 
    array (
      0 => 2,
      1 => 100,
    ),
    'cookie' => 'laravel_session',
    'path' => '/',
    'domain' => NULL,
    'secure' => false,
  ),
  'view' => 
  array (
    'paths' => 
    array (
      0 => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\resources\\views',
    ),
    'compiled' => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept\\storage\\framework\\views',
  ),
  'image' => 
  array (
    'driver' => 'gd',
  ),
  'ide-helper' => 
  array (
    'filename' => '_ide_helper',
    'format' => 'php',
    'meta_filename' => '.phpstorm.meta.php',
    'include_fluent' => false,
    'write_model_magic_where' => true,
    'include_helpers' => false,
    'helper_files' => 
    array (
      0 => 'C:\\Users\\Joel\\Desktop\\projects\\SchoolConcept/vendor/laravel/framework/src/Illuminate/Support/helpers.php',
    ),
    'model_locations' => 
    array (
      0 => 'app',
    ),
    'extra' => 
    array (
      'Eloquent' => 
      array (
        0 => 'Illuminate\\Database\\Eloquent\\Builder',
        1 => 'Illuminate\\Database\\Query\\Builder',
      ),
      'Session' => 
      array (
        0 => 'Illuminate\\Session\\Store',
      ),
    ),
    'magic' => 
    array (
      'Log' => 
      array (
        'debug' => 'Monolog\\Logger::addDebug',
        'info' => 'Monolog\\Logger::addInfo',
        'notice' => 'Monolog\\Logger::addNotice',
        'warning' => 'Monolog\\Logger::addWarning',
        'error' => 'Monolog\\Logger::addError',
        'critical' => 'Monolog\\Logger::addCritical',
        'alert' => 'Monolog\\Logger::addAlert',
        'emergency' => 'Monolog\\Logger::addEmergency',
      ),
    ),
    'interfaces' => 
    array (
    ),
    'custom_db_types' => 
    array (
    ),
    'model_camel_case_properties' => false,
    'type_overrides' => 
    array (
      'integer' => 'int',
      'boolean' => 'bool',
    ),
  ),
);
