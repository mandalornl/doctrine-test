<?php

namespace App\Traits\Behavior;

trait SluggableTrait
{
	/**
	 * @var string
	 */
	protected $slug;

	/**
	 * Set slug
	 *
	 * @param string $slug
	 *
	 * @return SluggableTrait
	 */
	public function setSlug($slug)
	{
		$this->slug = $slug;

		return $this;
	}

	/**
	 * Get slug
	 *
	 * @return string
	 */
	public function getSlug()
	{
		return $this->slug;
	}

	/**
	 * Get value to slugify
	 *
	 * @return string
	 */
	abstract public function getValueToSlugify();
}