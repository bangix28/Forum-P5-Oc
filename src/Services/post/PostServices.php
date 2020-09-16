<?php


namespace App\Services\post;

use App\Controller\MainController;
use App\Entity\Post;
use App\Services\user\UserEdit;

class PostServices extends MainController
{
   private $userService;
    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserEdit();

    }

    public function editVerification($post)
    {
        if (!empty($this->request->getPost()->get('title')) && !empty($this->request->getPost()->get('content')))
        {
            $this->editPost($post);
        }else{
            echo 'Veuillez remplir tout les champs !';
        }
    }

    public function postVerification()
    {
        dump($this->request->getPost()->get('title'));
        if (!empty($this->request->getPost()->get('title')) && !empty($this->request->getFiles()->get('image')) && !empty($this->request->getPost()->get('content')))
        {
            $this->createPost();
        }else{
             echo 'Veuillez remplir tout les champs !';
        }
    }

    public function setPost($em,$post)
    {
        $user = $em->find(':User', $this->request->getSession()->get('id'));
        $em->merge($user);
        $post->setUser($user);
        $post->setTitle($this->request->getPost()->get('title'));
        $post->setContent(strip_tags($this->request->getPost()->get('content'), ['<p>', '<span>', '<em>', '<del>', '<sup>', '<sub>']));
        $post->setCreatedAt(new \DateTime('now'));
    }

    public function editPost($post)
    {
       $em = $this->orm->entityManager();
       $this->setPost($em,$post);
       $img = $this->request->getFiles()->get('image');
       $em->persist($post);
       $em->flush($post);
       if ($img['size'] > 1) {
           $this->addImage($post, $em);
       }
        header('Location:index.php?access=post!read&id=' . $post->getId());
    }

    public function createPost()
    {
        $em = $this->orm->entityManager();
        $post = new Post();
        $this->setPost($em, $post);
        $post->setThumbnail('1');
        $em->persist($post);
        $em->flush();
        $this->addImage($post, $em);
        header('Location:index.php?access=post!read&id=' . $post->getId());
    }

    public function addImage($post, $em)
    {
        $a = 'post';
        $b = $post->getId();
        $files = $files = $this->request->getFiles()->get('image');
        $img = $this->userService->uploadImage($a,$b, $files);
        if ($img)
        {
            $post->setThumbnail($img);
            $em->persist($post);
            $em->flush();
            return true;
        }
    }
}
