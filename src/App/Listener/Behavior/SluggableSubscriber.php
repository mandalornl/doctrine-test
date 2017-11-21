<?php

namespace App\Listener\Behavior;

use App\Helper\SlugifyHelper;
use App\Traits\Behavior\SluggableTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

class SluggableSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents()
	{
		return [
			Events::prePersist,
			Events::preUpdate
		];
	}

	/**
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$this->setSlug($args->getObject());
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$this->setSlug($args->getObject());
	}

	/**
	 * Set slug
	 *
	 * @param object $entity
	 * @param \ReflectionClass $reflection
	 *
	 * @return bool
	 */
	private function setSlug($entity, \ReflectionClass $reflection = null)
	{
		$reflection = $reflection ?: new \ReflectionClass($entity);

		if ($reflection->hasProperty('slug'))
		{
			$property = $reflection->getProperty('slug');
			$property->setAccessible(true);

			/**
			 * @var SluggableTrait $entity
			 */
			$property->setValue($entity, SlugifyHelper::slugify($entity->getValueToSlugify()));

			return true;
		}

		return false;
	}
}