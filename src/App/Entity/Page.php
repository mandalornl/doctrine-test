<?php

namespace App\Entity;

use App\Traits\Behavior\BlamableTrait;
use App\Traits\Behavior\SluggableTrait;
use App\Traits\Behavior\TimeStampableTrait;
use App\Traits\Behavior\TranslatableTrait;

/**
 * Page
 */
class Page
{
	use TimeStampableTrait;
	use TranslatableTrait;
	use SluggableTrait;
	use BlamableTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $published = 0;

	/**
	 * @var \Doctrine\Common\Collections\Collection
	 */
    private $taxonomies;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		$this->taxonomies = new \Doctrine\Common\Collections\ArrayCollection();
	}

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Page
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set published
     *
     * @param boolean $published
     *
     * @return Page
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished()
    {
        return $this->published;
    }

	/**
	 * Add taxonomy
	 *
	 * @param \App\Entity\Taxonomy $taxonomy
	 *
	 * @return Page
	 */
	public function addTaxonomy(\App\Entity\Taxonomy $taxonomy)
	{
		$this->taxonomies[] = $taxonomy;

		return $this;
	}

	/**
	 * Remove taxonomy
	 *
	 * @param \App\Entity\Taxonomy $taxonomy
	 *
	 * @return Page
	 */
	public function removeTaxonomy(\App\Entity\Taxonomy $taxonomy)
	{
		$this->taxonomies->removeElement($taxonomy);

		return $this;
	}

	/**
	 * Get taxonomies
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getTaxonomies()
	{
		return $this->taxonomies;
	}

	/**
	 * {@inheritdoc}
	 *
	 * @return PageTranslation
	 */
    public function translate($locale = null, $fallback = true)
	{
		return $this->doTranslate($locale, $fallback);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValueToSlugify()
	{
		return $this->name;
	}
}
