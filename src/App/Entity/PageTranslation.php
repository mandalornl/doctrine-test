<?php

namespace App\Entity;

use App\Traits\Behavior\TranslationTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="page_translation")
 * @ORM\Entity()
 */
class PageTranslation
{
	use TranslationTrait;

    /**
     * @var string
	 *
	 * @ORM\Column(name="title", type="string", nullable=true)
     */
    private $title;

    /**
     * @var string
	 *
	 * @ORM\Column(name="body", type="text", nullable=true)
     */
    private $body;

    /**
     * @var string
	 *
	 * @ORM\Column(name="meta_title", type="string", nullable=true)
     */
    private $metaTitle;

    /**
     * @var string
	 *
	 * @ORM\Column(name="meta_keywords", type="string", nullable=true)
     */
    private $metaKeywords;

    /**
     * @var string
	 *
	 * @ORM\Column(name="meta_description", type="text", nullable=true)
     */
    private $metaDescription;


    /**
     * Set title
     *
     * @param string $title
     *
     * @return PageTranslation
     */
    public function setTitle(string $title = null): PageTranslation
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set body
     *
     * @param string $body
     *
     * @return PageTranslation
     */
    public function setBody(string $body = null): PageTranslation
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return PageTranslation
     */
    public function setMetaTitle(string $metaTitle = null): PageTranslation
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle(): ?string
    {
        return $this->metaTitle;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     *
     * @return PageTranslation
     */
    public function setMetaKeywords(string $metaKeywords = null): PageTranslation
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords(): ?string
    {
        return $this->metaKeywords;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return PageTranslation
     */
    public function setMetaDescription(string $metaDescription = null): PageTranslation
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription(): ?string
    {
        return $this->metaDescription;
    }
}
