<?php

/**
 * My Application bootstrap file.
 */
use Nette\Diagnostics\Debugger,
	Nette\Application\Routers\Route;


// Load Nette Framework
require LIBS_DIR . '/Nette/loader.php';


// Enable Nette Debugger for error visualisation & logging
Debugger::$logDirectory = __DIR__ . '/../log';
Debugger::$strictMode = TRUE;
Debugger::enable();


// Configure application
$configurator = new Nette\Config\Configurator;
$configurator->setTempDirectory(__DIR__ . '/../temp');

// Enable RobotLoader - this will load all classes automatically
$configurator->createRobotLoader()
	->addDirectory(APP_DIR)
	->addDirectory(LIBS_DIR)
	->register();

// Create Dependency Injection container from config.neon file
$configurator->addConfig(__DIR__ . '/config/config.neon');
$container = $configurator->createContainer();

// Opens already started session
if ($container->session->exists()) {
	$container->session->start();
}

// Setup router
$router = $container->router;

if( MobileDetection::isMobile() ) {
	// mobile routing
	$router[] = new Route('index.php', 'Mobile:Homepage:default', Route::ONE_WAY);
	$router[] = new Route('<presenter>/<action>[/<id>]', 'Mobile:Homepage:default');
} else {
	// default routing
	$router[] = new Route('index.php', 'Classic:Homepage:default', Route::ONE_WAY);
	$router[] = new Route('<presenter>/<action>[/<id>]', 'Classic:Homepage:default');	
}

// Configure and run the application!
$application = $container->application;
//$application->catchExceptions = TRUE;
$application->errorPresenter = 'Error';
$application->run();
