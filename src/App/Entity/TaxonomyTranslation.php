<?php

namespace App\Entity;

use App\Entity\Behavior\TranslationTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(
 *     name="taxonomy_translation",
 *	   uniqueConstraints={
 *	       @ORM\UniqueConstraint(name="name_uniq", columns={ "name", "locale" })
 *	   },
 *     indexes={
 *		   @ORM\Index(name="name_idx", columns={ "name" })
 *	   }
 * )
 * @ORM\Entity()
 */
class TaxonomyTranslation
{
	use TranslationTrait;

	/**
	 * @var string
	 *
	 * @ORM\Column(name="name", type="string", nullable=true)
	 */
	private $name;

	/**
	 * Set name
	 *
	 * @param string $name
	 *
	 * @return TaxonomyTranslation
	 */
	public function setName(string $name = null): TaxonomyTranslation
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @Return string
	 */
	public function getName(): ?string
	{
		return $this->name;
	}
}