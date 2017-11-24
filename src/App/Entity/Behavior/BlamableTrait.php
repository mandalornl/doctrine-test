<?php

namespace App\Entity\Behavior;

use App\Entity\User;
use App\Entity\OwnerInterface;

trait BlamableTrait
{
	/**
	 * @var OwnerInterface
	 */
	protected $owner;

	/**
	 * Set owner
	 *
	 * @param OwnerInterface $owner
	 *
	 * @return $this
	 */
	public function setOwner(OwnerInterface $owner = null)
	{
		$this->owner = $owner;

		return $this;
	}

	/**
	 * Get owner
	 *
	 * @return OwnerInterface
	 */
	public function getOwner(): ?OwnerInterface
	{
		return $this->owner;
	}

	/**
	 * Get blamable entity class name
	 *
	 * @return string
	 */
	public static function getBlamableEntityClassName(): string
	{
		return User::class;
	}
}