<?php

namespace AppBundle\Entity;

/**
 * Class DummyRelation
 * @package AppBundle\Entity
 */
class DummyRelation
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var  string
     */
    protected $summary;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var DummyEntity
     */
    protected $dummy;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSummary()
    {
        return $this->summary;
    }

    /**
     * @param $summary
     * @return $this
     */
    public function setSummary($summary)
    {
        $this->summary = $summary;

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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return DummyEntity
     */
    public function getDummy()
    {
        return $this->dummy;
    }

    /**
     * @param $dummy
     * @return $this
     */
    public function setDummy($dummy)
    {
        $this->dummy = $dummy;

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
