<?php

namespace App\Controller;

use App\Entity\Internship;
use App\Form\InternshipType;
use App\Form\ProfileContactProviderType;
use App\Form\ProfileContactSurferType;
use App\Form\ProfileProviderInternshipType;
use App\Form\ProfileProviderServicesType;
use App\Form\ProfileSurferNewsletterType;
use App\Services\MailsSender;
use App\Services\PictureLinker;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProfileController extends AbstractController
{
    private $encoder;
    private $pl;

    /**
     * @var MailsSender
     */
    private $mailer;

    public function __construct(UserPasswordEncoderInterface $encoder, PictureLinker $pictureLinker, MailsSender $mailer)
    {
        $this->encoder = $encoder;
        $this->pl = $pictureLinker;
        $this->mailer = $mailer;
    }

    /**
     * @Route("/profile", name="profile")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Request $request)
    {
        $user = $this->getUser();
        $userType = $user->getClass();
        // call form entity class form
        if ($userType == 'Provider') {
            $form = $this->createForm(ProfileContactProviderType::class, $user);
        } else if ($userType == 'Surfer') {
            $form = $this->createForm(ProfileContactSurferType::class, $user);
        }
        // pass request to form
        $form->handleRequest($request);
        // check if $form is submitted
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * NEED CHECK HERE
             */

//            $picture = $form->getData()->getPicture();
//            $uploadedFile = $this->pl->getUploadedFile($request, 'picture', $picture);
            // if is provider add two files
//            if ($userType == 'provider') {
//                $picture = $form->getData()->getLogo();
//                $uploadedFile = $this->pl->getUploadedFile($request, 'logo', $picture);
//            }
            $user->setUpdatedAt(new \DateTime());
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();
        }
        return $this->render('profile/profile.html.twig', [
            'controller' => 'profile',
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/profile/newsletter", name="profile.newsletter")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function newsletter(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileSurferNewsletterType::class, $user);
        // pass request to form
        $form->handleRequest($request);
        return $this->render('profile/newsletter.html.twig', [
            'controller' => 'newsletter',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/newsletter/edit", name="profile.newsletter.update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function newsletterUpdate(Request $request)
    {
        $user = $this->getUser();
        // check if $form is submitted
        $requested = $request->request->all();
        $requested = reset($requested);
        $isSubToNewsletter = $requested['isSubToNewsletter'];
        if (!$user->getIsSubToNewsletter() && !is_null($isSubToNewsletter)) {
            $user->setIsSubToNewsletter(true);
            $this->mailer->sendMail([], 'You just subscribed to our newsletter', $user->getBasicEmail(), 'newsletter/sub');
        } else if($user->getIsSubToNewsletter() && is_null($isSubToNewsletter)){
            $user->setIsSubToNewsletter(false);
            $this->mailer->sendMail([], 'You just unsubscribed to our newsletter', $user->getBasicEmail(), 'newsletter/unsub');
        }
        $user->setUpdatedAt(new \DateTime());
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();
        $this->get('session')->getFlashBag()->clear();
        $this->addFlash("success", "Souscription newsletter modifiée ! ");
        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/profile/password", name="profile.password")
     */
    public function password()
    {
        return $this->render('profile/password.html.twig', [
            'controller' => 'password'
        ]);
    }

    /**
     * @Route("/profile/password/update", name="profile.password.update")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function passwordUpdate(Request $request)
    {
        // get current user
        $user = $this->getUser();
        // check if password is correct
        $currentPassword = $this->encoder->isPasswordValid($user, $request->get('current'));
        // only if the current password is correct
        if ($currentPassword) {
            if ($request->get('new') == $request->get('confirm')) {
                // update new password
                $user->setPassword($this->encoder->encodePassword($user, $request->get('new')));
                $this->getDoctrine()->getManager()->persist($user);
                $this->getDoctrine()->getManager()->flush();
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash("success", "Mot de passe modifié");
                $this->mailer->sendMail(['username' => $user->getUsername()], 'You just changed your password', $user->getBasicEmail(), 'password/change');
                return $this->redirectToRoute('profile');
            } else {
                $this->get('session')->getFlashBag()->clear();
                $this->addFlash("danger", "Mot de passe non modifié");
            }
        } else {
            $this->get('session')->getFlashBag()->clear();
            $this->addFlash("danger", "Ancien mot de passe invalid");
        }
        return $this->render('profile/password.html.twig', [
            'controller' => 'password'
        ]);
    }

    /**
     * @Route("/profile/services", name="profile.services")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function services(Request $request)
    {
        $provider = $this->getUser();
        $form = $this->createForm(ProfileProviderServicesType::class, $provider);
//        dd($form->getData()->getServices());
        $form->handleRequest($request);
        // check if $form is submitted
        if ($form->isSubmitted() && $form->isValid()) {
            // don't forget to set new update date
            $provider->setUpdatedAt(new \DateTime());
            // persist in db
            $this->getDoctrine()->getManager()->persist($provider);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('profile/services.html.twig', [
                'controller' => 'services',
                'form' => $form->createView()
            ]);
        }
        return $this->render('profile/services.html.twig', [
            'controller' => 'services',
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/profile/stages", name="profile.stages")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function stages(Request $request)
    {
        $provider = $this->getUser();
        $internship = new Internship();
        $form = $this->createForm(InternshipType::class, $internship);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // don't forget to set new update date
            $internship->setProvider($provider);
            // persist in db
            $this->getDoctrine()->getManager()->persist($internship);
            $this->getDoctrine()->getManager()->flush();
            return $this->render('profile/stages.html.twig', [
                'controller' => 'stages',
                'form' => $form->createView()
            ]);
        }
        return $this->render('profile/stages.html.twig', [
            'controller' => 'stages',
            'form' => $form->createView()
        ]);
    }
}
