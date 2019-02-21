<?php

namespace App\Controller;

use App\Entity\Locality;
use App\Entity\Provider;
use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    /**
     * @method retrieve list providers
     */
    public function retrieveForListProviders()
    {
        $providers = $this->getDoctrine()->getRepository(Provider::class)->findAll();
        return $this->render('front/list/providers.html.twig', [
            'providers' => $providers,
        ]);
    }

    /**
     * @method retrieve list services
     * @param $where
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function  retrieveForListServices($where)
    {
        $services = $this->getDoctrine()->getRepository(Service::class)->findAll();
        if($where == 'list'){
            $template = 'front/list/services.html.twig';
        }else{
            $template = 'front/list/servicesOptions.html.twig';
        }
        return $this->render($template, [
            'services' => $services,
        ]);
    }

    /**
     * @method retrieve list localities
     */
    public function retrieveForListLocalities()
    {
        $localities = $this->getDoctrine()->getRepository(Locality::class)->findAll();
        return $this->render('front/list/localities.html.twig', [
            'localities' => $localities,
        ]);
    }
}
