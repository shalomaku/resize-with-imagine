<?php

namespace AppBundle\Entity;

class Room
{
    /** @var  int */
    private $id;

    /** @var  string */
    private $name;

    /** @var  string */
    private $imageUrl;

    /** @var  float */
    private $scale;

    /** @var  array|Furniture[] */
    private $furnitures;

    /**
     * Room constructor.
     */
    public function __construct()
    {
        $this->furnitures = [];
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Room
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Room
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getImageUrl()
    {
        return $this->imageUrl;
    }

    /**
     * @param string $imageUrl
     *
     * @return Room
     */
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    /**
     * @return float
     */
    public function getScale()
    {
        return $this->scale;
    }

    /**
     * @param float $scale
     *
     * @return Room
     */
    public function setScale($scale)
    {
        $this->scale = $scale;

        return $this;
    }

    /**
     * @param Furniture $furniture
     *
     * @return Room
     */
    public function addFurniture(Furniture $furniture)
    {
        $this->furnitures[] = $furniture;
        $furniture->setRoom($this);

        return $this;
    }

    /**
     * @param Furniture $furniture
     *
     * @return Room
     */
    public function removeFurniture(Furniture $furniture)
    {
        if (($key = array_search($furniture, $this->furnitures)) !== false) {
            unset($this->furnitures[$key]);
        }

        return $this;
    }

    /**
     * @param array|Furniture[] $furnitures
     *
     * @return Room
     */
    public function setFurnitures(array $furnitures)
    {
        $this->furnitures = $furnitures;

        return $this;
    }

    /**
     * @return Furniture[]|array
     */
    public function getFurnitures()
    {
        return $this->furnitures;
    }
}
