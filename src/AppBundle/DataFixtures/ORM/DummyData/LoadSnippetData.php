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
    const MAX_CODE_LINES = 20;
    const NUM_STORIES = 10;
    const NUM_SNIPPETS_PER_STORIES = 10;
    const SNIPPET_CODE = [
        'php'=>'print_r($data);',
        'javascript'=>'console.log(a);',
        'sql'=>'CREATE TABLE snippet ( id int(11) );',
        'css'=>'body { display:none }'
        //todo add more examples ?
    ];

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        //$em = $this->getDoctrine()->getManager();
        $manager->createQuery('DELETE FROM AppBundle:Story')->execute();

        $list = "'" . implode("','", array_keys(self::SNIPPET_CODE)) . "'";

        $dql = 'SELECT l FROM AppBundle:Language l where l.title in ('. $list .')';
        $languages = $manager->createQuery($dql)->getResult();
        //$languages = $manager->getRepository('AppBundle:Language')->findAll();

//        print_r($languages);

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