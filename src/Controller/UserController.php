<?php


namespace App\Controller;
use App\Services\Security;
use App\Services\user\UserEdit;

class UserController extends MainController
{
    protected $userEdit;

    private $security;

    public function __construct()
    {
        parent::__construct();
        $this->userEdit = new UserEdit();
        $this->security = new Security();
    }

    public function editMethod()
    {
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
                $message = $this->userEdit->edit($f,$form);
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
        $id = $this->request->getSession()->get('id');
        $em = $this->orm->entityManager();
        $user = $em->find(':User', $id);
        $em->remove($user);
        $em->flush();
        $this->request->getSession()->stop();
        header('location:index.php');
    }
}
