<?php

// src/AppBundle/DataFixtures/ORM/LookupData/LoadLanguageData.php
namespace AppBundle\DataFixtures\ORM\LookupData;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\Language;

class LoadLanguageData extends AbstractFixture implements OrderedFixtureInterface
{
    /**
     * array
     */
    const LANGUAGES = [
        'php', 'javascript', 'sql', 'css', 'bash', 'docker', 'git', 'http', 'yaml', 'json', 'ini', 'apacheconf', 'twig'
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach(self::LANGUAGES as $title){

            $language = $this->findOrCreateLanguage($title, $manager);

            /** Check if the object is managed (so already exists in the database) **/
            if (false == $manager->contains($language)) {
                $manager->persist($language);
            }

//            $entity = new Language;
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
     * @return Language
     */
    protected function findOrCreateLanguage($title, ObjectManager $manager)
    {
        return $manager->getRepository('AppBundle:Language')
            ->findOneBy(['title' => $title]) ?: new Language($title);
//            ->findOneBy(['title' => $title]) ?: (new Language())->setTitle($title);
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