<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 25 08 2015
 */
namespace App\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\Collection;

/**
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\FrontPageRepository")
 * @ORM\Table(name="front_pages")
 */
class FrontPage
{
    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("id")
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * @Gedmo\Slug(fields={"name"})
     *
     * @ORM\Column(type="string", length=128, unique=true)
     */
    protected $slug;

    /**
     * @JMS\Expose
     * @JMS\Type("datetime")
     * @JMS\SerializedName("show_date")
     *
     * @ORM\Column(type="datetime", name="show_date")
     */
    protected $showDate;

    /**
     * @JMS\Expose
     * @JMS\Type("datetime")
     * @JMS\SerializedName("hide_date")
     *
     * @ORM\Column(type="datetime", name="hide_date")
     */
    protected $hideDate;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("status")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="smallint")
     */
    protected $status;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("default")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="smallint", name="is_default")
     */
    protected $default;

    /**
     * @ORM\OneToOne(targetEntity="Seo", cascade={"persist", "remove"})
     */
    protected $seo;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("hero_image")
     *
     *
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist"})
     * @ORM\JoinColumn(name="hero_image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $heroImage;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("alternative_hero_text")
     *
     *
     * @ORM\Column(type="string", length=128, unique=false, nullable=true, name="alternative_hero_text")
     */
    protected $alternativeHeroText;

    /**
     * @var Collection<App\CoreBundle\Entity\FrontPageBlock>
     *
     * @JMS\Expose
     * @JMS\SerializedName("blocks")
     * @JMS\Type("array<App\CoreBundle\Entity\FrontPageBlock>")
     *
     * @ORM\OneToMany(targetEntity="FrontPageBlock", mappedBy="frontPage", cascade={"persist", "remove"})
     * @ORM\OrderBy({"priority" = "ASC"})
     */
    protected $blocks;

    /**
     * set blocks to ArrayCollection
     */
    public function __construct()
    {
        $this->blocks = new ArrayCollection();
    }

    public function __clone()
    {
        if ($this->id) {
            $this->setDefault(0);
            $this->setId(null);
            $this->setSeo(null);
            foreach ($this->blocks as $block) {
                $newBlock = clone $block;
                $newBlock->setFrontPage($this);
                $this->blocks->add($newBlock);
            }
        }
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id.
     * @param $id
     *
     * @return FrontPage
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return FrontPage
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return FrontPage
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug.
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }


    /**
     * Set showDate.
     *
     * @param $showDate
     *
     * @return FrontPage
     */
    public function setShowDate($showDate)
    {
        $this->showDate = $showDate;

        return $this;
    }

    /**
     * Get showDate.
     *
     * @return mixed
     */
    public function getShowDate()
    {
        return $this->showDate;
    }

    /**
     * Set status.
     *
     * @param $status
     *
     * @return FrontPage
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set default.
     *
     * @param $default
     *
     * @return FrontPage
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }

    /**
     * Get default.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set heroImage.
     *
     * @param Image|null $heroImage
     *
     * @return FrontPage
     */
    public function setHeroImage(Image $heroImage = null)
    {
        $this->heroImage = $heroImage;

        return $this;
    }

    /**
     * Get heroImage.
     *
     * @return Image
     */
    public function getHeroImage()
    {
        return $this->heroImage;
    }

    /**
     * Set alternativeHeroText.
     *
     * @param string $alternativeHeroText
     *
     * @return FrontPage
     */
    public function setAlternativeHeroText($alternativeHeroText)
    {
        $this->alternativeHeroText = $alternativeHeroText;

        return $this;
    }

    /**
     * Get alternativeHeroText.
     *
     * @return string
     */
    public function getAlternativeHeroText()
    {
        return $this->alternativeHeroText;
    }

    /**
     * Set hide.
     *
     * @param $hideDate
     *
     * @return FrontPage
     */
    public function setHideDate($hideDate)
    {
        $this->hideDate = $hideDate;

        return $this;
    }

    /**
     * Get hide.
     *
     * @return mixed
     */
    public function getHideDate()
    {
        return $this->hideDate;
    }

    /**
     * Set seo.
     *
     * @param Seo|null $seo
     *
     * @return FrontPage
     */
    public function setSeo(Seo $seo = null)
    {
        $this->seo = $seo;

        return $this;
    }

    /**
     * Get seo.
     *
     * @return Seo
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * @param Collection <App\CoreBundle\Entity\FrontPageBlock> $blocks
     *
     * @return FrontPage
     */
    public function setBlocks($blocks)
    {
        $this->blocks = $blocks;

        return $this;
    }

    /**
     * @return Collection<App\CoreBundle\Entity\FrontPageBlock>
     */
    public function getBlocks()
    {
        return $this->blocks;
    }

    /**
     * @param FrontPageBlock $block
     *
     * @return FrontPage
     */
    public function addBlock($block)
    {
        if (null === $this->blocks) {
            $this->blocks = new ArrayCollection();
        }
        $this->blocks->add($block);

        return $this;
    }
}
