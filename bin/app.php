<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$helperSet = require __DIR__ . '/../cli-config.php';

ConsoleRunner::run($helperSet, [
	new App\Command\ServerCommand()
]);