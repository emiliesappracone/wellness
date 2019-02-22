<?php

namespace App\Controller;

use App\Entity\Surfer;
use App\Form\SurferType;
use App\Services\MailsSender;
use App\Services\UsersMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SurferController extends AbstractController
{
    /**
     * @var UsersMaker
     */
    private $um;

    /**
     * @var MailsSender
     */
    private $mailer;

    /**
     * UsersController constructor.
     * @param UsersMaker $usersMaker
     * @param MailsSender $mailer
     */
    public function __construct(UsersMaker $usersMaker, MailsSender $mailer)
    {
        $this->um = $usersMaker;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/admin/surfer/add", name="admin.surfer.add")
     */
    public function add(Request $request)
    {
        $surfer = new Surfer();
        $form = $this->createForm(SurferType::class, $surfer);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->um->checkMail($surfer->getBasicEmail())) {
                // add token here
                $surfer->setToken(md5(uniqid() . rand(1, 1000)));
                $surfer->setRoles(['ROLE_USER']);
                // don't forget to set new update date
                $surfer->setUpdatedAt(new \DateTime());
                $surfer->setUsername($surfer->getBasicEmail());
                // persist in db
                $this->getDoctrine()->getManager()->persist($surfer);
                $this->getDoctrine()->getManager()->flush();
                // redirect to route with message
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('success', 'Création réussie');
                return $this->redirectToRoute("admin.surfers");
            } else {
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('danger', 'Email déjà utilisée');
            }
        }
        return $this->render('/admin/surfers/form.html.twig', [
            'form' => $form->createView(),
            'controller' => 'surfers',
            'action' => 'Créer'
        ]);
    }

    /**
     * @Route("/admin/surfers/update/{id}", name="admin.surfer.update")
     */
    public function update(Request $request, $id)
    {
        // call what service need to be updated
        $surfer = $this->getDoctrine()->getRepository(Surfer::class)->find($id);
        // call form entity class form
        $form = $this->createForm(SurferType::class, $surfer);
        $isChanged = false;
        if ($request->request->get('surfer')['basicEmail'] != $surfer->getBasicEmail()) {
            $isChanged = $this->um->checkMail($request->request->get('surfer')['basicEmail']);
        }
        // pass request to form
        $form->handleRequest($request);
        // check if $form is submitted
        if ($form->isSubmitted() && $form->isValid()) {

            if (!$isChanged) {
                // don't forget to set new update date
                $surfer->setUpdatedAt(new \DateTime());
                $surfer->setUsername($surfer->getBasicEmail());
                $this->getDoctrine()->getManager()->persist($surfer);
                $this->getDoctrine()->getManager()->flush();
                // redirect to route with message
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('success', 'Edition réussie');
                return $this->redirectToRoute("admin.surfer.update", ["id" => $id]);
            } else {
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('danger', 'Email déjà utilisée');
            }
        }
        return $this->render('admin/surfers/form.html.twig', [
            'form' => $form->createView(),
            'controller' => 'surfers',
            'action' => 'Editer'
        ]);
    }

    /**
     * @Route("/admin/surfer/delete/{id}", name="admin.surfer.delete", methods="DELETE")
     * @param Surfers $surfer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Surfer $surfer, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $surfer->getId(), $request->get('_token'))) {
            $this->getDoctrine()->getManager()->remove($surfer);
            $this->getDoctrine()->getManager()->flush();
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('success', 'Surfer supprimé');
        } else {
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('danger', 'Surfer non supprimé');
        }
        return $this->redirectToRoute('admin.surfers');
    }

    /**
     * @Route("/admin/banned/surfer/{id}", name="admin.surfer.banned")
     * @param Surfer $surfer
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function banned(Surfer $surfer, Request $request)
    {
        if ($this->isCsrfTokenValid('banned' . $surfer->getId(), $request->get('_token'))) {
            if ($surfer->getIsBanned() == true) {
                $surfer->setIsBanned(false);
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('success', 'Surfer débanni');
                $this->mailer->sendMail([], 'Account unbanned', $surfer->getBasicEmail(), 'ban/unban');
            } else {
                $surfer->setIsBanned(true);
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('success', 'Surfer banni');
                $this->mailer->sendMail([], 'Account banned', $surfer->getBasicEmail(), 'ban/admin');
            }
            $this->getDoctrine()->getManager()->persist($surfer);
            $this->getDoctrine()->getManager()->flush();
        } else {
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('danger', 'Surfer non banni');
        }
        return $this->redirectToRoute('admin.surfers');
    }
}
