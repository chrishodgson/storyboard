<?php

namespace AppBundle\Controller;

use AppBundle\Entity\FavouriteSnippet;
use AppBundle\Entity\Snippet;
use AppBundle\Entity\Story;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Request;

class SnippetController extends Controller
{
    public function indexAction(Request $request)
    {
        $searchFormData = $request->get('appbundle_snippet_search');
        $status = $searchFormData['status'] ?? '';
        $language = $searchFormData['language'] ?? '';
        $searchText = $searchFormData['searchText'] ?? '';
        $searchTerms = explode(' ', trim($searchText));
        $showFavourites = $searchFormData['showFavourites'] ?? false;

        $em = $this->getDoctrine()->getManager();

        // get the language entity
        if($language){
            $language = $em->getRepository('AppBundle:Language')->find($language);
        }

        // get the status entity
        if($status){
            $status = $em->getRepository('AppBundle:SnippetStatus')->find($status);
        }

        //create the search form
        $searchForm = $this->createForm('AppBundle\Form\SnippetSearch');

        //set the search form values
        $searchForm->get('searchText')->setData($searchText);
        $searchForm->get('language')->setData($language);
        $searchForm->get('status')->setData($status);
        $searchForm->get('showFavourites')->setData((bool)$showFavourites);

        $repository = $em->getRepository('AppBundle:Snippet');

        //generate the initial query
        $query = $repository->createQueryBuilder('s')
            ->join('s.language', 'l')
            ->join('s.status', 'status')
            ->leftJoin('s.favourite', 'f');

        // filter by language
        if($language){
            $query->andWhere('l.id = :language')
                ->setParameter('language', $language->getId());
        }

        // filter by status
        if($status){
            $query->andWhere('status.id = :status')
                ->setParameter('status', $status->getId());
        }

        //filter on show favouritez
        if($showFavourites){
            $query->andWhere($query->expr()->isNotNull('f.id'));
        }

        // filter by search text
        if(count($searchTerms) > 0){
            foreach ($searchTerms as $key => $searchTerm) {
                $query->andWhere('s.title LIKE :searchTerm' . $key)
                    ->setParameter('searchTerm' . $key, '%' . $searchTerm . '%');
            }
        }

        // paginate the query and get the results
        $paginator  = $this->get('knp_paginator');

        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1) /*page number*/,
            10 /*limit per page*/
        );

        return $this->render('snippet/index.html.twig', [
            'search_form' => $searchForm->createView(),
            'pagination' => $pagination
        ]);
    }

    public function newAction(Request $request, Story $story)
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

    public function showAction(Snippet $snippet)
    {
        $deleteForm = $this->createDeleteForm($snippet);

        return $this->render('snippet/show.html.twig', array(
            'snippet' => $snippet,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function editAction(Request $request, Snippet $snippet)
    {
        $deleteForm = $this->createDeleteForm($snippet);
        $editForm = $this->createForm('AppBundle\Form\SnippetType', $snippet);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('snippet_show', array('id' => $snippet->getId()));
        }

        return $this->render('snippet/edit.html.twig', array(
            'snippet' => $snippet,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    public function deleteAction(Request $request, Snippet $snippet)
    {
        $form = $this->createDeleteForm($snippet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($snippet);
            $em->flush($snippet);
        }

        return $this->redirectToRoute('snippet_index');
    }

    public function updateFavouriteAction(Request $request, Snippet $snippet, int $option)
    {
        $em = $this->getDoctrine()->getManager();
        $favourite = $em->getRepository('AppBundle:FavouriteSnippet')->findOneBy([
            'snippet' => $snippet
        ]);

        if($option){
            if(!$favourite){
                $favourite = new FavouriteSnippet;
                $favourite->setSnippet($snippet);
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
            return $this->redirectToRoute('snippet_index');
        }
    }

    private function createDeleteForm(Snippet $snippet)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('snippet_delete', array('id' => $snippet->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
