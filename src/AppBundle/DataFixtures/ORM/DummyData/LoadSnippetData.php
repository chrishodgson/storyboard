<?php

// src/AppBundle/DataFixtures/ORM/DummyData/LoadSnippetData.php
namespace AppBundle\DataFixtures\ORM\DummyData;

use AppBundle\Entity\FavouriteSnippet;
use AppBundle\Entity\FavouriteStory;
use AppBundle\Entity\Project;
use AppBundle\Entity\Snippet;
use AppBundle\Entity\Story;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadSnippetData extends AbstractFixture implements OrderedFixtureInterface
{
    const MAX_CODE_LINES = 20;
    const NUM_STORIES = 10;
    const NUM_SNIPPETS_PER_STORIES = 10;
    const NUM_FAVOURITE_SNIPPETS = 20;
    const NUM_FAVOURITE_STORIES = 4;
    const NUM_PROJECTS = 4;
    const ACTIVE_SNIPPET_STATUS = 2;

    const SNIPPET_CODE = [
        'php'=>'print_r($data);',
        'javascript'=>'console.log(a);',
        'sql'=>'CREATE TABLE snippet ( id int(11) );',
        'css'=>'body { display:none }'
    ];

    const PROJECTS = [
        'Carphone',
        'Sky',
        'ADI',
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $snippetFavouriteCount = 0;
        $storyFavouriteCount = 0;

        $manager->createQuery('DELETE FROM AppBundle:Story')->execute();

        $list = "'" . implode("','", array_keys(self::SNIPPET_CODE)) . "'";

        $this->createProjects($manager);
        $projects = $manager->getRepository('AppBundle:Project')->findAll();

        $dql = 'SELECT l FROM AppBundle:Language l where l.title in ('. $list .')';
        $languages = $manager->createQuery($dql)->getResult();

        $statuses = $manager->getRepository('AppBundle:SnippetStatus')->findAll();

        /**
         * create stories
         */
        for($storyCount=1; $storyCount<=self::NUM_STORIES; $storyCount++){

            $faker = \Faker\Factory::create();
            $story = new Story;
            $story->setTitle('Story ' . $storyCount . ' ' . $faker->sentence(3));

            //project association
            $project = current($projects) ? current($projects) : reset($projects);
            $story->setProject($project);
            next($projects);

            $manager->persist($story);
            $manager->flush();

            /**
             * create favourite story
             */
            if ($storyFavouriteCount < self::NUM_FAVOURITE_STORIES) {
                $favourite = new FavouriteStory;
                $favourite->setStory($story);
                $manager->persist($favourite);
                $manager->flush();
                $storyFavouriteCount++;
            }

            /**
             * create snippets
             */
            for($snippetCount=1; $snippetCount<=self::NUM_SNIPPETS_PER_STORIES; $snippetCount++){
                $snippet = new Snippet;

                //language association
                $language = current($languages) ? current($languages) : reset($languages);
                $snippet->setLanguage($language);
                next($languages);

                $languageTitle = $snippet->getLanguage()->getTitle();
                if(true == array_key_exists($languageTitle, self::SNIPPET_CODE)){
                    $max = rand(1,self::MAX_CODE_LINES);
                    $code = '';
                    for($i=1; $i<=$max; $i++){
                        $code .= self::SNIPPET_CODE[$languageTitle] . PHP_EOL;
                    }
                    $snippet->setCode($code);
                }

                $snippet->setPosition($snippetCount);
                $snippet->setTitle(ucfirst($languageTitle) . ' Snippet' . $snippetCount . ' ' . $faker->sentence(3));
                $snippet->setDescription($faker->sentence(30));

                $snippet->setStory($story);

                //status association
                $status = current($statuses) ? current($statuses) : reset($statuses);
                $snippet->setStatus($status);
                next($statuses);

                $manager->persist($snippet);
                $manager->flush();

                /**
                 * create favourite snippet
                 */
                if (rand(0,4) == 0 && $snippetFavouriteCount < self::NUM_FAVOURITE_SNIPPETS) {
                    $favourite = new FavouriteSnippet;
                    $favourite->setSnippet($snippet);
                    $manager->persist($favourite);
                    $manager->flush();
                    $snippetFavouriteCount++;
                }
            }
        }
    }

    private function createProjects(ObjectManager $manager)
    {
        $manager->createQuery('DELETE FROM AppBundle:Project')->execute();

        foreach(self::PROJECTS as $title){
            $entity = new Project;
            $entity->setTitle($title);
            $manager->persist($entity);
        }

        $manager->flush();
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