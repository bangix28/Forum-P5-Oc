<?php


namespace App\Controller;
use App\Services\Security;

class SecurityController extends MainController
{
    /**
     * @var SecurityController
     */
    protected $security;

    public function __construct()
    {
        parent::__construct();
        $this->security = new Security();
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
        dump($user->getRandomKey());
        $message = 'Bonjour veuillez cliquez sur ce lien pour  valider votre email http://localhost:8000/index.php?access=security!verified&k='. $user->getRandomKey();
        mail($user->getEmail(),$subject,$message);
    }

    public function verifiedMethod()
    {

    }
}
