<?php

namespace App\CoreBundle\Entity;

use App\CoreBundle\Model\Entity\RestaurantInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use JMS\Serializer\Annotation as JMS;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Translatable\Translatable;

/**
 * @JMS\ExclusionPolicy("all")
 *
 * @Gedmo\TranslationEntity(class="App\CoreBundle\Entity\RestaurantTranslation")
 *
 * @ORM\Entity(repositoryClass="App\CoreBundle\Repository\RestaurantRepository")
 * @ORM\Table(name="restaurant")
 */
class Restaurant implements RestaurantInterface, Translatable
{
    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("platsnr")
     *
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/\D/",
     *     match=false,
     *     message="ID should be a number"
     * )
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @JMS\Expose
     * @JMS\Type("string")
     * @JMS\SerializedName("namn")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("aktiv")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="smallint")
     */
    private $active;

    /**
     * @Assert\NotBlank()
     *
     * @Gedmo\Translatable
     *
     * @ORM\Column(type="string")
     */
    protected $title;

    /**
     * @Gedmo\Slug(fields={"title"})
     *
     * @ORM\Column(type="string", length=128, unique=true)
     */
    protected $slug;

    /**
     * @var RestaurantAddress
     *
     * @ORM\OneToOne(targetEntity="RestaurantAddress", mappedBy="restaurant", cascade={"persist", "remove"})
     *
     * @Assert\Type(type="App\CoreBundle\Entity\RestaurantAddress")
     * @Assert\Valid()
     */
    protected $address;

    /**
     * @Gedmo\Translatable
     */
    protected $description;

    /**
     * @var Seo
     *
     * @ORM\OneToOne(targetEntity="Seo", cascade={"persist", "remove"})
     *
     * @Assert\Type(type="App\CoreBundle\Entity\Seo")
     * @Assert\Valid()
     */
    private $seo;

    /**
     * @ORM\Column(type="smallint", nullable=true)
     */
    private $visible;

    /**
     * @ORM\OneToMany(targetEntity="RestaurantOpeningHour", mappedBy="restaurant", cascade={"persist", "remove"})
     */
    private $openHours;

    /**
     * @ORM\OneToMany(
     *   targetEntity="RestaurantTranslation",
     *   mappedBy="restaurant",
     *   cascade={"persist", "remove"}
     * )
     */
    protected $translations;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("time_takeaway")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="smallint", name="time_takeaway", options={"default":20})
     */
    private $timeTakeaway;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("time_delivery")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="smallint", name="time_delivery", options={"default":60})
     */
    private $timeDelivery;

    /**
     * @JMS\Expose
     * @JMS\Type("integer")
     * @JMS\SerializedName("warranty_voided")
     *
     * @Assert\NotBlank()
     *
     * @ORM\Column(type="smallint", name="warranty_voided", options={"default":0})
     */
    private $warrantyVoided;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->openHours = new ArrayCollection();
        $this->restaurantDeliveryRoutes = new ArrayCollection();
        $this->translations = new ArrayCollection();
    }

    /**
     * @return array
     */
    public function getData()
    {
        return array_merge(get_object_vars($this), [
            'status' => $this->getStatus(),
        ]);
    }

    /**
     * Set id.
     *
     * @param int $id
     *
     * @return Restaurant
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * @return Restaurant
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
     * Set active.
     *
     * @param int $active
     *
     * @return Restaurant
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active.
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return bool
     */
    public function isActiveAndVisible()
    {
        return $this->getActive() && $this->getVisible();
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Restaurant
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set slug.
     *
     * @param string $slug
     *
     * @return Restaurant
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
     * Set address.
     *
     * @param RestaurantAddress $address
     *
     * @return Restaurant
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address.
     *
     * @return RestaurantAddress
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Restaurant
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set features.
     *
     * @param string $features
     *
     * @return Restaurant
     */
    public function setFeatures($features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Get features.
     *
     * @return string
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set visible.
     *
     * @param int $visible
     *
     * @return Restaurant
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return int
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set openHours.
     *
     * @param Collection $openHours
     *
     * @return Restaurant
     */
    public function setOpenHours($openHours)
    {
        $this->openHours = $openHours;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getOpenHours()
    {
        return $this->openHours;
    }

    /**
     * Add openHours.
     *
     * @param RestaurantOpeningHour $openHours
     *
     * @return Restaurant
     */
    public function addOpenHour(RestaurantOpeningHour $openHours)
    {
        $this->openHours[] = $openHours;

        return $this;
    }

    /**
     * Remove openHours.
     *
     * @param RestaurantOpeningHour $openHours
     *
     * @return $this
     */
    public function removeOpenHour(RestaurantOpeningHour $openHours)
    {
        $this->openHours->removeElement($openHours);

        return $this;
    }

    /**
     * Add restaurantDeliveryRoutes.
     *
     * @param DeliveryRouteRestaurant $restaurant
     *
     * @return Restaurant
     */
    public function addRestaurantDeliveryRoute(DeliveryRouteRestaurant $restaurant)
    {
        $this->restaurantDeliveryRoutes[] = $restaurant;

        return $this;
    }

    /**
     * Remove restaurantDeliveryRoutes.
     *
     * @param DeliveryRouteRestaurant $restaurant
     */
    public function removeRestaurantDeliveryRoute(DeliveryRouteRestaurant $restaurant)
    {
        $this->restaurantDeliveryRoutes->removeElement($restaurant);
    }

    /**
     * Get restaurantDeliveryRoutes.
     *
     * @return Collection
     */
    public function getRestaurantDeliveryRoutes()
    {
        return $this->restaurantDeliveryRoutes;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        if (!$this->getActive()) {
            return 0;
        }

        if (!$this->getVisible()) {
            return 0;
        }

        return 1;
    }

    /**
     * @param mixed $seo
     *
     * @return $this
     */
    public function setSeo($seo)
    {
        $this->seo = $seo;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSeo()
    {
        return $this->seo;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->getId();
    }

    /**
     * @return ArrayCollection
     */
    public function getTranslations()
    {
        $translations = $this->translations;

        foreach ($translations as $translation) {
            $this->translations->set($translation->getLocale(), $translation);
        }

        return $this->translations;
    }

    /**
     * @param $translations
     * @return $this
     */
    public function setTranslations($translations)
    {
        $this->translations = $translations;

        foreach ($translations as $translation) {
            $translation->setRestaurant($this);
        }

        return $this;
    }

    /**
     * @param RestaurantTranslation $translation
     * @return $this
     */
    public function addTranslation($translation)
    {
        $this->getTranslations()->set($translation->getLocale(), $translation);

        return $this;
    }

    /**
     * @return string
     */
    public function getTranslationEntityClass()
    {
        return __CLASS__ . 'Translation';
    }

    /**
     * @return mixed
     */
    public function getCurrentTranslation()
    {
        return $this->getTranslations()->first();
    }

    /**
     * @param $local
     */
    public function setDescriptionTitle($local)
    {
        /**
         * @var RestaurantTranslation $translations
         */
        $translations = $this->getTranslations();

        if ($translations->get($local)) {
            $this->setDescription($translations->get($local)->getDescription());
            $this->setTitle($translations->get($local)->getTitle());
        }
    }

    /**
     * Set timeTakeaway.
     *
     * @param int $timeTakeaway
     *
     * @return Restaurant
     */
    public function setTimeTakeaway($timeTakeaway)
    {
        $this->timeTakeaway = $timeTakeaway < 20 ? 20 : $timeTakeaway;

        return $this;
    }

    /**
     * Get timeTakeaway.
     *
     * @return int
     */
    public function getTimeTakeaway()
    {
        return $this->timeTakeaway;
    }

    /**
     * Set timeDelivery.
     *
     * @param int $timeDelivery
     *
     * @return Restaurant
     */
    public function setTimeDelivery($timeDelivery)
    {
        $this->timeDelivery = $timeDelivery < 60 ? 60 : $timeDelivery;

        return $this;
    }

    /**
     * Get timeDelivery.
     *
     * @return int
     */
    public function getTimeDelivery()
    {
        return $this->timeDelivery;
    }

    /**
     * Set guarantyVoided.
     *
     * @param int $warrantyVoided
     *
     * @return Restaurant
     */
    public function setWarrantyVoided($warrantyVoided)
    {
        $this->warrantyVoided = $warrantyVoided;

        return $this;
    }

    /**
     * Get guarantyVoided.
     *
     * @return int
     */
    public function getWarrantyVoided()
    {
        return $this->warrantyVoided;
    }

    /**
     * @param $method
     * @param $args
     * @return mixed|string
     */
    public function __call($method, $args)
    {
        return ($translation = $this->getCurrentTranslation()) ?
            call_user_func([
                $translation,
                (preg_match('/^get/', $method)) ? ucfirst($method) : 'get' . ucfirst($method),
            ]) : '';
    }
}
