<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\DebugFormatterHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\ProcessHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ServerCommand extends Command
{
	/**
	 * {@inheritdoc}
	 */
	protected function configure()
	{
		$this
			->setName('server:run')
			->setDescription('Run building-in server.')
			->addOption('host', null, InputOption::VALUE_OPTIONAL, 'Set host: [localhost]')
			->addOption('port', null, InputOption::VALUE_OPTIONAL, 'Set port: [8000]')
			->addOption('docroot', null, InputOption::VALUE_OPTIONAL, 'Set docroot: [public/]')
			->setHelp(
				<<<EOT
'Run building-in php server. Will default to 'http://localhost:8000', using 'public/' as docroot.'
EOT
			)
		;
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$host = $input->getOption('host') ?: 'localhost';
		$port = (int)$input->getOption('port') ?: 8000;

		$fp = @fsockopen($host, $port, $errno, $errstr, 5);
		if ($fp !== false)
		{
			fclose($fp);

			$output->writeln(sprintf('Server already running on %s:%d', $host, $port));
			return;
		}

		$binary = '/usr/local/bin/php';

		$helper = new ProcessHelper();
		$helper->setHelperSet(new HelperSet([new DebugFormatterHelper()]));
		$helper->run($output, [
			$binary,
			'-S',
			"{$host}:{$port}",
			'-t',
			$input->getOption('docroot') ?: 'public/'
		]);
	}
}