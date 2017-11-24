<?php

namespace App\Listener\Behavior;

use App\Entity\Taxonomy;
use App\Entity\Behavior\TaxonomyTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

final class TaxonomySubscriber implements EventSubscriber
{
	/**
	 * {@inheritdoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			Events::loadClassMetadata
		];
	}

	/**
	 * On load class metadata event
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

		if (!in_array(TaxonomyTrait::class, $reflectionClass->getTraitNames()))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('taxonomies'))
		{
			$classMetadata->mapManyToMany([
				'fieldName' => 'taxonomies',
				'fetch' => ClassMetadata::FETCH_LAZY,
				'targetEntity' => Taxonomy::class,
				'cascade' => ['persist'],
				'joinTable' => [
					'name' => "{$classMetadata->getTableName()}_to_taxonomy",
					'joinColumns' => [[
						'name' => 'entity_id',
						'referenceColumnName' => 'id',
						'onDelete' => 'CASCADE'
					]],
					'inverseJoinColumns' => [[
						'name' => 'taxonomy_id',
						'referenceColumnName' => 'id',
						'onDelete' => 'CASCADE'
					]]
				]
			]);
		}
	}
}