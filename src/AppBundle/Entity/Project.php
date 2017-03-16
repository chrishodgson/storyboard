<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Project
 *
 * @ORM\Table(name="project")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProjectRepository")
 */
class Project
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $title;

    /**
     * One Project has Many Stories (non owning side)
     * @ORM\OneToMany(targetEntity="Story", mappedBy="project", cascade={"remove"})
     */
    private $stories;

    /**
     * Project constructor.
     * @param null $title
     */
    public function __construct($title=null)
    {
        $this->setTitle($title);
        $this->stories = new ArrayCollection;
        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Project
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getStories()
    {
        return $this->stories;
    }

    /**
     * @param mixed $stories
     */
    public function setStories($stories)
    {
        $this->stories = $stories;
    }

    /**
     * Override toString() method to return the title
     * @return string name
     */
    public function __toString()
    {
        return $this->title;
    }
}

