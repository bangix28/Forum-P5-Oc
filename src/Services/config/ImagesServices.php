<?php


namespace App\Services\config;


use App\Controller\MainController;
use App\Services\Security;

class ImagesServices extends MainController
{
    private $security;

    private $em;

    public function __construct()
    {
        parent::__construct();
        $this->security =  new Security();
        $this->em = $this->orm->entityManager();

    }

    public function uploadImage($a)
    {
        $files = $this->request->getFiles()->get('form');
        if ($files['size'] <= 1000000) {
            $infosFiles = pathinfo($files['name']);
            $uploadExtension = $infosFiles['extension'];
            $authorizedExtension = array('jpg', 'jpeg', 'gif', 'png');
            if (in_array($uploadExtension, $authorizedExtension)) {
                $name = md5($files['name']).'.'. $infosFiles['extension'];
                move_uploaded_file($files['tmp_name'], 'assets/img/upload/' . basename($name));
                if ($a === 'user')
                {
                    $user = $this->request->getSession()->get('user');
                    $user->setImage($name);
                    $this->em->merge($user);
                    $this->em->flush();
                    $this->security->sessionLogin($user);
                    header('Location:index.php?access=user!read');
                }elseif ($a === 'post'){
                    return $name;
                }
            }else{
                return $message = 'Extension invalide';
            }
        }else{
            return $message = 'Le fichier doit faire moins de 10 Mo';
        }

        }
}
