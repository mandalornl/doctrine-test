<?php

namespace App\Traits\Behavior;

trait TranslationTrait
{
	/**
	 * @var int
	 */
	protected $id;

	/**
	 * @var TranslatableTrait
	 */
	protected $translatable;

	/**
	 * @var string
	 */
	protected $locale;

	/**
	 * Get id
	 *
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * Set translatable
	 *
	 * @param TranslatableTrait $translatable
	 *
	 * @return $this
	 */
	public function setTranslatable($translatable)
	{
		$this->translatable = $translatable;

		return $this;
	}

	/**
	 * Get translatable
	 *
	 * @return TranslatableTrait
	 */
	public function getTranslatable()
	{
		return $this->translatable;
	}

	/**
	 * Set locale
	 *
	 * @param string $locale
	 *
	 * @return $this
	 */
	public function setLocale(string $locale)
	{
		$this->locale = $locale;

		return $this;
	}

	/**
	 * Get locale
	 *
	 * @return string
	 */
	public function getLocale(): ?string
	{
		return $this->locale;
	}

	/**
	 * Get translatable entity class name
	 *
	 * @return string
	 */
	public static function getTranslatableEntityClassName()
	{
		return substr(__CLASS__, 0, -11);
	}
}