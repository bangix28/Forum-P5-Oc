<?php


namespace App\Controller;

use App\Services\mail\Mail;
use App\Services\Security;

class SecurityController extends MainController
{
    /**
     * @var SecurityController
     */
    private $security;

    private $em;

    private $mail;

    public function __construct()
    {
        parent::__construct();
        $this->security = new Security();
        $this->em = $this->orm->entityManager();
        $this->mail = new Mail();
    }

    public function registerMethod()
    {
        $message = null;
        $submit = $this->request->getPost()->get('submit');
        if (isset($submit)) {
            $message = $this->security->register();
        }

        return $this->render('security/register.html.twig', ['message' => $message]);
    }

    public function loginMethod()
    {
        $submit = $this->request->getPost()->get('submit');
        if (isset($submit)){
            $this->security->login();
        }
    }

    public function logoutMethod()
    {
        session_unset();
        $this->request->getSession()->set('msuccess', "Compte Déconnecté");
        header('location:index.php');
    }

    public function mailMethod()
    {
        $this->mail->verifiedMail();
        $this->request->getSession()->set('msuccess', "Mail envoyé");
        header('Location:index.php?access=user!read');
    }

    public function verifiedMethod()
    {
        $user = $this->request->getSession()->get('user');
        if ($this->request->getGet()->get('k') === $user->getRandomKey()) {
            $user->setVerified(1);
            $this->em->merge($user);
            $this->em->flush();
            $this->request->getSession()->set('msuccess', "Confirmation de compte effectué");
            header("Location:index.php");
        }
        $this->request->getSession()->set('merror', "Probleme avec la confirmation, contactez votre administrateur web");
    }

    public function contactMethod()
    {
        if (!empty($this->request->getPost()->get('name')) && !empty($this->request->getPost()->get('email')) && !empty($this->request->getPost()->get('subject')) && !empty($this->request->getPost()->get('firstName')) && !empty($this->request->getPost()->get('content'))) {
            $this->mail->contactMail();
            $this->request->getSession()->set('msuccess', "Mail envoyer !");
            header("Location:index.php");
        } else {
            $this->request->getSession()->set('merror', "Vous devez remplir tous les champs");
        }
    }

    public function recoverPasswordMethod()
    {
        $submit = $this->request->getPost()->get('submit');
        if (isset($submit)) {
            if (!empty($this->request->getPost()->get('email'))) {
                $email = $this->security->verifiedEmail($this->request->getPost()->get('email'));
                if (!empty($email)) {
                    $user = $this->em->getRepository(':User')->findOneBy(array('email' => $this->request->getPost()->get('email')));
                    $this->mail->recoverPassword($user);
                    header("Location:index.php");
                } else {
                    $this->request->getSession()->set('merror', "Vous devez renseigner un email !");
                }
            } else {
                $this->request->getSession()->set('merror', "Email non existant !");
            }
        }
        return $this->render('security/recoverPassword.html.twig');
    }

    public function changePasswordMethod()
    {
        $user = $this->em->getRepository(':User')->findOneBy(array('email' => $this->request->getGet()->get('e')));
        if ($this->request->getGet()->get('k') === $user->getRandomKey()) {
            $submit = $this->request->getPost()->get('submit');
            if (isset($submit) && !empty($this->request->getPost()->get('password1')) && !empty($this->request->getPost()->get('password2'))) {
                if ($this->request->getPost()->get('password1') === $this->request->getPost()->get('password2')) {
                    if (preg_match('/^(?=.*\d)(?=.*[@#\-_$%^.&+=§!\?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^.&+=§!\?]{8,20}$/', $this->request->getPost()->get('password1'))) {
                        $user->setPassword(password_hash($this->request->getPost()->get('password1'), PASSWORD_DEFAULT));
                        $this->em->persist($user);
                        $this->em->flush();
                        header("Location:index.php");
                    } else {
                        $this->request->getSession()->set('merror', "Vous devez respecter le format de mot de passe");
                    }
                } else {
                    $this->request->getSession()->set('merror', "Vous devez remplir tout les champs");
                }
            } else {
                $this->request->getSession()->set('merror', "Problème avec la confirmation, contactez votre administrateur web");
            }
        }
        return $this->render('security/changePassword.html.twig');
    }

    public function sessionDelayMethod()
    {
        $this->request->getSession()->remove('merror');
        $this->request->getSession()->remove('msuccess');
    }

}
