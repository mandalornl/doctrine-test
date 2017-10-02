<?php

namespace App\Traits\Behavior;

use App\Entity\User;

trait BlamableTrait
{
	/**
	 * @var User
	 */
	protected $owner;

	/**
	 * Set owner
	 *
	 * @param User $owner
	 *
	 * @return BlamableTrait
	 */
	public function setOwner($owner)
	{
		$this->owner = $owner;

		return $this;
	}

	/**
	 * Get owner
	 *
	 * @return User
	 */
	public function getOwner()
	{
		return $this->owner;
	}
}