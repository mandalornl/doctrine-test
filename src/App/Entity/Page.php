<?php

namespace App\Entity;

use App\Entity\Behavior\BlamableTrait;
use App\Entity\Behavior\IdableTrait;
use App\Entity\Behavior\SluggableTrait;
use App\Entity\Behavior\SoftDeletableTrait;
use App\Entity\Behavior\SortableTrait;
use App\Entity\Behavior\TaxonomyTrait;
use App\Entity\Behavior\TimeStampableTrait;
use App\Entity\Behavior\TranslatableTrait;
use App\Entity\Behavior\CloneableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity()
 */
class Page
{
	use IdableTrait;
	use SluggableTrait;
	use BlamableTrait;
	use SoftDeletableTrait;
	use TranslatableTrait;
	use TaxonomyTrait;
	use SortableTrait;
	use CloneableTrait;
	use TimeStampableTrait;

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
