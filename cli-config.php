<?php

use App\Core;
use Doctrine\ORM\Tools\Console\ConsoleRunner;;

require_once 'bootstrap.php';

$entityManager = Core::instance()->getEntityManager();

return ConsoleRunner::createHelperSet($entityManager);