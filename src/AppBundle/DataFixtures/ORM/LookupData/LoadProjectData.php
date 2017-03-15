<?php

// src/AppBundle/DataFixtures/ORM/LookupData/LoadProjectData.php
namespace AppBundle\DataFixtures\ORM\LookupData;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Project;

class LoadProjectData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * array
     */
    const PROJECTS = [
        'freelance', 'sky', 'adi',
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach(self::PROJECTS as $title){

            $Project = $this->findOrCreateProject($title, $manager);

            /** Check if the object is managed (so already exists in the database) **/
            if (false == $manager->contains($Project)) {
                $manager->persist($Project);
            }

//            $entity = new Project;
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
     * @return Project
     */
    protected function findOrCreateProject($title, ObjectManager $manager)
    {
        return $manager->getRepository('AppBundle:Project')
            ->findOneBy(['title' => $title]) ?: new Project($title);
//            ->findOneBy(['title' => $title]) ?: (new Project())->setTitle($title);
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