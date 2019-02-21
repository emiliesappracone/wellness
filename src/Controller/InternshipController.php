<?php

namespace App\Controller;

use App\Entity\Internship;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class InternshipController extends AbstractController
{
    /**
     * @Route("/internship/{slug}", name="internship.detail")
     */
    public function index($slug)
    {
        $internship = $this->getDoctrine()->getRepository(Internship::class)->findOneBy(['slug' => $slug]);
        return $this->render('front/internship/detail.html.twig', [
            'internship' => $internship
        ]);
    }
}
