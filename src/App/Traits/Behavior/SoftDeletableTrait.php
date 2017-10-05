<?php

namespace App\Traits\Behavior;

trait SoftDeletableTrait
{
	/**
	 * @var bool
	 */
	protected $deleted = 0;

	/**
	 * Set deleted
	 *
	 * @param bool $deleted
	 *
	 * @return $this
	 */
	public function setDeleted($deleted)
	{
		$this->deleted = (bool)$deleted;

		return $this;
	}

	/**
	 * Set deleted
	 *
	 * @return bool
	 */
	public function getDeleted()
	{
		return $this->deleted;
	}
}