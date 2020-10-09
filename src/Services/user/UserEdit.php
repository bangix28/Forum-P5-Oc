<?php


namespace App\Services\user;


use App\Controller\MainController;
use App\Services\config\ImagesServices;
use App\Services\Security;


class UserEdit extends MainController
{
    private $security;

    private $image;

    private $em;

    public function __construct()
    {
        parent::__construct();
        $this->security = new Security();
        $this->image = new ImagesServices();
        $this->em = $this->orm->entityManager();
    }

    public function edit($f,$form,$user)
    {
        switch ($f)
        {
            case '1':
                $mail = $this->security->verifiedEmail($form);
                    if (empty($mail))
                    {
                        $user->setEmail($form);
                        $this->editRedirect($user);
                    }
                break;
            case '2':
                if (preg_match('/^(?=.*\d)(?=.*[@#\-_$%^&+=§!?])(?=.*[a-z])(?=.*[A-Z])[0-9A-Za-z@#\-_$%^&+=§!\?]{8,20}$/', $form)) {
                    $user->setPassword(password_hash($form, PASSWORD_DEFAULT));
                    $this->editRedirect($user);
                }
                break;
            case '3':
                $user->setLastName($form);
                $this->editRedirect($user);
                break;
            case '4':
                $user->setFirstName($form);
                $this->editRedirect($user);
                break;
            case '5':
                $a = 'user';
                $img = $this->image->uploadImage($a);
                if (!$img)
                {
                    $this->editRedirect($user);
                }else{
                    return $img;
                }
        }
    }

    public function editRedirect($user)
    {
        $this->em->merge($user);
        $this->em->flush();
        $this->security->sessionLogin($user);
        header('Location:index.php?access=user!read');
    }
}
