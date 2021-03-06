<?php


namespace App\Services\mail;


use App\Controller\MainController;
use Swift_Mailer;
use Swift_SmtpTransport;

class Mail extends MainController
{

    public function __construct()
    {
        parent::__construct();

    }

    public function transport()
    {
        $transport = (new Swift_SmtpTransport('votre smtp', "port du smtp", 'ssl'))
            ->setUsername('votre mail')
            ->setPassword('mot de passe du mail')
        ;
        return $mailer = new Swift_Mailer($transport);
    }

    public function verifiedMail()
    {
        $user = $this->request->getSession()->get('user');
        $mailer = $this->transport();
        $message = (new \Swift_Message())
            ->setFrom('contact@kenolane-granger.com')
            ->setTo($user->getEmail())
            ->setSubject('Confirmation de votre compte !')
            ->setBody(
                $this->render('mail/confirmation.html.twig', array(
                        'user' => $user
                    )
                ),
                'text/html'
            );
        if ($mailer->send($message))
        {
            $this->request->getSession()->set('msuccess', 'Mail envoyer !');
        }else
        {
            $this->request->getSession()->set('merror', "Probléme avec l'envoie d'email");
        }
    }

    public function contactMail()
    {
        $mailer = $this->transport();
        $message = (new \Swift_Message())
            ->setFrom($this->request->getPost()->get('email'))
            ->setTo('Votre addresse email')
            ->setSubject($this->request->getPost()->get('subject'))
            ->setBody(
                $this->render('mail/contact.html.twig', array(
                        'mail' => $this->request->getPost()->get('email'),
                        'firstName' => $this->request->getPost()->get('firstName'),
                        'lastName' => $this->request->getPost()->get('lastName'),
                        'content' => $this->request->getPost()->get('content')
                    )
                ),
                'text/html'
            );
        if ($mailer->send($message))
        {
            $this->request->getSession()->set('msuccess', 'Mail envoyer !');
        }else
        {
            $this->request->getSession()->set('merror', "Probléme avec l'envoie d'email");
        }
    }

    public function recoverPassword($user)
    {
        $mailer = $this->transport();
        $message = (new \Swift_Message())
            ->setFrom('votre addresse email')
            ->setTo($user->getEmail())
            ->setSubject('Changement de mot de passe')
            ->setBody(
                $this->render('mail/password.html.twig', array(
                        'user' => $user
                    )
                ),
                'text/html'
            );
        if ($mailer->send($message))
        {
            $this->request->getSession()->set('msuccess', 'Mail envoyer !');
        }else
        {
            $this->request->getSession()->set('merror', "Probléme avec l'envoie d'email");
        }
    }
}
