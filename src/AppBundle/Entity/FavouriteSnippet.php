<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * FavouriteSnippet
 *
 * @ORM\Table(name="favourite_snippet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FavouriteSnippetRepository")
 */
class FavouriteSnippet
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
     * One FavouriteSnippet has One Snippet (owning side).
     * @ORM\OneToOne(targetEntity="Snippet", inversedBy="favourite")
     * @ORM\JoinColumn(name="snippet_id", referencedColumnName="id")
     */
    private $snippet;

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
    public function getSnippet()
    {
        return $this->snippet;
    }

    /**
     * @param mixed $snippet
     */
    public function setSnippet($snippet)
    {
        $this->snippet = $snippet;
    }
}

