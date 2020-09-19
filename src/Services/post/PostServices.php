<?php


namespace App\Services\post;

use App\Controller\MainController;
use App\Entity\Post;
use App\Services\config\ImagesServices;
use App\Services\user\UserEdit;

class PostServices extends MainController
{
   private $userService;

   private $imageServices;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserEdit();
        $this->imageServices = new ImagesServices();
    }

    public function createVerification()
    {
        if (!empty($this->request->getPost()->get('title')) && !empty($this->request->getPost()->get('content'))
        && !empty($this->request->getFiles()->get('form')))
        {
            echo 'tet';
            $this->create();
        }else{
            return $message = 'Remplissez tout les champs nécésaires';
        }

    }

    public function editVerification($post,$em)
    {
        if (!empty($this->request->getPost()->get('title')) && !empty($this->request->getPost()->get('content')))
        {
            $this->edit($post,$em);
        }
    }

    public function create()
    {
        $em = $this->orm->entityManager();
        $post = new Post();
        $user = $em->find(':user', $this->request->getSession()->get('id'));
        $em->merge($user);
        $post->setUser($user);
        $post->setCreatedAt(new \DateTime('now'));
        $post->setTitle($this->request->getPost()->get('title'));
        $post->setContent($this->request->getPost()->get('content'));
        $a = 'post';
        $name = $this->imageServices->uploadImage($a,$em);
        $post->setThumbnail($name);
        $this->redirect($em,$post);
    }

    public function edit($post,$em)
    {
        $post->setTitle($this->request->getPost()->get('title'));
        $post->setContent($this->request->getPost()->get('content'));
        $img = $this->request->getFiles()->get('form');
        if ($img['error'] === 0)
        {
            $a = 'post';
            $name = $this->imageServices->uploadImage($a,$em);
            $post->setThumbnail($name);
        }
       $this->redirect($em,$post);
    }

    public function redirect($em,$post)
    {
        $em->persist($post);
        $em->flush();
        header('Location:index.php?access=post!read&id='. $post->getId());
    }

}
