<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Provider;
use App\Entity\Service;
use App\Entity\Surfer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function admin()
    {
        // GET ALL SERVICES
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();
        // RETURN SERVICES TO VIEW
        return $this->render('admin/admin.html.twig', [
            'services' => $services,
            'controller' => 'dashboard'
        ]);
    }

    /**
     * @Route("/admin/providers", name="admin.providers")
     */
    public function providers()
    {
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAll();
        // RETURN TO VIEW
        return $this->render('admin/providers/index.html.twig', ['providers' => $providers, 'controller' => 'providers']);
    }

    /**
     * @Route("/admin/surfers", name="admin.surfers")
     */
    public function surfers()
    {
        $surfers = $this->getDoctrine()->getRepository(Surfer::class)->findAll();
        // RETURN TO VIEW
        return $this->render('admin/surfers/index.html.twig', ['surfers' => $surfers, 'controller' => 'surfers']);
    }

    /**
     * @Route("/admin/comments", name="admin.comments")
     */
    public function comments()
    {
        $comments = $this->getDoctrine()->getRepository(Comment::class)->findAll();
        // RETURN TO VIEW
        return $this->render('admin/comments/index.html.twig', ['comments' => $comments, 'controller' => 'comments']);
    }

    /**
     * @Route("/admin/services", name="admin.services")
     */
    public function services()
    {
        // GET ALL SERVICES
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();
        // RETURN TO VIEW
        return $this->render('admin/services/index.html.twig', ['services' => $services, 'controller' => 'services']);
    }
//    /**
//     * @Route("/admin/newsletters", name="admin.newsletters")
//     */
//    public function newsletters()
//    {
//        // RETURN TO VIEW
//        return $this->render('admin/newsletters/index.html.twig', ['controller' => 'newsletters']);
//    }
}
