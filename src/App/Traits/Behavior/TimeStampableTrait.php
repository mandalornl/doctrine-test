<?php

namespace App\Traits\Behavior;

use Doctrine\ORM\Mapping as ORM;

trait TimeStampableTrait
{
	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="created_at", type="datetime", options={ "default" = "CURRENT_TIMESTAMP" })
	 */
	protected $createdAt;

	/**
	 * @var \DateTime
	 *
	 * @ORM\Column(name="modified_at", type="datetime", options={ "default" = "CURRENT_TIMESTAMP" })
	 */
	protected $modifiedAt;

	/**
	 * Set created at
	 *
	 * @param \DateTime $createdAt
	 *
	 * @return TimeStampableTrait
	 */
	public function setCreatedAt(\DateTime $createdAt)
	{
		$this->createdAt = $createdAt;

		return $this;
	}

	/**
	 * Get created at
	 *
	 * @return \DateTime
	 */
	public function getCreatedAt(): ?\DateTime
	{
		return $this->createdAt;
	}

	/**
	 * Set modified at
	 *
	 * @param \DateTime $modifiedAt
	 *
	 * @return TimeStampableTrait
	 */
	public function setModifiedAt(\DateTime $modifiedAt)
	{
		$this->modifiedAt = $modifiedAt;

		return $this;
	}

	/**
	 * Get modified at
	 *
	 * @return \DateTime
	 */
	public function getModifiedAt(): ?\DateTime
	{
		return $this->modifiedAt;
	}
}