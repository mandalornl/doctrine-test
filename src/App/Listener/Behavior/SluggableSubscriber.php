<?php

namespace App\Listener\Behavior;

use App\Helper\SlugifyHelper;
use App\Traits\Behavior\SluggableTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

class SluggableSubscriber implements EventSubscriber
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

		$this->mapField($classMetadata);
	}

	/**
	 * Map field
	 *
	 * @param ClassMetadata $classMetadata
	 */
	private function mapField(ClassMetadata $classMetadata)
	{
		$reflectionClass = $classMetadata->getReflectionClass();

		if (!in_array(SluggableTrait::class, $reflectionClass->getTraitNames()))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('slug'))
		{
			$classMetadata->mapField([
				'fieldName' => 'slug',
				'type' => 'string',
				'nullable' => false
			]);
		}

		$name = 'slug_idx';
		if (!isset($classMetadata->table['uniqueConstraints'][$name]))
		{
			$classMetadata->table['uniqueConstraints'][$name] = [
				'columns' => ['id', 'slug']
			];
		}
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