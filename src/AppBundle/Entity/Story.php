<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Story
 *
 * @ORM\Table(name="story")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StoryRepository")
 */
class Story
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
     * Many Stories have One Project (owning side)
     * @ORM\ManyToOne(targetEntity="Project", inversedBy="stories")
     * @ORM\JoinColumn(name="project_id", referencedColumnName="id", onDelete="CASCADE")
     * @Assert\NotBlank()
     */
    private $project;

    /**
     * One Story has Many Snippets (non owning side).
     * @ORM\OneToMany(targetEntity="Snippet", mappedBy="story", cascade={"remove"})
     * @ORM\OrderBy({"position" = "DESC"})
     */
    private $snippets;

    /**
     * One Story has One Favourite.
     * @ORM\OneToOne(targetEntity="FavouriteStory", mappedBy="story")
     */
    private $favourite;

    /**
     * Story constructor.
     */
    public function __construct()
    {
        $this->snippets = new ArrayCollection;
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
     * @return Story
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
    public function getSnippets()
    {
        return $this->snippets;
    }

    /**
     * @param mixed $snippets
     */
    public function setSnippets($snippets)
    {
        $this->snippets = $snippets;
    }

    /**
     * @return mixed
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param mixed $project
     */
    public function setProject($project)
    {
        $this->project = $project;
    }

    /**
     * @return mixed
     */
    public function getFavourite()
    {
        return $this->favourite;
    }

    /**
     * @param mixed $favourite
     */
    public function setFavourite($favourite)
    {
        $this->favourite = $favourite;
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

