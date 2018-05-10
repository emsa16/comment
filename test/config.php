<?php

/**
 * Sample configuration file for test configuration.
 */


/**
 * Define essential Anax paths, end with /
 */
define("ANAX_INSTALL_PATH", realpath(__DIR__ . "/.."));
define("ANAX_APP_PATH", ANAX_INSTALL_PATH);



/**
 * Include autoloader.
 */
require ANAX_INSTALL_PATH . "/vendor/autoload.php";


/**
 * Include other files to test, for example mock files.
 */
require_once 'Form/ValidationTrait.php';
require_once 'Form/ValidationInterface.php';
require_once 'Form/BaseModel.php';
require_once 'Form/ModelForm.php';
require_once 'User/User.php';
require_once 'Request/Request.php';
require_once 'Session/Session.php';
require_once 'Database/Database.php';
require_once 'Repository/RepositoryManager.php';
require_once 'Repository/DbRepository.php';
require_once 'Repository/SoftManagedModelInterface.php';
require_once 'Repository/SoftManagedModelTrait.php';
