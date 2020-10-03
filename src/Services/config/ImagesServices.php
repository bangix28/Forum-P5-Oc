<?php


namespace App\Services\config;


use App\Controller\MainController;

class ImagesServices extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function uploadImage($a, $em)
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
                    $em->persist($user);
                    $em->flush();
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
