<?php

namespace App\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait TranslatableTrait
{
	/**
	 * @var Collection
	 */
	protected $translations;

	/**
	 * @var Collection
	 */
	protected $newTranslations;

	/**
	 * @var string
	 */
	protected $currentLocale = 'nl';

	/**
	 * @var string
	 */
	protected $defaultLocale = 'nl';

	/**
	 * {@inheritdoc}
	 */
	public function __call(string $method, array $args)
	{
		foreach (['set', 'get'] as $methodType)
		{
			if (!(strpos($method, $methodType)) === 0)
			{
				continue;
			}

			$name = lcfirst(str_replace($methodType, '', $method));
			$proxyMethod = sprintf('proxy%sValue', ucfirst($methodType));
			return call_user_func_array([$this, $proxyMethod], array_merge([$name], $args));
		}

		$entity = $this;
		if ($this->currentLocale !== $this->defaultLocale)
		{
			$entity = $this->translate($this->currentLocale);
		}

		if (count($args))
		{
			return call_user_func_array([$entity, $method], $args);
		}

		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __set(string $name, mixed $value)
	{
		return $this->proxySetValue($name, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get(string $name)
	{
		return $this->proxyGetValue($name);
	}

	/**
	 * Set value
	 *
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return $this
	 */
	private function proxySetValue(string $name, mixed $value)
	{
		$reflectionClass = new \ReflectionClass($this);
		if (!$reflectionClass->hasProperty($name))
		{
			return $this;
		}

		$property = $reflectionClass->getProperty($name);
		$property->setAccessible(true);
		$property->setValue($this, $value);

		return $this;
	}

	/**
	 * Get value
	 *
	 * @param string $name
	 *
	 * @return mixed
	 */
	private function proxyGetValue(string $name)
	{
		$reflectionClass = new \ReflectionClass($this);
		if (!$reflectionClass->hasProperty($name))
		{
			return $this;
		}

		$property = $reflectionClass->getProperty($name);
		$property->setAccessible(true);
		return $property->getValue($this);
	}

	/**
	 * Set current locale
	 *
	 * @param string $currentLocale
	 *
	 * @return $this
	 */
	public function setCurrentLocale(string $currentLocale)
	{
		$this->currentLocale = $currentLocale;

		return $this;
	}

	/**
	 * Get current locale
	 *
	 * @return string
	 */
	public function getCurrentLocale(): string
	{
		return $this->currentLocale;
	}

	/**
	 * Set default locale
	 *
	 * @param string $defaultLocale
	 *
	 * @return $this
	 */
	public function setDefaultLocale(string $defaultLocale)
	{
		$this->defaultLocale = $defaultLocale;

		return $this;
	}

	/**
	 * Get default locale
	 *
	 * @return string
	 */
	public function getDefaultLocale(): string
	{
		return $this->defaultLocale;
	}

	/**
	 * Add translation
	 *
	 * @param TranslationTrait $translation
	 *
	 * @return $this
	 */
	public function addTranslation($translation)
	{
		$this->getTranslations()->set((string)$translation->getLocale(), $translation);
		$translation->setTranslatable($this);

		return $this;
	}

	/**
	 * Remove translation
	 *
	 * @param TranslationTrait $translation
	 *
	 * @return $this
	 */
	public function removeTranslation($translation)
	{
		$this->getTranslations()->removeElement($translation);

		return $this;
	}

	/**
	 * Get translations
	 *
	 * @return ArrayCollection
	 */
	public function getTranslations()
	{
		return $this->translations = $this->translations ?: new ArrayCollection();
	}

	/**
	 * Add new translation
	 *
	 * @param TranslationTrait $translation
	 *
	 * @return $this
	 */
	public function addNewTranslation($translation)
	{
		$this->getNewTranslations()->set((string)$translation->getLocale(), $translation);
		$translation->setTranslatable($this);

		return $this;
	}

	/**
	 * Remove new translation
	 *
	 * @param TranslationTrait $translation
	 *
	 * @return $this
	 */
	public function removeNewTranslation($translation)
	{
		$this->getNewTranslations()->removeElement($translation);

		return $this;
	}

	/**
	 * Get new translations
	 *
	 * @return ArrayCollection
	 */
	public function getNewTranslations()
	{
		return $this->newTranslations = $this->newTranslations ?: new ArrayCollection();
	}

	/**
	 * Merge new translations
	 *
	 * @return $this
	 */
	public function mergeNewTranslations()
	{
		$translations = $this->getTranslations();
		$newTranslations = $this->getNewTranslations();

		foreach ($newTranslations as $translation)
		{
			if ($translations->contains($translation))
			{
				continue;
			}

			$this->addTranslation($translation);
			$newTranslations->removeElement($translation);
		}

		return $this;
	}

	/**
	 * Do translate
	 *
	 * @param string $locale [optional]
	 * @param bool $fallback [optional]
	 *
	 * @return mixed
	 */
	protected function doTranslate(string $locale = null, bool $fallback = true)
	{
		if ($locale === null)
		{
			$locale = $this->currentLocale;
		}

		if (($translation = $this->findTranslationByLocale($locale)))
		{
			return $translation;
		}

		if ($fallback)
		{
			if (($fallbackLocale = $this->computeFallbackLocale($locale)) &&
				($translation = $this->findTranslationByLocale($fallbackLocale))
			)
			{
				return $translation;
			}

			if (($translation = $this->findTranslationByLocale($this->defaultLocale, false)))
			{
				return $translation;
			}
		}

		$className = self::getTranslationEntityClassName();

		/**
		 * @var TranslationTrait $translation
		 */
		$translation = new $className();
		$translation->setLocale($locale);

		$this->addNewTranslation($translation);
		return $translation;
	}

	/**
	 * Translate
	 *
	 * @param string $locale [optional]
	 * @param bool $fallback [optional]
	 *
	 * @return mixed
	 */
	abstract public function translate(string $locale = null, bool $fallback = true);

	/**
	 * Find translation by locale
	 *
	 * @param string $locale
	 * @param bool $withNewTranslations [optional]
	 *
	 * @return mixed
	 */
	private function findTranslationByLocale(string $locale, bool $withNewTranslations = true)
	{
		if (($translations = $this->getTranslations()) && $translations->containsKey($locale))
		{
			return $translations->get($locale);
		}

		if ($withNewTranslations)
		{
			if (($newTranslations = $this->getNewTranslations()) && $newTranslations->containsKey($locale))
			{
				return $newTranslations->get($locale);
			}
		}

		return null;
	}

	/**
	 * Compute fallback locale
	 *
	 * @param string $locale
	 *
	 * @return string
	 */
	private function computeFallbackLocale(string $locale): ?string
	{
		if (strpos($locale, '_') === 2)
		{
			return substr($locale, 0, 2);
		}

		return null;
	}

	/**
	 * Clone translations
	 */
	protected function onCloneTranslations()
	{
		foreach ($this->translations as $translation)
		{
			$this->addTranslation(clone $translation);
			$this->removeTranslation($translation);
		}
	}

	/**
	 * Get translation entity class name
	 *
	 * @return string
	 */
	public static function getTranslationEntityClassName(): string
	{
		return __CLASS__  . 'Translation';
	}
}