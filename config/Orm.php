<?php
use Doctrine\ORM\Tools\Setup;
require_once __DIR__."/../vendor/autoload.php";

   class Orm
   {
           public function entityManager()
       {
           $isDevMode = true;
           $proxyDir = null;
           $cache = null;
           $useSimpleAnnotationReader = false;

           $config = Setup::createAnnotationMetadataConfiguration(array(__DIR__."/../src/Entity"), $isDevMode, $proxyDir, $cache, $useSimpleAnnotationReader);
           $config->addEntityNamespace('','App\Entity');

// database configuration parameters
               $conn = array( 'driver' => 'pdo_mysql', 'host' => 'localhost', 'user' => 'root', 'password' => '', 'dbname' =>'blog', );

// obtaining the entity manager
             return  $em = Doctrine\ORM\EntityManager::create($conn, $config);
           }
   }
