<?php

namespace App\Entity;

use App\Traits\Behavior\BlamableTrait;
use App\Traits\Behavior\IdableTrait;
use App\Traits\Behavior\SluggableTrait;
use App\Traits\Behavior\SoftDeletableTrait;
use App\Traits\Behavior\TaxonomyTrait;
use App\Traits\Behavior\TimeStampableTrait;
use App\Traits\Behavior\TranslatableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity()
 */
class Page
{
	use IdableTrait;
	use TimeStampableTrait;
	use SluggableTrait;
	use BlamableTrait;
	use SoftDeletableTrait;
	use TranslatableTrait;
	use TaxonomyTrait;

    /**
     * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=false)
     */
    private $name;

    /**
     * @var boolean
	 *
	 * @ORM\Column(name="published", type="boolean", options={ "default" = 0 })
     */
    private $published = 0;

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Page
     */
    public function setName(string $name = null): Page
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
     * Set published
     *
     * @param boolean $published
     *
     * @return Page
     */
    public function setPublished(bool $published = false): Page
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return boolean
     */
    public function getPublished(): bool
    {
        return $this->published;
    }

	/**
	 * {@inheritdoc}
	 *
	 * @return PageTranslation
	 */
    public function translate(string $locale = null, bool $fallback = true): PageTranslation
	{
		return $this->doTranslate($locale, $fallback);
	}

	/**
	 * {@inheritdoc}
	 */
	public function getValueToSlugify(): string
	{
		return $this->name;
	}

	/**
	 * {@inheritdoc}
	 */
	public function __toString(): string
	{
		return $this->name;
	}
}
