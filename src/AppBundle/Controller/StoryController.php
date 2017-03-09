<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Snippet;
use AppBundle\Entity\Story;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Story controller.
 *
 * @Route("story")
 */
class StoryController extends Controller
{
    /**
     * Lists all story entities.
     *
     * @Route("/", name="story_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();

        $searchFormData = $request->get('appbundle_story_search');
        $searchText = $searchFormData['searchText'] ?? '';
        $searchTerms = explode(' ', trim($searchText));

        //create the search form
        $searchForm = $this->createForm('AppBundle\Form\StorySearch');

        //set the search form values
        $searchForm->get('searchText')->setData($searchText);

        $repository = $em->getRepository('AppBundle:Story');

        //generate the initial query
        $query = $repository->createQueryBuilder('s');

        // filter by search text
        if(count($searchTerms) > 0){
            $logger->info('You searched for the following terms', array(
                'terms' => $searchTerms,
            ));

            foreach ($searchTerms as $key => $searchTerm) {
                $query->andWhere('s.title LIKE :searchTerm' . $key)
                    ->setParameter('searchTerm' . $key, '%' . $searchTerm . '%');
            }
        }

        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1) /*page number*/,
            10 /*limit per page*/
        );

        return $this->render('story/index.html.twig', [
            'search_form' => $searchForm->createView(),
            'pagination' => $pagination
        ]);
    }

    /**
     * @todo move to the snippet controller when we do yaml routes
     * Creates a new snippet entity for a story.
     *
     * @Route("/{id}/snippet/new", name="snippet_new")
     * @Method({"GET", "POST"})
     */
    public function newSnippetAction(Request $request, Story $story)
    {
        $snippet = new Snippet();
        $snippet->setStory($story);
        $form = $this->createForm('AppBundle\Form\SnippetType', $snippet);

        //add an extra field
        $form->add('another', CheckboxType::class, [
            'label' => 'Add another?',
            'mapped' => false,
            'required' => false,
            'attr' => ['checked' => true]
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($snippet);
            $em->flush($snippet);

            if(true == $form->get('another')->getData()){
                return $this->redirectToRoute('snippet_new', array('id' => $story->getId()));
            } else {
                return $this->redirectToRoute('snippet_show', array('id' => $snippet->getId()));
            }
        }

        return $this->render('snippet/new.html.twig', array(
            'story' => $story,
            'snippet' => $snippet,
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new story entity.
     *
     * @Route("/new", name="story_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $story = new Story();
        $form = $this->createForm('AppBundle\Form\StoryType', $story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($story);
            $em->flush($story);

            //return $this->redirectToRoute('story_show', array('id' => $story->getId()));
            return $this->redirectToRoute('snippet_new', array('id' => $story->getId()));
        }

        return $this->render('story/new.html.twig', array(
            'story' => $story,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a story entity.
     *
     * @Route("/{id}", name="story_show")
     * @Method("GET")
     */
    public function showAction(Request $request, Story $story)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('AppBundle:Snippet');

        $query = $repository->createQueryBuilder('s')
            ->where('s.story = :story')
            ->setParameter('story', $story->getId());

        // paginate the query and get the results
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1) /*page number*/,
            10 /*limit per page*/
        );

        $deleteForm = $this->createDeleteForm($story);

        return $this->render('story/show.html.twig', array(
            'pagination' => $pagination,
            'story' => $story,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing story entity.
     *
     * @Route("/{id}/edit", name="story_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Story $story)
    {
        $deleteForm = $this->createDeleteForm($story);
        $editForm = $this->createForm('AppBundle\Form\StoryType', $story);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('story_show', array('id' => $story->getId()));
        }

        return $this->render('story/edit.html.twig', array(
            'story' => $story,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a story entity.
     *
     * @Route("/{id}", name="story_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Story $story)
    {
        $form = $this->createDeleteForm($story);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($story);
            $em->flush($story);
        }

        return $this->redirectToRoute('story_index');
    }

    /**
     * Creates a form to delete a story entity.
     *
     * @param Story $story The story entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Story $story)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('story_delete', array('id' => $story->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
