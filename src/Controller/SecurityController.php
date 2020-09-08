<?php


namespace App\Controller;
require_once __DIR__ . '/../../config/Orm.php';
use App\Services\config\Request;
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
        if (isset($_POST['submit']))
        {
            $message = $this->security->register();
        }

        return $this->render('security/register.html.twig', ['message' => $message]);
    }
    public function loginMethod()
    {
        if (isset($_POST['submit'])){
            $this->security->login();
        }
    }

    public function logoutMethod()
    {
        session_destroy();
        header('location:index.php');
    }
}
