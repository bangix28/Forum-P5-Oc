<?php


namespace App\Services\user;


use App\Controller\MainController;
use App\Services\config\ImagesServices;
use App\Services\Security;
use App\Entity\User;


class UserEdit extends MainController
{
    private $security;

    private $image;

    public function __construct()
    {
        parent::__construct();
        $this->security = new Security();
        $this->image = new ImagesServices();
    }

    public function edit($f,$form)
    {
        $em = $this->orm->entityManager();
        $user = $em->getRepository(':User')->findOneBy(['id' =>$this->request->getSession()->get('id')]);
        switch ($f)
        {
            case '1':
                $mail = $this->security->verifiedEmail($form);
                    if (empty($mail))
                    {
                        $user->setEmail($form);
                        $this->editRedirect($em, $user);
                    }
                break;
            case '2':
                if (preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $form)) {
                    $user->setPassword(password_hash($form, PASSWORD_DEFAULT));
                    $this->editRedirect($em, $user);
                }
                break;
            case '3':
                $user->setLastName($form);
                $this->editRedirect($em, $user);
                break;
            case '4':
                $user->setFirstName($form);
                $this->editRedirect($em, $user);
                break;
            case '5':
                $a = 'user';
                $img = $this->image->uploadImage($a, $em);
                if (!$img)
                {
                    $this->editRedirect($em, $user);
                }else{
                    return $img;
                }
        }
    }

    public function editRedirect($em, $user)
    {
        $em->persist($user);
        $em->flush();
        $this->security->sessionLogin($user);
        header('Location:index.php?access=user!read');
    }
}
