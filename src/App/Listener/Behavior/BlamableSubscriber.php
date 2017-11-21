<?php

namespace App\Listener\Behavior;

use App\Entity\User;
use App\Traits\Behavior\BlamableTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;

class BlamableSubscriber implements EventSubscriber
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

		if (!in_array(BlamableTrait::class, $reflectionClass->getTraitNames()))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('owner'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'owner',
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> $reflectionClass->getMethod('getBlamableEntityClassName')->invoke(null),
				'joinColumn' 	=> [[
					'name' 					=> 'owner_id',
					'referencedColumnName' 	=> 'id',
					'onDelete' 				=> 'SET NULL'
				]]
			]);
		}
	}
}