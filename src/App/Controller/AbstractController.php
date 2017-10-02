<?php

namespace App\Controller;

use App\Core;
use App\Config;

abstract class AbstractController
{
	/**
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $entityManager;

	/**
	 * @var Config
	 */
	protected $config;

	/**
	 * AbstractController constructor.
	 */
	public function __construct()
	{
		$this->entityManager = Core::instance()->getEntityManager();
		$this->config = Config::instance();
	}

	/**
	 * Get entity manager.
	 * @return \Doctrine\ORM\EntityManager
	 */
	public function getEntityManager()
	{
		return $this->entityManager;
	}

	/**
	 * Get config.
	 * @return Config
	 */
	public function getConfig()
	{
		return $this->config;
	}
}