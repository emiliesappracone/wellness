<?php

namespace App\Controller;

use App\Entity\Page;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('front/aboutus.html.twig', [
            'NameSite' => 'Wellness',
            'page' => $page
        ]);
    }
}
