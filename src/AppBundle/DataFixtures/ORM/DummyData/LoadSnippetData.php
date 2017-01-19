<?php

// src/AppBundle/DataFixtures/ORM/DummyData/LoadSnippetData.php
namespace AppBundle\DataFixtures\ORM\DummyData;

use AppBundle\Entity\Snippet;
use AppBundle\Entity\Story;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSnippetData extends AbstractFixture implements OrderedFixtureInterface
{
    const NUM_STORIES = 10;
    const NUM_SNIPPETS_PER_STORIES = 10;
    const SNIPPET_CODE = [
        'php'=>'print_r($data);',
        'javascript'=>'console.log(a);',
        'sql'=>'CREATE TABLE snippet ( id int(11) )',
        'css'=>'body { display:none }'
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //$em = $this->getDoctrine()->getManager();
        $manager->createQuery('DELETE FROM AppBundle:Story')->execute();

        $languages = $manager->getRepository('AppBundle:Language')->findAll();

        for($storyCount=1; $storyCount<=self::NUM_STORIES; $storyCount++){
            $faker = \Faker\Factory::create();
            $story = new Story;
            $story->setTitle('Story ' . $storyCount . ' ' . $faker->sentence(3));
            $manager->persist($story);
            $manager->flush();

            for($snippetCount=1; $snippetCount<=self::NUM_SNIPPETS_PER_STORIES; $snippetCount++){
                $snippet = new Snippet;

                //language association
                $language = current($languages) ? current($languages) : reset($languages);
                //$language->getSnippets()->add($snippet);
                $snippet->setLanguage($language);
                next($languages);

                $languageTitle = $snippet->getLanguage()->getTitle();
                if(true == array_key_exists($languageTitle, self::SNIPPET_CODE)){
                    $snippet->setCode(self::SNIPPET_CODE[$languageTitle]);
                }

                $snippet->setPosition($snippetCount);
                $snippet->setTitle(ucfirst($languageTitle) . ' Snippet' . $snippetCount . ' ' . $faker->sentence(3));
                $snippet->setDescription($faker->sentence(30));

                //story association
                //$story->getSnippets()->add($snippet);
                $snippet->setStory($story);

                $manager->persist($snippet);
                $manager->flush();
            }
        }
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