<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Snippet;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Snippet controller.
 *
 * @Route("snippet")
 */
class SnippetController extends Controller
{
    /**
     * Snippet entity search.
     *
     * @Route("/", name="snippet_index")
     * @Method({"GET", "POST"})
     */
    public function indexAction(Request $request)
    {
        $searchFormData = $request->get('appbundle_snippet_search');
        $language = $searchFormData['language'] ?? '';
        $searchText = $searchFormData['searchText'] ?? '';
        $searchTerms = explode(' ', trim($searchText));

        $em = $this->getDoctrine()->getManager();

        // get the language entity
        if($language){
            $language = $em->getRepository('AppBundle:Language')->find($language);
        }

        //create the search form
        $searchForm = $this->createForm('AppBundle\Form\SnippetSearch');

        //set the search form values
        $searchForm->get('searchText')->setData($searchText);
        $searchForm->get('language')->setData($language);

        $repository = $em->getRepository('AppBundle:Snippet');

        //generate the initial query
        $query = $repository->createQueryBuilder('s')
            ->join('s.language', 'l');

        // filter by language
        if($language){
            $query->where('l.id = :language')
                  ->setParameter('language', $language->getId());
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

    /**
     * Finds and displays a snippet entity.
     *
     * @Route("/{id}", name="snippet_show")
     * @Method("GET")
     */
    public function showAction(Snippet $snippet)
    {
        $deleteForm = $this->createDeleteForm($snippet);

        return $this->render('snippet/show.html.twig', array(
            'snippet' => $snippet,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing snippet entity.
     *
     * @Route("/{id}/edit", name="snippet_edit")
     * @Method({"GET", "POST"})
     */
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

    /**
     * Deletes a snippet entity.
     *
     * @Route("/{id}", name="snippet_delete")
     * @Method("DELETE")
     */
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

    /**
     * Creates a form to delete a snippet entity.
     *
     * @param Snippet $snippet The snippet entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Snippet $snippet)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('snippet_delete', array('id' => $snippet->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
