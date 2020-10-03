<?php
// cli-config.php
$em = new \Config\Orm();
return \Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($em->entityManager());
