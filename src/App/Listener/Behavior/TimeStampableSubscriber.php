<?php

namespace App\Listener\Behavior;

use App\Entity\Behavior\TimeStampableTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

final class TimeStampableSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
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
		$reflectionClass = new \ReflectionClass($entity);

		if (!$this->hasTrait($reflectionClass))
		{
			return;
		}

		$property = $reflectionClass->getProperty('createdAt');
		$property->setAccessible(true);

		if (!$property->getValue($entity) instanceof \DateTime)
		{
			$property->setValue($entity, new \DateTime());
		}

		$this->setModifiedAt($entity, $reflectionClass);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$entity = $args->getObject();
		$reflectionClass = new \ReflectionClass($entity);

		if (!$this->hasTrait($reflectionClass))
		{
			return;
		}

		$this->setModifiedAt($entity, $reflectionClass);
	}

	/**
	 * Set modified at
	 *
	 * @param object $entity
	 * @param \ReflectionClass $reflectionClass
	 */
	private function setModifiedAt($entity, \ReflectionClass $reflectionClass)
	{
		$property = $reflectionClass->getProperty('modifiedAt');
		$property->setAccessible(true);
		$property->setValue($entity, new \DateTime());
	}

	/**
	 * Check whether or not entity has trait
	 *
	 * @param \ReflectionClass $reflectionClass
	 *
	 * @return bool
	 */
	private function hasTrait(\ReflectionClass $reflectionClass): bool
	{
		return in_array(TimeStampableTrait::class, $reflectionClass->getTraitNames());
	}
}