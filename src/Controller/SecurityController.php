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
        $message = 'Bonjour veuillez cliquez sur ce lien pour  valider votre email http://localhost:8000/index.php?access=security!verified&k='. $user->getRandomKey();
        mail($user->getEmail(),$subject,$message);
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
            dump('test');
           $sender = $this->request->getPost()->get('email');
           $addressee = 'kenolane28@gmail.com';
           $subject = $this->request->getPost()->get('subject');
           $header = "From:". $sender;
           $content = $this->request->getPost()->get('content');
           mail($addressee,$subject,$content,$header);
           header('Location:index.php');
        }
    }
}
