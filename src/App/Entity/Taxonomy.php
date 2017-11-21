<?php

namespace App\Entity;

use App\Traits\Behavior\IdableTrait;
use App\Traits\Behavior\TimeStampableTrait;
use App\Traits\Behavior\TranslatableTrait;
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