<?php


namespace App\Services\user;


use App\Controller\MainController;
use App\Services\Security;
use App\Entity\User;


class UserEdit extends MainController
{
    private $security;

    public function __construct()
    {
        parent::__construct();
        $this->security = new Security();
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
                $img = $this->uploadImage();
                if ($img)
                {
                    $user->setImage($img);
                    $this->editRedirect($em, $user);
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

    public function uploadImage()
    {
        $files = $this->request->getFiles()->get('form');
        if ($files['size'] <= 1000000)
        {
            $infosFiles = pathinfo($files['name']);
            $uploadExtension = $infosFiles['extension'];
            $authorizedExtension = array('jpg', 'jpeg', 'gif', 'png');
            if (in_array($uploadExtension, $authorizedExtension))
            {
                $name = 'user'. $this->request->getSession()->get('id') . '.' . $uploadExtension;
                move_uploaded_file($files['tmp_name'],'assets/img/upload/' . basename($name));
                echo "L'envoi a bien été effectué !";
                return $name;
            }

        }
    }
}
