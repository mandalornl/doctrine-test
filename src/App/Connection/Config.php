<?php

namespace App\Connection;

use App\Traits\SingletonTrait;
use Symfony\Component\Yaml\Yaml;

class Config
{
	use SingletonTrait;

	/**
	 * @var string
	 */
	private $driver = 'pdo_mysql';

	/**
	 * @var string
	 */
	private $hostname = 'localhost';

	/**
	 * @var string
	 */
	private $database;

	/**
	 * @var string
	 */
	private $user;

	/**
	 * @var string
	 */
	private $password = '';

	/**
	 * @var string
	 */
	private $charset = 'UTF8';

	/**
	 * Config constructor.
	 */
	private function __construct()
	{
		$filename = realpath(__DIR__ . '/../../config/') . '/connection.yml';
		if (!is_readable($filename))
		{
			throw new ConfigNotFoundException('No config file found at: %s', $filename);
		}

		$config = Yaml::parse(file_get_contents($filename));

		$this->driver = $config['driver'] ?: $this->driver;
		$this->hostname = $config['hostname'] ?: $this->hostname;
		$this->database = $config['database'];
		$this->user = $config['user'];
		$this->password = $config['password'];
		$this->charset = $config['charset'] ?: $this->charset;
	}

	/**
	 * Set driver.
	 * @param string $driver
	 * @return $this
	 */
	public function setDriver($driver)
	{
		$this->driver = $driver;

		return $this;
	}

	/**
	 * Get driver.
	 * @return string
	 */
	public function getDriver()
	{
		return $this->driver;
	}

	/**
	 * Set hostname.
	 * @param string $hostname
	 * @return $this
	 */
	public function setHostname($hostname)
	{
		$this->hostname = $hostname;

		return $this;
	}

	/**
	 * Get hostname.
	 * @return string
	 */
	public function getHostname()
	{
		return $this->hostname;
	}

	/**
	 * Set database.
	 * @param string $database
	 * @return $this
	 */
	public function setDatabase($database)
	{
		$this->database = $database;

		return $this;
	}

	/**
	 * Get database.
	 * @return string
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * Set user.
	 * @param string $user
	 * @return $this
	 */
	public function setUser($user)
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * Get user.
	 * @return string
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set password.
	 * @param string $password
	 * @return $this
	 */
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * Get password.
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * Set charset.
	 * @param string $charset
	 * @return $this
	 */
	public function setCharset($charset)
	{
		$this->charset = $charset;

		return $this;
	}

	/**
	 * Get charset.
	 * @return string
	 */
	public function getCharset()
	{
		return $this->charset;
	}
}