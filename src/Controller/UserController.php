<?php

namespace App\Controller;

use App\Entity\Provider;
use App\Entity\Surfer;
use App\Form\SubProviderType;
use App\Form\SubSurferType;
use App\Services\MailsSender;
use App\Services\UsersMaker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @var MailsSender
     */
    private $mailer;

    /**
     * @var UsersMaker
     */
    private $um;

    /**
     * UsersController constructor.
     * @param MailsSender $mailer
     * @param UsersMaker $usersMaker
     */
    public function __construct(MailsSender $mailer, UsersMaker $usersMaker)
    {
        $this->mailer = $mailer;
        $this->um = $usersMaker;
    }

    /**
     * @Route("/subscribe/{type}", name="subscribe")
     * @var type - by default equals none, means not user type are preselected before to go on current function route
     * @return mixed
     */
    public function subscribe($type = 'none')
    {
        return $this->render('front/subscribe/subscribe.html.twig', [
            'type' => $type
        ]);
    }

    /**
     * @Route("/subscribe/add/provider", name="subscribe.add.provider")
     */
    public function provider(Request $request)
    {
        // Csrf
        if ($this->isCsrfTokenValid('create', $request->get('_token'))) {
            // get array of datas
            $datas = $this->newProvider($request);
            // init new Provider
            $provider = new Provider();
            // persist user
            $token = $this->um->makeUser($provider, $datas, 'token');
            if (!$token) {
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('danger', 'Email already used');
                return $this->redirectToRoute('subscribe', ['type' => 'provider']);
            } else {
                // message datas
                $messageDatas = ['token' => $token, 'name' => $datas['name'], 'email' => $datas['basicEmail'], 'type' => 'provider'];
                // Send mail
                $isSent = $this->mailer->sendMail($messageDatas, "Continue your subscription", $datas['basicEmail'], 'subscribe/validation');
                return $this->render('front/subscribe/_first/success.html.twig', [
                    'mail' => $isSent
                ]);
            }
        } else {
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('danger', 'Mauvais token');
            return $this->redirectToRoute('subscribe');
        }
    }

    /**
     * @Route("/subscribe/add/surfer", name="subscribe.add.surfer")
     */
    public function surfer(Request $request)
    {
        // Csrf
        if ($this->isCsrfTokenValid('create', $request->get('_token'))) {
            // get array of datas
            $datas = $this->newSurfer($request);
            // init new Surfer
            $surfer = new Surfer();
            // persist the user
            $token = $this->um->makeUser($surfer, $datas, 'token');
            if (!$token) {
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('danger', 'Email already used');
                return $this->redirectToRoute('subscribe', ['type' => 'surfer']);
            } else {
                // message datas
                $messageDatas = ['token' => $token, 'name' => $datas['firstname'], 'email' => $datas['basicEmail'], 'type' => 'surfer'];
                // Send mail
                $isSent = $this->mailer->sendMail($messageDatas, "Continue your subscription", $datas['basicEmail'], 'subscribe/validation');
                return $this->render('front/subscribe/_first/success.html.twig', [
                    'mail' => $isSent
                ]);
            }
        } else {
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash('danger', 'Mauvais token');
            return $this->redirectToRoute('subscribe');
        }
    }

    /**
     * @Route("/subscribe/complete/{type}/{token}", name="subscribe.complete.user")
     */
    public function complete($type, $token, Request $request)
    {
        if ($type == 'surfer') {
            $surfer = $this->getDoctrine()->getRepository(Surfer::class)->findOneBy(["token" => $token]);
            // call form entity class form
            $form = $this->createForm(SubSurferType::class, $surfer);
            // pass request to form
            $form->handleRequest($request);
            // check if $form is submitted
            if ($form->isSubmitted() && $form->isValid()) {
                $datas = $this->updateSurfer($surfer);
                // update Surfer
                $this->um->makeUser($surfer, $datas);
                // send mail completed inscription
                $this->mailer->sendMail(['username' => $surfer->getUsername()], 'Subscription completed', $surfer->getBasicEmail(), 'subscribe/confirmation');
                // redirect to route with message
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('success', 'Inscription réussie');
                return $this->render('front/subscribe/finish.html.twig', []);
            } else {

            }
        }
        if ($type == 'provider') {
            $provider = $this->getDoctrine()->getRepository(Provider::class)->findOneBy(["token" => $token]);
            // call form entity class form
            $form = $this->createForm(SubProviderType::class, $provider);
            // pass request to form
            $form->handleRequest($request);
            // check if $form is submitted
            if ($form->isSubmitted() && $form->isValid()) {
                $datas = $this->updateProvider($provider);
                // update Provider
                $this->um->makeUser($provider, $datas);
                // send mail completed inscription
                $this->mailer->sendMail(['username' => $provider->getUsername()], 'Subscription completed', $provider->getBasicEmail(), 'subscribe/confirmation');
                // redirect to route with message
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash('success', 'Inscription réussie');
                return $this->render('front/subscribe/finish.html.twig', []);
            } else {

            }
        }
        return $this->render('front/subscribe/complete.html.twig', [
            'type' => $type,
            'form' => $form->createView()
        ]);
    }

    /**
     * Method used to build array with key and value of surfer
     * @return array $datas
     */
    public function newSurfer($request)
    {
        $datas = [
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'basicEmail' => $request->get('email'),
            'isRegistered' => false,
            'roles' => ['ROLE_USER'],
            'updatedAt' => new \DateTime(),
            'token' => md5(uniqid() . rand(1, 1000)),
            'username' => $request->get('email'),
            'isSubToNewsletter' => false
        ];
        return $datas;
    }

    /**
     * Method used to build array with key and value of provider
     * @return array $datas
     */
    public function newProvider($request)
    {
        $datas = [
            'name' => $request->get('name'),
            'basicEmail' => $request->get('email'),
            'isRegistered' => false,
            'roles' => ['ROLE_USER'],
            'updatedAt' => new \DateTime(),
            'token' => md5(uniqid() . rand(1, 1000)),
            'username' => $request->get('email'),
        ];
        return $datas;
    }

    /**
     * Method used to update Surfer after confirmation
     * @param $surfer
     * @return array
     */
    public function updateSurfer($surfer)
    {
        $datas = [
            'firstname' => $surfer->getFirstname(),
            'lastname' => $surfer->getLastname(),
            'basicEmail' => $surfer->getBasicEmail(),
            'isRegistered' => true,
            'roles' => ['ROLE_USER'],
            'updatedAt' => new \DateTime(),
            'token' => md5(uniqid() . rand(1, 1000)),
            'username' => $surfer->getBasicEmail(),
            'isBanned' => false,
            'tryToConnect' => 0,
//            'registeredAt' => new \DateTime(),
            'password' => true
        ];
        return $datas;
    }

    /**
     * Method used to update Provider after confirmation
     * @param $provider
     * @return array
     */
    public function updateProvider($provider)
    {
        $datas = [
            'name' => $provider->getName(),
            'basicEmail' => $provider->getBasicEmail(),
            'isRegistered' => true,
            'roles' => ['ROLE_USER'],
            'updatedAt' => new \DateTime(),
            'token' => md5(uniqid() . rand(1, 1000)),
            'username' => $provider->getBasicEmail(),
            'isBanned' => false,
            'tryToConnect' => 0,
//            'registeredAt' => new \DateTime(),
            'password' => true
        ];
        return $datas;
    }
}
