<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\Surfer;
use App\Form\NewsletterType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{
    /**
     * @Route("/admin/newsletters", name="admin.newsletters")
     */
    public function retrieve()
    {
        $newsletters = $this->getDoctrine()->getRepository(Newsletter::class)->findAll();
        return $this->render('admin/newsletters/index.html.twig', [
            'newsletters' => $newsletters,
            'controller' => 'newsletters'
        ]);
    }
    /**
     * @Route("/admin/newsletters/users", name="admin.newsletters.users")
     */
    public function users()
    {
        $surfers = $this->getDoctrine()->getRepository(Surfer::class)->findBy(['isSubToNewsletter' => 1]);
        return $this->render('admin/newsletters/users.html.twig', [
            'surfers' => $surfers,
            'controller' => 'newsletters'
        ]);
    }

    /**
     * @Route("/admin/newsletters/add", name="admin.newsletters.add")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request){
        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('success', 'Création réussie');
            return $this->redirectToRoute("admin.newsletters");
        } else {
            $this->addFlash('danger', 'Création non réussie');
        }

        return $this->render('admin/newsletters/form.html.twig', [
            'controller' => 'newsletters',
            'form' => $form->createView(),
            'action' => 'Ajouter'
        ]);
    }
    /**
     * @Route("/admin/surfer/newsletter/{id}", name="admin.surfer.newsletter", methods="POST")
     * @param Surfer $surfer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Surfer $surfer, Request $request)
    {
        if ($this->isCsrfTokenValid('newsletter' . $surfer->getId(), $request->get('_token'))) {
            $surfer->setIsSubToNewsletter(0);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Surfer désélectionné');
        } else {
            $this->addFlash('danger', 'Erreur token');
        }
        return $this->redirectToRoute('admin.newsletters.users');
    }
}
