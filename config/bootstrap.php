<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

require_once "vendor/autoload.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../src/Entity"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);

// database configuration parameters
$conn = array( 'driver' => 'pdo_mysql', 'host' => 'localhost', 'user' => 'root', 'password' => '', 'dbname' =>'blog', );

// obtaining the entity manager
$entityManager = EntityManager::create($conn, $config);
