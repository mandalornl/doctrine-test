<?php

namespace App\Traits\Behavior;

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
	public function __call($method, array $args)
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
	public function __set($name, $value)
	{
		return $this->proxySetValue($name, $value);
	}

	/**
	 * {@inheritdoc}
	 */
	public function __get($name)
	{
		return $this->proxyGetValue($name);
	}

	/**
	 * Set value
	 *
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return TranslatableTrait
	 */
	private function proxySetValue($name, $value)
	{
		$reflection = new \ReflectionClass($this);
		if (!$reflection->hasProperty($name))
		{
			return $this;
		}

		$property = $reflection->getProperty($name);
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
	private function proxyGetValue($name)
	{
		$reflection = new \ReflectionClass($this);
		if (!$reflection->hasProperty($name))
		{
			return $this;
		}

		$property = $reflection->getProperty($name);
		$property->setAccessible(true);
		return $property->getValue($this);
	}

	/**
	 * Set current locale
	 *
	 * @param string $currentLocale
	 *
	 * @return TranslatableTrait
	 */
	public function setCurrentLocale($currentLocale)
	{
		$this->currentLocale = $currentLocale;

		return $this;
	}

	/**
	 * Get current locale
	 *
	 * @return string
	 */
	public function getCurrentLocale()
	{
		return $this->currentLocale;
	}

	/**
	 * Set default locale
	 *
	 * @param string $defaultLocale
	 *
	 * @return TranslatableTrait
	 */
	public function setDefaultLocale($defaultLocale)
	{
		$this->defaultLocale = $defaultLocale;

		return $this;
	}

	/**
	 * Get default locale
	 *
	 * @return string
	 */
	public function getDefaultLocale()
	{
		return $this->defaultLocale;
	}

	/**
	 * Add translation
	 *
	 * @param TranslationTrait $translation
	 *
	 * @return TranslatableTrait
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
	 * @return TranslatableTrait
	 */
	public function removeTranslation($translation)
	{
		$this->getTranslations()->removeElement($translation);

		return $this;
	}

	/**
	 * Get translations
	 *
	 * @return Collection
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
	 * @return TranslatableTrait
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
	 * @param $translation
	 *
	 * @return TranslatableTrait
	 */
	public function removeNewTranslation($translation)
	{
		$this->getNewTranslations()->removeElement($translation);

		return $this;
	}

	/**
	 * Get new translations
	 *
	 * @return Collection
	 */
	public function getNewTranslations()
	{
		return $this->newTranslations = $this->newTranslations ?: new ArrayCollection();
	}

	/**
	 * Merge new translations
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
	}

	/**
	 * Do translate
	 *
	 * @param string $locale
	 * @param bool $fallback
	 *
	 * @return mixed
	 */
	protected function doTranslate($locale = null, $fallback = true)
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

		$className = static::getTranslationEntityClassName();

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
	 * @param string $locale
	 * @param bool $fallback
	 *
	 * @return mixed
	 */
	abstract public function translate($locale = null, $fallback = true);

	/**
	 * Find translation by locale
	 *
	 * @param string $locale
	 * @param bool $withNewTranslations
	 *
	 * @return TranslationTrait
	 */
	private function findTranslationByLocale($locale, $withNewTranslations = true)
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
	 * @return bool|string
	 */
	private function computeFallbackLocale($locale)
	{
		if (strpos($locale, '_') === 2)
		{
			return substr($locale, 0, 2);
		}

		return false;
	}

	/**
	 * Get translation entity class name
	 *
	 * @return string
	 */
	public static function getTranslationEntityClassName()
	{
		return __CLASS__ . 'Translation';
	}
}