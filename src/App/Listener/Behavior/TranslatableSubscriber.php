<?php

namespace App\Listener\Behavior;

use App\Traits\Behavior\TranslatableTrait;
use App\Traits\Behavior\TranslationTrait;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Id\IdentityGenerator;
use Doctrine\ORM\Mapping\ClassMetadata;

final class TranslatableSubscriber implements EventSubscriber
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

		$this->mapTranslatable($classMetadata);
		$this->mapTranslation($classMetadata);
	}

	/**
	 * Map translatable
	 *
	 * @param ClassMetadata $classMetaData
	 */
	private function mapTranslatable(ClassMetadata $classMetaData)
	{
		$reflectionClass = $classMetaData->getReflectionClass();

		if (!in_array(TranslatableTrait::class, $reflectionClass->getTraitNames()))
		{
			return;
		}

		if (!$classMetaData->hasAssociation('translations'))
		{
			$classMetaData->mapOneToMany([
				'fieldName' 	=> 'translations',
				'mappedBy' 		=> 'translatable',
				'indexBy' 		=> 'locale',
				'cascade' 		=> ['persist', 'merge', 'remove'],
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> $reflectionClass->getMethod('getTranslationEntityClassName')->invoke(null),
				'orphanRemoval' => true
			]);
		}
	}

	/**
	 * Map translation
	 *
	 * @param ClassMetadata $classMetadata
	 */
	private function mapTranslation(ClassMetadata $classMetadata)
	{
		$reflectionClass = $classMetadata->getReflectionClass();

		if (!in_array(TranslationTrait::class, $reflectionClass->getTraitNames()))
		{
			return;
		}

		if (!$classMetadata->hasAssociation('translatable'))
		{
			$classMetadata->mapManyToOne([
				'fieldName' 	=> 'translatable',
				'inversedBy' 	=> 'translations',
				'cascade' 		=> ['persist', 'merge'],
				'fetch' 		=> ClassMetadata::FETCH_LAZY,
				'targetEntity' 	=> $reflectionClass->getMethod('getTranslatableEntityClassName')->invoke(null),
				'joinColumns' 	=> [[
					'name' 					=> 'translatable_id',
					'referencedColumnName' 	=> 'id',
					'onDelete' 				=> 'CASCADE'
				]]
			]);
		}

		if (!$classMetadata->hasField('locale') && !$classMetadata->hasAssociation('locale'))
		{
			$classMetadata->mapField([
				'fieldName' => 'locale',
				'type' 		=> 'string'
			]);
		}

		if (!$classMetadata->hasField('id'))
		{
			$classMetadata->mapField([
				'id' 		=> true,
				'fieldName' => 'id',
				'type' 		=> 'bigint'
			]);

			$classMetadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_IDENTITY);
			$classMetadata->setIdGenerator(new IdentityGenerator());
		}

		$name = 'translation_uniq';
		if (!isset($classMetadata->table['uniqueConstraints'][$name]))
		{
			$classMetadata->table['uniqueConstraints'][$name] = [
				'columns' => ['translatable_id', 'locale']
			];
		}
	}
}