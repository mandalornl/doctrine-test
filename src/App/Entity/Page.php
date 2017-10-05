<?php

namespace App\Entity;

use App\Traits\Behavior\BlamableTrait;
use App\Traits\Behavior\SluggableTrait;
use App\Traits\Behavior\SoftDeletableTrait;
use App\Traits\Behavior\TaxonomyTrait;
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
	use SoftDeletableTrait;
	use TaxonomyTrait;

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
