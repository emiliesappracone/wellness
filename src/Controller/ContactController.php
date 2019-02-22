<?php

namespace App\Controller;

use App\Entity\Contact;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{
    /**
     * @Route("/admin/contacts", name="admin.contacts")
     */
    public function list()
    {
        $contact = $this->getDoctrine()->getRepository(Contact::class)->findAll();
        return $this->render('admin/contact/index.html.twig', [
            'controller' => 'contacts',
            'contacts' => $contact
        ]);
    }

}
