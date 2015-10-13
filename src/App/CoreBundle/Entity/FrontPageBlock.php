<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 24 08 2015
 */
namespace App\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\FrontPageBlockRepository")
 * @ORM\Table(name="front_page_blocks")
 */
class FrontPageBlock
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
     * @JMS\Type("App\CoreBundle\Entity\FrontPage")
     * @JMS\SerializedName("front_page")
     *
     * @ORM\ManyToOne(targetEntity="FrontPage", inversedBy="front_page")
     * @ORM\JoinColumn(name="front_page_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $frontPage;

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
     * @JMS\Type("string")
     * @JMS\SerializedName("main_image")
     *
     *
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist"})
     * @ORM\JoinColumn(name="main_image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $mainImage;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("secondary_image")
     *
     * @ORM\ManyToOne(targetEntity="Image", cascade={"persist"})
     * @ORM\JoinColumn(name="secondary_image_id", referencedColumnName="id", nullable=true, onDelete="SET NULL")
     */
    protected $secondaryImage;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("alternative_text")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", name="alternative_text")
     */
    protected $alternativeText;
    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("priority")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="smallint")
     */
    protected $priority;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("style")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string", length=16)
     */
    protected $style;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("name")
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $url;


    public function __clone()
    {
        if ($this->id) {
            $this->setId(null);
            $this->setFrontPage(null);
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
     * @return FrontPageBlock
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
     * @return FrontPageBlock
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
     * @return FrontPageBlock
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
     * Set mainImage.
     *
     * @param Image|null $mainImage
     *
     * @return FrontPageBlock
     */
    public function setMainImage(Image $mainImage = null)
    {
        $this->mainImage = $mainImage;

        return $this;
    }

    /**
     * Get mainImage.
     *
     * @return string
     */
    public function getMainImage()
    {
        return $this->mainImage;
    }


    /**
     * Set secondary image.
     *
     * @param Image|null $secondaryImage
     *
     * @return FrontPageBlock
     */
    public function setSecondaryImage(Image $secondaryImage = null)
    {
        $this->secondaryImage = $secondaryImage;

        return $this;
    }

    /**
     * Get secondary image.
     *
     * @return Image
     */
    public function getSecondaryImage()
    {
        return $this->secondaryImage;
    }

    /**
     * Set alternativeText.
     *
     * @param string $alternativeText
     *
     * @return FrontPageBlock
     */
    public function setAlternativeText($alternativeText)
    {
        $this->alternativeText = $alternativeText;

        return $this;
    }

    /**
     * Get alternativeText.
     *
     * @return string
     */
    public function getAlternativeText()
    {
        return $this->alternativeText;
    }

    /**
     * Set priority.
     *
     * @param string $priority
     *
     * @return FrontPageBlock
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set style.
     *
     * @param string $style
     *
     * @return FrontPageBlock
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style.
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return FrontPageBlock
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set frontPage.
     *
     * @param string $frontPage
     *
     * @return FrontPageBlock
     */
    public function setFrontPage($frontPage)
    {
        $this->frontPage = $frontPage;

        return $this;
    }

    /**
     * Get frontPage.
     *
     * @return FrontPage
     */
    public function getFrontPage()
    {
        return $this->frontPage;
    }
}
