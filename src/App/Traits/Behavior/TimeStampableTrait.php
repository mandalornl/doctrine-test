<?php

namespace App\Traits\Behavior;

trait TimeStampableTrait
{
	/**
	 * @var \DateTime
	 */
	protected $creationDate;

	/**
	 * @var \DateTime
	 */
	protected $modificationDate;

	/**
	 * Set creation date
	 *
	 * @param \DateTime $creationDate
	 *
	 * @return TimeStampableTrait
	 */
	public function setCreationDate(\DateTime $creationDate)
	{
		$this->creationDate = $creationDate;

		return $this;
	}

	/**
	 * Get creation date
	 *
	 * @return \DateTime
	 */
	public function getCreationDate()
	{
		return $this->creationDate;
	}

	/**
	 * Set modification date
	 *
	 * @param \DateTime $modificationDate
	 *
	 * @return TimeStampableTrait
	 */
	public function setModificationDate(\DateTime $modificationDate)
	{
		$this->modificationDate = $modificationDate;

		return $this;
	}

	/**
	 * Get modification date
	 *
	 * @return \DateTime
	 */
	public function getModificationDate()
	{
		return $this->modificationDate;
	}
}