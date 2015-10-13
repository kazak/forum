<?php

/**
 * @author:     aat <aat@norse.digital>
 *
 * @copyright   Copyright (C) 2015 NXC AS.
 * @date: 06 07 2015
 */
namespace App\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\MenuRepository")
 * @ORM\Table(name="menu")
 * @ORM\HasLifecycleCallbacks()
 */
class Menu
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
     * @JMS\SerializedName("priority")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="smallint")
     */
    protected $priority;

    /**
     * @ORM\OneToOne(targetEntity="Seo", cascade={"persist", "remove"})
     */
    protected $seo;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("hide_for_search_engines")
     *
     * @ORM\Column(type="boolean", name="hide_for_search_engines")
     */
    protected $hideForSearchEngines;

    /**
     * @ORM\Column(type="boolean", options={"default":1})
     */
    protected $siteMap;

    /**
     * add Collection
     */
    public function __construct()
    {
        $this->menuProducts = new ArrayCollection();
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
     * Set name.
     *
     * @param string $name
     *
     * @return Menu
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
     * @return Menu
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
     * Set status.
     *
     * @param string $status
     *
     * @return Menu
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
     * Set priority.
     *
     * @param string $priority
     *
     * @return Menu
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
     * Set seo.
     *
     * @param Seo $seo
     *
     * @return $this
     */
    public function setSeo(Seo $seo)
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
     * Set hideForSearchEngines.
     *
     * @param string $hideForSearchEngines
     *
     * @return Menu
     */
    public function setHideForSearchEngines($hideForSearchEngines)
    {
        $this->hideForSearchEngines = $hideForSearchEngines;

        return $this;
    }

    /**
     * Get hideForSearchEngines.
     *
     * @return string
     */
    public function getHideForSearchEngines()
    {
        return $this->hideForSearchEngines;
    }

    /**
     * Add Product.
     *
     * @param Product $product
     * @param int $maxPriority
     *
     * @return Menu
     */
    public function addProduct(Product $product, $maxPriority)
    {
        if (null === $this->menuProducts) {
            $this->menuProducts = new ArrayCollection();
        }
        $menuProduct = new MenuProducts();
        $menuProduct->setProduct($product);
        $menuProduct->setPriority($maxPriority);

        $this->addMenuProduct($menuProduct);

        return $this;
    }

    /**
     * Remove Product.
     *
     * @param Product $product
     * @return $this
     */
    public function removeProduct(Product $product)
    {
        $menuProducts = $this->menuProducts->get($product->getId());
        if ($menuProducts !== null) {
            return $this->removeMenuProduct($menuProducts);
        }
        return $this;
    }

    /**
     * Get Products.
     *
     * @return Collection<App\CoreBundle\Entity\Product>
     */
    public function getProducts()
    {
        return array_map(
            function ($menuProducts) {
                return $menuProducts->getProduct();
            },
            $this->menuProducts->toArray()
        );
    }

    /**
     * @param MenuProducts $menuProduct
     * @return $this
     */
    public function addMenuProduct(MenuProducts $menuProduct)
    {
        if (null === $this->menuProducts) {
            $this->menuProducts = new ArrayCollection();
        }
        if (!$this->menuProducts->contains($menuProduct)) {
            $this->menuProducts->add($menuProduct);
            $menuProduct->setMenu($this);
        }

        return $this;
    }

    /**
     * @return ArrayCollection
     */
    public function getMenuProducts()
    {
        return $this->menuProducts;
    }

    /**
     * @param MenuProducts $menuProduct
     * @return $this
     */
    public function removeMenuProduct(MenuProducts $menuProduct)
    {
        if ($this->menuProducts->contains($menuProduct)) {
            $this->menuProducts->removeElement($menuProduct);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiteMap()
    {
        return $this->siteMap;
    }

    /**
     * @param mixed $siteMap
     * @return $this
     */
    public function setSiteMap($siteMap)
    {
        $this->siteMap = $siteMap;

        return $this;
    }
}
