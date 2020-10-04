<?php
namespace Config;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Setup;

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
           $config->getDefaultRepositoryClassName();

// database configuration parameters
               $conn = array( 'driver' => 'pdo_mysql', 'host' => DATABASE_HOST, 'user' => DATABASE_USER, 'password' => DATABASE_PASSWORD, 'dbname' => DATABASE_NAME );

// obtaining the entity manager
             return  $em = EntityManager::create($conn,$config);
           }
   }
