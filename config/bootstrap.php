<?php
// bootstrap.php
use Doctrine\ORM\Tools\Setup;


require_once __DIR__."/../vendor/autoload.php";
require_once __DIR__."/../src/Entity/Post.php";

// Create a simple "default" Doctrine ORM configuration for Annotations
$isDevMode = true;
$proxyDir = null;
$cache = null;
$useSimpleAnnotationReader = false;

$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../src/Entity"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
$config->addEntityNamespace('','App\Entity');

// database configuration parameters
$conn = array( 'driver' => 'pdo_mysql', 'host' => 'localhost', 'user' => 'root', 'password' => '', 'dbname' =>'blog', );

// obtaining the entity manager
   $em = Doctrine\ORM\EntityManager::create($conn, $config);
