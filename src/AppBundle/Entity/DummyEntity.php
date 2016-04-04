<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use EP\DisplayBundle\Entity\DisplayTrait;
use EP\DoctrineLockBundle\Traits\LockableTrait;
use EP\DoctrineLockBundle\Annotations\Lockable;
use EP\DisplayBundle\Annotation as Display;

/**
 * ObjectLock
 * @Lockable
 * @Display\Display()
 */
class DummyEntity
{
    use LockableTrait;
    use DisplayTrait;

    /**
     * @var int
     */
    private $id;

    /**
     * @var  string
     */
    protected $title;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     * @Display\Image(path="/uploads/images/", height="60", width="60")
     */
    protected $avatar;

    /**
     * @var string
     * @Display\File(path="/uploads/files/")
     */
    protected $sampleFile;

    /**
     * @var ArrayCollection|DummyRelation[]
     */
    protected $relations;

    public function __construct()
    {
        $this->relations = new ArrayCollection();
    }

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
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param  DummyRelation $relation
     * @return $this
     */
    public function addRelation(DummyRelation $relation)
    {
        if (!$this->relations->contains($relation)) {
            $this->relations->add($relation);
        }
        return $this;
    }

    /**
     * @param DummyRelation $relation
     */
    public function removeRelation(DummyRelation $relation)
    {
        if ($this->relations->contains($relation)) {
            $this->relations->removeElement($relation);
        }
    }

    /**
     * @return ArrayCollection
     */
    public function getRelations()
    {
        return $this->relations;
    }

    /**
     * @return string
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * @param $avatar
     * @return $this
     */
    public function setAvatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return string
     */
    public function getSampleFile()
    {
        return $this->sampleFile;
    }

    /**
     * @param $sampleFile
     * @return $this
     */
    public function setSampleFile($sampleFile)
    {
        $this->sampleFile = $sampleFile;

        return $this;
    }

    public function __toString()
    {
        return $this->getTitle();
    }
}
