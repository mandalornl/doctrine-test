<?php

namespace App;

use App\Connection\Config as ConnectionConfig;
use App\Listener\Behavior\BlamableSubscriber;
use App\Listener\Behavior\SluggableSubscriber;
use App\Listener\Behavior\SortableSubscriber;
use App\Listener\Behavior\TaxonomySubscriber;
use App\Listener\Behavior\TimeStampableSubscriber;
use App\Listener\Behavior\TranslatableSubscriber;
use App\Listener\Behavior\CloneableSubscriber;
use App\Traits\SingletonTrait;
use Doctrine\Common\Cache\ApcuCache;
use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\EventManager;
use Doctrine\Common\Proxy\Autoloader as ProxyAutoLoader;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;

final class Core
{
	use SingletonTrait;

	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * Core constructor.
	 */
	private function __construct()
	{
		$this->entityManager = $this->createEntityManager();
	}

	/**
	 * Get entity manager.
	 * @return EntityManager
	 */
	public function getEntityManager()
	{
		return $this->entityManager;
	}

	/**
	 * Create entity manager.
	 * @return EntityManager
	 */
	private function createEntityManager()
	{
		$appConfig = Config::instance();
		$connectionConfig = ConnectionConfig::instance();

		$cache = $appConfig->getDebug() ? new ArrayCache() : new ApcuCache();

		$config = new Configuration();
		$config->setMetadataCacheImpl($cache);

		$driver = $config->newDefaultAnnotationDriver([
			$appConfig->getProjectDir() . '/src/App/Entity'
		], false);
		$config->setMetadataDriverImpl($driver);

		$config->setQueryCacheImpl($cache);
		$config->setResultCacheImpl($cache);
		$config->setProxyDir($appConfig->getCacheDir() . '/proxies');
		$config->setProxyNamespace('App\Proxies');
		$config->setAutoGenerateProxyClasses($appConfig->getDebug());
		$config->setEntityNamespaces([
			'App' => 'App\Entity'
		]);

		// register entity proxy auto loader
		ProxyAutoLoader::register($appConfig->getCacheDir() . '/proxies', 'App\Proxies');

		$eventManager = new EventManager();
		$eventManager->addEventSubscriber(new SluggableSubscriber());
		$eventManager->addEventSubscriber(new BlamableSubscriber());
		$eventManager->addEventSubscriber(new TranslatableSubscriber());
		$eventManager->addEventSubscriber(new TaxonomySubscriber());
		$eventManager->addEventSubscriber(new CloneableSubscriber());
		$eventManager->addEventSubscriber(new SortableSubscriber());
		$eventManager->addEventSubscriber(new TimeStampableSubscriber());

		return EntityManager::create([
			'driver' => $connectionConfig->getDriver(),
			'host' => $connectionConfig->getHostname(),
			'user' => $connectionConfig->getUser(),
			'password' => $connectionConfig->getPassword(),
			'dbname' => $connectionConfig->getDatabase(),
			'charset' => $connectionConfig->getCharset()
		], $config, $eventManager);
	}
}