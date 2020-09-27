<?php


namespace App\Controller;
use App\Services\Security;

class SecurityController extends MainController
{
    /**
     * @var SecurityController
     */
    protected $security;

    private $em;

    public function __construct()
    {
        parent::__construct();
        $this->security = new Security();
        $this->em = $this->orm->entityManager();
    }

    public function registerMethod()
    {
        $message = null;
        $submit = $this->request->getPost()->get('submit');
        if (isset($submit))
        {
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
        session_destroy();
        header('location:index.php');
    }

    public function mailMethod()
    {
        $user = $this->request->getSession()->get('user');
        $subject = 'Validation de votre email';
        $message = 'Bonjour veuillez cliquez sur ce lien pour  valider votre email http://kenolane-granger.com/index.php?access=security!verified&k=' . $user->getRandomKey();
        mail($user->getEmail(),$subject,$message);
        $this->request->getSession()->set('message', 'Le mail a bien été envoyer');
        header("Location:index.php");
    }

    public function verifiedMethod()
    {
        $user = $this->request->getSession()->get('user');
        if ($this->request->getGet()->get('k') === $user->getRandomKey())
        {
            $user->setVerified(1);
            $this->em->merge($user);
            $this->em->flush();
            header("Location:index.php");
        }
    }

    public function contactMethod()
    {
        if (!empty($this->request->getPost()->get('name')) && !empty($this->request->getPost()->get('email')) && !empty($this->request->getPost()->get('subject')) && !empty($this->request->getPost()->get('firstName')) && !empty($this->request->getPost()->get('content')))
        {
           $sender = $this->request->getPost()->get('email');
           $addressee = 'kenolane28@gmail.com';
           $subject = $this->request->getPost()->get('subject');
           $header = "From:". $sender;
           $content = $this->request->getPost()->get('content');
           mail($addressee,$subject,$content,$header);
            header("Location:index.php");
        }
    }

    public function recoverPasswordMethod()
    {
        $submit = $this->request->getPost()->get('submit');
        if (isset($submit) && !empty($this->request->getPost()->get('email'))) {
            $email = $this->security->verifiedEmail($this->request->getPost()->get('email'));
            if (!empty($email)) {
                $user = $this->em->getRepository(':User')->findOneBy(array('email' => $this->request->getPost()->get('email')));
                $addressee = $user->getEmail();
                $subject = 'Changer votre Mot de passe';
                $content = 'Pour changer votre mot de passe cliquez sur ce lien http://kenolane-granger.com/index.php?access=security!changePassword&k=' . $user->getRandomKey() . '&e=' . $user->getEmail();
                mail($addressee, $subject, $content);
                header("Location:index.php");
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
                    if (preg_match('/^(?=.*\d)(?=.*[-.*&^%$#@()/_])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $this->request->getPost()->get('password1'))) {
                        $user->setPassword(password_hash($this->request->getPost()->get('password1'), PASSWORD_DEFAULT));
                        $this->em->persist($user);
                        $this->em->flush();
                        header("Location:index.php");
                    }
                }
            }
            return $this->render('security/changePassword.html.twig');
        }
    }

}
