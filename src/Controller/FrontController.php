<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Page;
use App\Form\ContactType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home()
    {
        return $this->render('front/front.html.twig', [
            'NameSite' => 'Wellness',
        ]);
    }

    /**
     * @Route("/apropos", name="front.aboutus")
     */
    public function aboutus()
    {
        $page = $this->getDoctrine()->getRepository(Page::class)->findOneBy(['page' => 'aboutus']);
        return $this->render('front/page.html.twig', [
            'NameSite' => 'Wellness',
            'page' => $page
        ]);
    }

    /**
     * @Route("/contactus", name="front.contactus")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function contactus(Request $request)
    {
        $page = $this->getDoctrine()->getRepository(Page::class)->findOneBy(['page' => 'contactus']);
        $contactInit = new Contact();
        $form = $this->createForm(ContactType::class, $contactInit);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $contactInit->setSentAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($contactInit);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('success', 'Demande envoyée');
            return $this->redirectToRoute("front.contactus");
        }

        return $this->render('front/page.html.twig', [
            'NameSite' => 'Wellness',
            'page' => $page,
            'form' => $form->createView(),
        ]);
    }
}
