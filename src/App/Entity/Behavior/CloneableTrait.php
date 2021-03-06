<?php

namespace App\Entity\Behavior;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

trait CloneableTrait
{
	/**
	 * @var int
	 */
	protected $version;

	/**
	 * @var Collection
	 */
	protected $versions;

	/**
	 * Add version
	 *
	 * @param CloneableTrait $version
	 *
	 * @return $this
	 */
	public function addVersion($version)
	{
		$this->getVersions()->add($version);

		return $this;
	}

	/**
	 * Remove version
	 *
	 * @param CloneableTrait $version
	 *
	 * @return $this
	 */
	public function removeVersion($version)
	{
		$this->getVersions()->removeElement($version);

		return $this;
	}

	/**
	 * Get versions
	 *
	 * @return ArrayCollection
	 */
	public function getVersions()
	{
		return $this->versions = $this->versions ?: new ArrayCollection();
	}

	/**
	 * {@inheritdoc}
	 */
	public function __clone()
	{
		$reflectionClass = new \ReflectionClass($this);

		foreach ($reflectionClass->getMethods(\ReflectionMethod::IS_PROTECTED) as $reflectionMethod)
		{
			if (strpos($reflectionMethod->getName(), 'onClone') !== 0)
			{
				continue;
			}

			call_user_func([$this, $reflectionMethod->getName()]);
		}
	}
}