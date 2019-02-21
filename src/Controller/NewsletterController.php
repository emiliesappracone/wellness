<?php

namespace App\Controller;

use App\Entity\Newsletter;
use App\Entity\Surfer;
use App\Form\NewsletterType;
use App\Services\MailsSender;
use App\Services\NewsletterSender;
use App\Services\PictureLinker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NewsletterController extends AbstractController
{

    /**
     * @var PictureLinker $pictureLinker
     */
    public $pictureLinker;

    public $mailer;

    public function __construct(MailsSender $mailer)
    {
        $this->pictureLinker = new PictureLinker();
        $this->mailer = $mailer;
    }

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

            $names = $this->pictureLinker->getUploadedFile($request, 'newsletter', $newsletter);
            $newsletter->setPath($names[0]);
            $newsletter->setName($names[1]);
            // persist in db
            $this->getDoctrine()->getManager()->persist($newsletter);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('success', 'Création réussie');
            return $this->redirectToRoute("admin.newsletters");
        } else {
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('danger', 'Création non réussie');
        }

        return $this->render('admin/newsletters/form.html.twig', [
            'controller' => 'newsletters',
            'form' => $form->createView(),
            'action' => 'Ajouter'
        ]);
    }

    /**
     * @Route("/admin/surfer/newsletter/{id}", name="admin.newsletter.delete", methods="POST")
     * @param Newsletter $newsletter
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function remove(Newsletter $newsletter, Request $request){
        if ($this->isCsrfTokenValid('newsletter' . $newsletter->getId(), $request->get('_token'))) {
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('success', 'Newsletter désélectionné');
        } else {
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('danger', 'Erreur token');
        }
        return $this->redirectToRoute('admin.newsletters');
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
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('success', 'Surfer désélectionné');
        } else {
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('danger', 'Erreur token');
        }
        return $this->redirectToRoute('admin.newsletters.users');
    }

    /**
     * @Route("/admin/send/newsletter/{id}", name="admin.newsletter.send")
     * @param Newsletter $newsletter
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendNewsletter(Newsletter $newsletter){
        $surfers = $this->getDoctrine()->getRepository(Surfer::class)->findBy(['isSubToNewsletter' => true]);
        foreach ($surfers as $surfer){
            $this->mailer->sendMail(['newsletter' => $newsletter], 'Newsletter', $surfer->getBasicEmail(), 'newsletter/newsletter');
        }
        return $this->redirectToRoute('admin.newsletters');
    }
}
