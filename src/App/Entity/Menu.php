<?php

namespace App\Entity;

use App\Traits\Behavior\IdableTrait;
use App\Traits\Behavior\TimeStampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="menu")
 * @ORM\Entity()
 */
class Menu
{
	use IdableTrait;
	use TimeStampableTrait;

    /**
     * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

	/**
	 * @var Menu
	 *
	 * @ORM\ManyToOne(targetEntity="App\Entity\Menu", inversedBy="children")
	 * @ORM\JoinColumns({
	 *     @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
	 * })
	 */
    private $parent;

	/**
	 * @var Collection
	 *
	 * @ORM\OneToMany(targetEntity="App\Entity\Menu", mappedBy="parent")
	 * @ORM\OrderBy({ "weight" = "ASC" })
	 */
    private $children;

	/**
	 * @var int
	 *
	 * @ORM\Column(name="weight", type="integer", options={ "default" = 0 })
	 */
    private $weight = 0;

	/**
	 * Menu constructor
	 */
    public function __construct()
	{
		$this->children = new ArrayCollection();
	}

	/**
     * Set name
     *
     * @param string $name
     *
     * @return Menu
     */
    public function setName(string $name = null): Menu
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

	/**
	 * Set parent
	 *
	 * @param Menu $parent
	 *
	 * @return Menu
	 */
    public function setParent(Menu $parent = null): Menu
	{
		$this->parent = $parent;

		return $this;
	}

	/**
	 * Get parent
	 *
	 * @return Menu
	 */
	public function getParent(): ?Menu
	{
		return $this->parent;
	}

	/**
	 * Add child
	 *
	 * @param Menu $child
	 *
	 * @return Menu
	 */
	public function addChild(Menu $child): Menu
	{
		$this->children[] = $child;

		return $this;
	}

	/**
	 * Remove child
	 *
	 * @param Menu $child
	 *
	 * @return Menu
	 */
	public function removeChild(Menu $child): Menu
	{
		$this->children->removeElement($child);

		return $this;
	}

	/**
	 * Get children
	 *
	 * @return ArrayCollection
	 */
	public function getChildren()
	{
		return $this->children;
	}

	/**
	 * Set weight
	 *
	 * @param int $weight
	 *
	 * @return Menu
	 */
	public function setWeight(int $weight = 0): Menu
	{
		$this->weight = $weight;

		return $this;
	}

	/**
	 * Get weight
	 *
	 * @return int
	 */
	public function getWeight(): int
	{
		return $this->weight;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}
