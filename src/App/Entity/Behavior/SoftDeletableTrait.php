<?php

namespace App\Entity\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait SoftDeletableTrait
{
	/**
	 * @var bool
	 * 
	 * @ORM\Column(name="deleted", type="boolean", options={ "default" = 0 })
	 */
	protected $deleted = 0;

	/**
	 * Set deleted
	 *
	 * @param bool $deleted
	 *
	 * @return $this
	 */
	public function setDeleted(bool $deleted = false)
	{
		$this->deleted = $deleted;

		return $this;
	}

	/**
	 * Set deleted
	 *
	 * @return bool
	 */
	public function getDeleted(): bool
	{
		return $this->deleted;
	}
}