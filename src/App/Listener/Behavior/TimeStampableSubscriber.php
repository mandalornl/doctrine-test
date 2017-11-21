<?php

namespace App\Listener\Behavior;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

final class TimeStampableSubscriber implements EventSubscriber
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
	 * @param $args
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		$reflection = new \ReflectionClass($entity);

		if ($reflection->hasProperty('createdAt'))
		{
			$property = $reflection->getProperty('createdAt');
			$property->setAccessible(true);

			if (!$property->getValue($entity) instanceof \DateTime)
			{
				$property->setValue($entity, new \DateTime());
			}
		}

		$this->setModifiedAt($entity, $reflection);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$this->setModifiedAt($args->getObject());
	}

	/**
	 * Set modified at
	 *
	 * @param object $entity
	 * @param \ReflectionClass $reflection [optional]
	 *
	 * @return bool
	 */
	private function setModifiedAt($entity, \ReflectionClass $reflection = null)
	{
		$reflection = $reflection ?: new \ReflectionClass($entity);

		if ($reflection->hasProperty('modifiedAt'))
		{
			$property = $reflection->getProperty('modifiedAt');
			$property->setAccessible(true);
			$property->setValue($entity, new \DateTime());

			return true;
		}

		return false;
	}
}