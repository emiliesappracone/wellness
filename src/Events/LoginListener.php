<?php
/**
 * Created by PhpStorm.
 * User: Emilie Sappracone
 * Date: 17-02-19
 * Time: 17:50
 */

namespace App\Events;


use App\Entity\User;
use App\Services\MailsSender;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\AuthenticationEvents;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginListener implements EventSubscriberInterface
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    /**
     * @var MailsSender
     */
    protected $sendMails;

    /**
     * LoginListener constructor.
     * @param EntityManagerInterface $entityManager
     * @param MailsSender $sendMails
     */
    public function __construct(EntityManagerInterface $entityManager, MailsSender $sendMails)
    {
        $this->em = $entityManager;
        $this->sendMails = $sendMails;
    }
    /**
     * Call method on events
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            AuthenticationEvents::AUTHENTICATION_FAILURE => 'onAuthenticationFailure',
        ];
    }

    /**
     * Method check and increment try to connect on AUTHENTICATION_FAILURE event
     * @param AuthenticationFailureEvent $event
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        // 0- Number of tries left to user : change the value if update needed
        // limit** of tries
        $maxTries = 4;
        // 1- Get current user username (user trying to connect)
        $lastUsername = $event->getAuthenticationToken()->getUser();
        // 2- Doctrine get user object
        $userTryConnect = $this->em->getRepository(User::class)->findOneBy(['username' => $lastUsername]);
        // 2b - user exists ?
        if ($userTryConnect) {
            // 3- Before, check if user haven't been banned
            if ($userTryConnect->getIsBanned() != true) {
                // 4- After check ban, get number of tries
                $previousTryToConnect = $userTryConnect->getTryToConnect();
                // 4b- if number of tries is lower than limit**
                if ($previousTryToConnect < $maxTries) {
                    // 4c- prepare new number of tries
                    $newTryToConnect = ($previousTryToConnect == NULL || $previousTryToConnect == 0) ? 1 : $previousTryToConnect + 1;
                    // 4d- update than persist/flush in db
                    $userTryConnect->setTryToConnect($newTryToConnect);
                    $this->em->persist($userTryConnect);
                    $this->em->flush();
                    // 5e- send mail
                    if ($newTryToConnect == $maxTries) {
                        $this->sendMails->sendMail([], 'Account locked', $userTryConnect->getBasicEmail(), 'ban/tries');
                        $attemptMessage = "Compte bloqué après 4 essais";
                    } else {
                        // 4e- format message to show on Exception
                        $attemptMessage = $maxTries - $newTryToConnect;
                        $attemptMessage = "Nombre d'essai restant : " . $attemptMessage;
                    }
                    /**
                     * return custom Exception : Number of tries left
                     */
                    throw new CustomUserMessageAuthenticationException($attemptMessage);
                } else {
                    // 4b- if number of tries is upper than limit
                    $newTryToConnect = $previousTryToConnect;
                    $attemptMessage = "Compte bloqué après 4 essais";
                    /**
                     * return custom Exception : No more try left, the account is banned
                     */
                    throw new CustomUserMessageAuthenticationException($attemptMessage);
                }
            } else {
                throw new CustomUserMessageAuthenticationException('Compte banni');
            }
        } else {
            throw new CustomUserMessageAuthenticationException('Compte introuvable');
        }
    }

}