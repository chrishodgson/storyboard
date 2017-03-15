<?php

// src/AppBundle/DataFixtures/ORM/LookupData/LoadSnippetStatusData.php
namespace AppBundle\DataFixtures\ORM\LookupData;

use AppBundle\Entity\SnippetStatus;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSnippetStatusData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * array
     */
    const STATUSES = [
        'Draft', 'Active', 'Archived'
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach(self::STATUSES as $title){

            $status = $this->findOrCreatestatus($title, $manager);

            /** Check if the object is managed (so already exists in the database) **/
            if (false == $manager->contains($status)) {
                $manager->persist($status);
            }

//            $entity = new status;
//            $entity->setTitle($title);
//            $manager->persist($entity);
        }

        $manager->flush();
    }

    /**
     * Helper method to return an already existing Locator from the database, else create and return a new one
     *
     * @param string        $title
     * @param ObjectManager $manager
     *
     * @return status
     */
    protected function findOrCreateStatus($title, ObjectManager $manager)
    {
        return $manager->getRepository('AppBundle:SnippetStatus')
            ->findOneBy(['title' => $title]) ?: new SnippetStatus($title);
//            ->findOneBy(['title' => $title]) ?: (new status())->setTitle($title);
    }

    /**
     * @return int
     */
    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 1;
    }
}