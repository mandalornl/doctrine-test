<?php

namespace App\Listener\Behavior;

use App\Traits\Behavior\TimeStampableTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

final class TimeStampableSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents()
	{
		return [
			Events::loadClassMetadata,
			Events::prePersist,
			Events::preUpdate
		];
	}

	/**
	 * On load class metadata
	 *
	 * @param LoadClassMetadataEventArgs $args
	 */
	public function loadClassMetadata(LoadClassMetadataEventArgs $args)
	{
		/**
		 * @var ClassMetadata $classMetadata
		 */
		$classMetadata = $args->getClassMetadata();

		if ($classMetadata->getReflectionClass() === null)
		{
			return;
		}

		$this->mapFields($classMetadata);
	}

	/**
	 * Map fields
	 *
	 * @param ClassMetadata $classMetadata
	 */
	private function mapFields(ClassMetadata $classMetadata)
	{
		$reflectionClass = $classMetadata->getReflectionClass();

		if (!in_array(TimeStampableTrait::class, $reflectionClass->getTraitNames()))
		{
			return;
		}

		foreach (['creationDate', 'modificationDate'] as $property)
		{
			if (!$classMetadata->hasAssociation($property))
			{
				$classMetadata->mapField([
					'fieldName' => $property,
					'columnName' => preg_replace_callback('#[A-Z]#', function(array $matches)
					{
						return '_' . strtolower($matches[0]);
					}, $property),
					'type' => 'datetime',
					'options' => [
						'default' => 'CURRENT_TIMESTAMP'
					]
				]);
			}
		}
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

		if ($reflection->hasProperty('creationDate'))
		{
			$property = $reflection->getProperty('creationDate');
			$property->setAccessible(true);

			if (!$property->getValue($entity) instanceof \DateTime)
			{
				$property->setValue($entity, new \DateTime());
			}
		}

		$this->setModificationDate($entity, $reflection);
	}

	/**
	 * On pre update event
	 *
	 * @param PreUpdateEventArgs $args
	 */
	public function preUpdate(PreUpdateEventArgs $args)
	{
		$this->setModificationDate($args->getObject());
	}

	/**
	 * Set modification date
	 *
	 * @param object $entity
	 * @param \ReflectionClass $reflection [optional]
	 *
	 * @return bool
	 */
	private function setModificationDate($entity, \ReflectionClass $reflection = null)
	{
		$reflection = $reflection ?: new \ReflectionClass($entity);

		if ($reflection->hasProperty('modificationDate'))
		{
			$property = $reflection->getProperty('modificationDate');
			$property->setAccessible(true);
			$property->setValue($entity, new \DateTime());

			return true;
		}

		return false;
	}
}