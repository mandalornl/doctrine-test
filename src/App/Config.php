<?php

namespace App;

use App\Traits\SingletonTrait;
use Symfony\Component\Yaml\Yaml;

class Config
{
	use SingletonTrait;

	/**
	 * @var bool
	 */
	private $debug = true;

	/**
	 * @var string
	 */
	private $projectName;

	/**
	 * @var string
	 */
	private $projectDir;

	/**
	 * @var string
	 */
	private $cacheDir;

	/**
	 * @var array
	 */
	private $backend;

	/**
	 * @var array
	 */
	private $mail;

	/**
	 * Config constructor.
	 */
	private function __construct()
	{
		$filename = realpath(__DIR__ . '/../config/') . '/app.yml';
		if (!is_readable($filename))
		{
			throw new ConfigNotFoundException('No config file found at: ' . $filename);
		}

		$config = Yaml::parse(file_get_contents($filename));

		$this->debug = (bool)$config['debug'] ?: $this->debug;
		$this->projectName = $config['projectName'];
		$this->projectDir = realpath(__DIR__ . '/../../');
		$this->cacheDir = $this->projectDir . '/app/cache';
		$this->backend = (array)$config['backend'];
		$this->mail = (array)$config['mail'];
	}

	/**
	 * Set debug.
	 * @param bool $debug
	 * @return $this
	 */
	public function setDebug($debug)
	{
		$this->debug = $debug;

		return $this;
	}

	/**
	 * Get debug.
	 * @return bool
	 */
	public function getDebug()
	{
		return $this->debug;
	}

	/**
	 * Set project name.
	 * @param string $projectName
	 * @return $this
	 */
	public function setProjectName($projectName)
	{
		$this->projectName = $projectName;

		return $this;
	}

	/**
	 * Get project name.
	 * @return string
	 */
	public function getProjectName()
	{
		return $this->projectName;
	}

	/**
	 * Set project dir.
	 * @param string $projectDir
	 * @return $this
	 */
	public function setProjectDir($projectDir)
	{
		$this->projectDir = $projectDir;

		return $this;
	}

	/**
	 * Get project dir.
	 * @return string
	 */
	public function getProjectDir()
	{
		return $this->projectDir;
	}

	/**
	 * Set cache dir.
	 * @param string $cacheDir
	 * @return $this
	 */
	public function setCacheDir($cacheDir)
	{
		$this->cacheDir = $cacheDir;

		return $this;
	}

	/**
	 * Get cache dir.
	 * @return string
	 */
	public function getCacheDir()
	{
		return $this->cacheDir;
	}

	/**
	 * Set cms.
	 * @param array $backend
	 * @return $this
	 */
	public function setBackend(array $backend)
	{
		$this->backend = $backend;

		return $this;
	}

	/**
	 * Get cms.
	 * @return array
	 */
	public function getBackend()
	{
		return $this->backend;
	}

	/**
	 * Set mail.
	 * @param array $mail
	 * @return $this
	 */
	public function setMail(array $mail)
	{
		$this->mail = $mail;

		return $this;
	}

	/**
	 * Get mail.
	 * @return array
	 */
	public function getMail()
	{
		return $this->mail;
	}
}