<?php

namespace App\Listener\Behavior;

use App\Entity\Behavior\CloneableTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

final class CloneableSubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::loadClassMetadata,
			Events::prePersist
		];
	}

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

		$reflectionClass = $classMetadata->getReflectionClass();

		if (!in_array(CloneableTrait::class, $reflectionClass->getTraitNames()))
		{
			return;
		}

		$this->mapVersion($classMetadata, $reflectionClass);
		$this->mapVersions($classMetadata, $reflectionClass);
	}

	/**
	 * Map version
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapVersion(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass)
	{
		if (!$classMetadata->hasAssociation('version'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'version',
				'inversedBy'	=> 'versions',
				'cascade'		=> ['persist', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'joinColumns' 	=> [[
					'name' 					=> 'version_id',
					'referencedColumnName'	=> 'id',
					'onDelete'				=> 'CASCADE'
				]]
			]);
		}
	}

	/**
	 * Map versions
	 *
	 * @param ClassMetadata $classMetadata
	 * @param \ReflectionClass $reflectionClass
	 */
	private function mapVersions(ClassMetadata $classMetadata, \ReflectionClass $reflectionClass)
	{
		if (!$classMetadata->hasAssociation('versions'))
		{
			$classMetadata->mapOneToMany([
				'fieldName' 	=> 'versions',
				'mappedBy'		=> 'version',
				'cascade'		=> ['persist', 'merge', 'remove'],
				'fetch'			=> ClassMetadata::FETCH_LAZY,
				'targetEntity'	=> $reflectionClass->getName(),
				'orphanRemoval'	=> true
			]);
		}
	}

	/**
	 * On pre persist event
	 *
	 * @param LifecycleEventArgs $args
	 */
	public function prePersist(LifecycleEventArgs $args)
	{
		$entity = $args->getObject();
		$reflectionClass = new \ReflectionClass($entity);

		if (!in_array(CloneableTrait::class, $reflectionClass->getTraitNames()))
		{
			return;
		}

		$property = $reflectionClass->getProperty('version');
		$property->setAccessible(true);

		if ($property->getValue($entity) !== null)
		{
			return;
		}

		$property->setValue($entity, $entity);
	}
}