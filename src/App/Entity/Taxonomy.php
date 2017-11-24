<?php

namespace App\Entity;

use App\Entity\Behavior\IdableTrait;
use App\Entity\Behavior\TimeStampableTrait;
use App\Entity\Behavior\TranslatableTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="taxonomy")
 * @ORM\Entity()
 */
class Taxonomy
{
	use IdableTrait;
	use TimeStampableTrait;
	use TranslatableTrait;

	/**
	 * {@inheritdoc}
	 *
	 * @return TaxonomyTranslation
	 */
	public function translate(string $locale = null, bool $fallback = true): TaxonomyTranslation
	{
		return $this->doTranslate($locale, $fallback);
	}
}