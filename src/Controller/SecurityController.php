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
}
