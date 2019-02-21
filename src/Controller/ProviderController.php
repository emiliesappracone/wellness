<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Form\ProviderType;
use App\Services\UsersMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProviderController extends AbstractController
{
    /**
     * @var UsersMaker
     */
    private $um;

    /**
     * UsersController constructor.
     * @param UsersMaker $usersMaker
     */
    public function __construct(UsersMaker $usersMaker)
    {
        $this->um = $usersMaker;
    }


    /**
     * @method show all service providers
     * @Route("/providers/{first}", name="providers.all")
     */
    public function showAllProviders($first = 0)
    {
        $serviceProviders = $this->getDoctrine()->getRepository(Provider::class)->findAll();
        $count = sizeof($serviceProviders);
        $serviceProviders = $this->getDoctrine()->getRepository(Provider::class)->findAllProviders($first);
        return $this->render('front/providers/index.html.twig', [
            'providers' => $serviceProviders,
            'count' => $count,
            'all' => 'true'
        ]);
    }

    /**
     * @method show one service provider
     * @Route("/provider/{slug}", name="provider.one")
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function showOneProvider($slug)
    {
        $provider = $this->getDoctrine()->getRepository(Provider::class)->findOneBy(['slug' => $slug]);
        return $this->render('front/providers/detail.html.twig', [
            'provider' => $provider,
        ]);
    }

    /**
     * @Route("/providers/search/{first}", name="search.providers")
     * @param Request $request
     * @param int $first
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function retrieveProviders(Request $request, $first = 0)
    {
        // all get values
        $whatsSearched['locality'] = $request->get('locality');
        $whatsSearched['provider'] = $request->get('provider');
        $whatsSearched['services'] = $request->get('services');
        // total
        $allProviders = $this->getDoctrine()->getRepository(Provider::class)->findByAll($whatsSearched, $first, false);
        $count = sizeof($allProviders);
        // providers with limit
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findByAll($whatsSearched, $first, true);
        return $this->render('front/providers/index.html.twig', [
            'providers' => $providers,
            'count' => $count,
            'all' => 'false',
        ]);
    }
    /**
     * ADMIN ZONE --------------------------------------------------
     */
    /**
     * @Route("/admin/provider/add", name="admin.provider.add", methods="GET|POST")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function add(Request $request)
    {
        $provider = new Provider();
        $form = $this->createForm(ProviderType::class, $provider);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->um->checkMail($provider->getBasicEmail())) {
                $provider->setToken(md5(uniqid() . rand(1, 1000)));
                $provider->setRoles(['ROLE_USER']);
//            $provider->setRegisteredAt(new \DateTime());
                // don't forget to set new update date
                $provider->setUpdatedAt(new \DateTime());
                // persist in db
                $this->getDoctrine()->getManager()->persist($provider);
                $this->getDoctrine()->getManager()->flush();
                // redirect to route with message
                $this->addFlash('success', 'Création réussie');
                return $this->redirectToRoute("admin.providers");
            } else {
                $this->addFlash('danger', 'Email déjà utilisée');
            }
        }
        return $this->render('/admin/providers/form.html.twig', [
            'form' => $form->createView(),
            'controller' => 'providers',
            'action' => 'Créer'
        ]);
    }

    /**
     * @Route("/admin/provider/update/{id}", name="admin.provider.update", methods="GET|POST")
     * @param Request $request
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function update(Request $request, $id)
    {
        $provider = $this->getDoctrine()->getRepository(Provider::class)->find($id);
        $form = $this->createForm(ProviderType::class, $provider);
        $isChanged = false;
        if ($request->request->get('provider')['basicEmail'] != $provider->getBasicEmail()) {
            $isChanged = $this->um->checkMail($request->request->get('provider')['basicEmail']);
        }
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if (!$isChanged) {
                // don't forget to set new update date
                $provider->setUpdatedAt(new \DateTime());
                // persist in db
                $this->getDoctrine()->getManager()->persist($provider);
                $this->getDoctrine()->getManager()->flush();
                // redirect to route with message
                $this->addFlash('success', 'Edition réussie');
                return $this->redirectToRoute("admin.provider.update", ["id" => $id]);
            } else {
                $this->addFlash('danger', 'Email déjà utilisée');
            }
        }
        return $this->render('/admin/providers/form.html.twig', [
            'form' => $form->createView(),
            'controller' => 'providers',
            'action' => 'Editer'
        ]);
    }

    /**
     * @Route("/admin/provider/delete/{id}", name="admin.provider.delete", methods="DELETE")
     * @param Provider $provider
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Provider $provider, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $provider->getId(), $request->get('_token'))) {
            $this->getDoctrine()->getManager()->remove($provider);
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'Prestataire supprimé');
        } else {
            $this->addFlash('danger', 'Prestataire non supprimé');
        }
        return $this->redirectToRoute('admin.providers');
    }
}
