<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Status
 *
 * @ORM\Table(name="Status")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\StatusRepository")
 */
class Status
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
     * One Status has Many Snippets (non owning side)
     * @ORM\OneToMany(targetEntity="Snippet", mappedBy="Status", cascade={"remove"})
     */
    private $snippets;

    /**
     * Status constructor.
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
     * @return Status
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
     * Override toString() method to return the title
     * @return string name
     */
    public function __toString()
    {
        return $this->title;
    }
}

