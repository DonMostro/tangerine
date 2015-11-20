<?php

// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'testing'));

$_ENV['APPLICATION_CONFIG'] = array(
    'config' => array(
        APPLICATION_PATH . '/configs/application.ini',
    )
);

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

<<<<<<< HEAD
=======
//Autoloader de Composer
if (file_exists(APPLICATION_PATH . '/../vendor/autoload.php')) {
    require_once APPLICATION_PATH . '/../vendor/autoload.php';
}

>>>>>>> f306af8cbc860e73b2c8de2e6c526d3db946b5d4
require_once 'Zend/Loader/Autoloader.php';
Zend_Loader_Autoloader::getInstance();
