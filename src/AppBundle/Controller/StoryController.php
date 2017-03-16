<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FavouriteStory;
use AppBundle\Entity\Story;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class StoryController extends Controller
{
    public function indexAction(Request $request)
    {
        $logger = $this->get('logger');
        $em = $this->getDoctrine()->getManager();

        $searchFormData = $request->get('appbundle_story_search');
        $searchText = $searchFormData['searchText'] ?? '';
        $searchTerms = explode(' ', trim($searchText));
        $project = $searchFormData['project'] ?? '';

        //create the search form
        $searchForm = $this->createForm('AppBundle\Form\StorySearch');

        // get the project entity
        if($project){
            $project = $em->getRepository('AppBundle:Project')->find($project);
        }

        //set the search form values
        $searchForm->get('searchText')->setData($searchText);
        $searchForm->get('project')->setData($project);

        $repository = $em->getRepository('AppBundle:Story');

        //generate the initial query
        $query = $repository->createQueryBuilder('s')
            ->join('s.project', 'p')
            ->leftJoin('s.favourite', 'f');

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

        if($project){
            $query->andWhere('p.id = :project')
                ->setParameter('project', $project->getId());
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

    public function updateFavouriteAction(Request $request, Story $story, int $option)
    {
        $em = $this->getDoctrine()->getManager();
        $favourite = $em->getRepository('AppBundle:FavouriteStory')->findOneBy([
            'story' => $story
        ]);

        if($option){
            if(!$favourite){
                $favourite = new FavouriteStory;
                $favourite->setStory($story);
                $em->persist($favourite);
                $em->flush();
            }
        } else {
            if($favourite){
                $em->remove($favourite);
                $em->flush();
            }
        }

        $redirectUrl = $request->query->get('redirect_to');

        if($redirectUrl){
            return $this->redirect($redirectUrl);
        } else {
            return $this->redirectToRoute('story_index');
        }
    }

    private function createDeleteForm(Story $story)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('story_delete', array('id' => $story->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }
}
