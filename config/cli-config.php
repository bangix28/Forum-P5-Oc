<?php
// cli-config.php
require_once 'Orm.php';
$em = new \Orm();
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em->entityManager());
