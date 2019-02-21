<?php
/**
 * Created by PhpStorm.
 * User: Emilie Sappracone
 * Date: 17-02-19
 * Time: 17:43
 */

namespace App\Services;


class MailsSender
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    /**
     * @var \Twig_Environment
     */
    private $twig;
    /**
     * @var Admin email
     */
    private $adminEmail;
    /**
     * SendMails constructor.
     * @param \Swift_Mailer $mailer
     * @param \Twig_Environment $twig
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, $adminEmail)
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->adminEmail = $adminEmail;
    }
    /**
     * @param array $datas
     * @param string $subject
     * @param string $from
     * @param string $to
     * @param string $template
     * @return int
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function sendMail(array $datas, string $subject, string $to, string $template){
        // prepare message
        $message = (new \Swift_Message($subject))
            ->setFrom($this->adminEmail)
            ->setTo($to)
            ->setBody(
                $this->twig->render(
                    "emails/$template.html.twig",
                    $datas
                ),
                'text/html'
            );
        // send mail
        $isSent = $this->mailer->send($message);
        // return if message is sent correctly
        return $isSent;
    }
    /**
     * @return \Swift_Mailer
     */
    public function getMailer(): \Swift_Mailer
    {
        return $this->mailer;
    }
}