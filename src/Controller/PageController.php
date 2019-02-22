<?php

namespace App\Controller;

use App\Entity\Page;
use App\Form\PageType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/admin/pages", name="admin.pages")
     */
    public function list()
    {
        $pages = $this->getDoctrine()->getRepository(Page::class)->findAll();
        return $this->render('admin/page/index.html.twig', [
            'controller' => 'pages',
            'pages' => $pages
        ]);
    }

    /**
     * @Route("/admin/page/edit/{id}", name="admin.page.update")
     * @param Page $page
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Page $page, Request $request)
    {
        $form = $this->createForm(PageType::class, $page);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->persist($page);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('success', 'Page modifiée');
            return $this->redirectToRoute("admin.pages");
        }
        return $this->render('admin/page/form.html.twig', [
            'controller' => 'pages',
            'page' => $page,
            'action' => 'Editer',
            'form' => $form->createView(),
        ]);
    }

}
