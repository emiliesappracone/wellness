<?php

namespace App\Controller;

use App\Entity\Service;
use App\Form\ServiceType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    /**
     * @method show all services
     * @Route("/services", name="services.all")
     */
    public function allServices()
    {
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();
        return $this->render('front/services/index.html.twig', [
            'services' => $services,
        ]);
    }
    /** service.one
     * @Route("/service/{slug}", name="service.one")
     */
    public function showOneService($slug)
    {
        $service = $this->getDoctrine()->getRepository(Service::class)->findOneBy(['slug' => $slug]);
        return $this->render('front/services/detail.html.twig', [
            'service' => $service,
        ]);
    }

    /**
     * ADMIN ZONE --------------------------------------------------
     */
    /**
     * @Route("/admin/service/add", name="admin.service.add", methods="GET|POST")
     */
    public function add(Request $request)
    {
        $service = new Service();
        $form = $this->createForm(ServiceType::class, $service);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // don't forget to set new update date
            $service->setUpdatedAt(new \DateTime());
            // persist in db
            $this->getDoctrine()->getManager()->persist($service);
            $this->getDoctrine()->getManager()->flush();
            // redirect to route with message
            $this->addFlash('success', 'Création réussie');
            return $this->redirectToRoute("admin.services");
        }
        return $this->render('/admin/services/form.html.twig', [
            'form' => $form->createView(),
            'controller' => 'services',
            'action' => 'Créer'
        ]);
    }
    /** Update one service
     * @Route("/admin/service/{id}", name="admin.service.update", methods="GET|POST")
     */
    public function update(SessionInterface $session, Request $request, $id)
    {
        // call what service need to be updated
        $service = $this->getDoctrine()->getRepository(Service::class)->find($id);
        // call form entity class form
        $form = $this->createForm(ServiceType::class, $service);
        // pass request to form
        $form->handleRequest($request);
        // check if $form is submitted
        if ($form->isSubmitted() && $form->isValid()) {
            $service->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($service);
            $this->getDoctrine()->getManager()->flush();
            // redirect to route with message
            $this->addFlash('success', 'Edition réussie');
            return $this->redirectToRoute("admin.service.update", ["id" => $id]);
        }
        // if form not already submitted or not valid call createView method from form to send form in twig view
        return $this->render('admin/services/form.html.twig',
            [
                'form' => $form->createView(),
                'controller' => 'services',
                'action' => 'Editer'
            ]
        );
    }
    /**
     * @Route("/admin/service/delete/{id}", name="admin.service.delete", methods="DELETE")
     * @param Services $service
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Service $service, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $service->getId(), $request->get('_token'))) {
            $this->getDoctrine()->getManager()->remove($service);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Service supprimé');
        } else {
            $this->addFlash('danger', 'Service non supprimé');
        }
        return $this->redirectToRoute('admin.services');
    }
}
