<?php

namespace App\Listener\Behavior;

use App\Traits\Behavior\SoftDeletableTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

class SoftDeletableSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents()
	{
		return [
			Events::loadClassMetadata
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

		if (!in_array(SoftDeletableTrait::class, $reflectionClass->getTraitNames()))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('deleted'))
		{
			$classMetadata->mapField([
				'fieldName' => 'deleted',
				'type' => 'boolean',
				'nullable' => false,
				'options' => [
					'default' => 0
				]
			]);
		}
	}
}