<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FavouriteStory
 *
 * @ORM\Table(name="favourite_story")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FavouriteStoryRepository")
 */
class FavouriteStory
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
     * One FavouriteStory has One Story (owning side).
     * @ORM\OneToOne(targetEntity="Story", inversedBy="favourite")
     * @ORM\JoinColumn(name="story_id", referencedColumnName="id")
     */
    private $story;

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
     * @return mixed
     */
    public function getStory()
    {
        return $this->story;
    }

    /**
     * @param mixed $story
     */
    public function setStory($story)
    {
        $this->story = $story;
    }
}

