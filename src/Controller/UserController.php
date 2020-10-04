<?php


namespace App\Controller;
use App\Services\Security;
use App\Services\user\UserEdit;

class UserController extends MainController
{
    private $userEdit;

    private $security;

    private $em;

    public function __construct()
    {
        parent::__construct();
        $this->userEdit = new UserEdit();
        $this->security = new Security();
        $this->em = $this->orm->entityManager();
    }

    public function editMethod()
    {
        $user = $this->request->getSession()->get('user');
        $message = null;
        $submit = $this->request->getPost()->get('submit');
        $f = $this->request->getGet()->get('f');
        if (isset($submit))
        {
            if ($f === '5')
            {
                $form = $this->request->getFiles()->get('form');
            }else{
                $form = $this->request->getPost()->get('form');
            }
            if (!empty($form)) {
                if (password_verify($this->request->getPost()->get('password'), $user->getPassword())) {
                    if ($this->request->getPost()->get('token') === $this->request->getSession()->get('token')) {
                        $message = $this->userEdit->edit($f, $form, $user);
                    }
                }
                $message = 'Mauvais mot de passe';
            }
        }
        return $this->render('user/edit.html.twig', ['f' => $f, 'message' => $message]);
    }

    public function readMethod()
    {
       return $this->render('user/showUser.html.twig');
    }
    public function deleteMethod()
    {
        $user = $this->em->getRepository(':User')->find($this->request->getSession()->get('user'));
        $this->em->remove($user);
        $this->em->flush();
        $this->request->getSession()->stop();
        header('location:index.php');
    }
}
