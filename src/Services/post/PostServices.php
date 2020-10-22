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

   private $em;

    public function __construct()
    {
        parent::__construct();
        $this->userService = new UserEdit();
        $this->imageServices = new ImagesServices();
        $this->em = $this->orm->entityManager();
    }

    public function createVerification()
    {
        if (!empty($this->request->getPost()->get('title')) && !empty($this->request->getPost()->get('content'))
        && !empty($this->request->getFiles()->get('form')))
        {
            if ($this->request->getPost()->get('token') === $this->request->getSession()->get('token')) {
                $this->create();
            }
        }else{
            return $message = 'Remplissez tout les champs nécésaires';
        }

    }

    public function editVerification($post)
    {
        if (!empty($this->request->getPost()->get('title')) && !empty($this->request->getPost()->get('content'))) {
            $this->edit($post);
        } else {
            $this->request->getSession()->set('merror', 'Remplissez tous les champs !');
        }
    }

    public function create()
    {
        $post = new Post();
        $user = $this->em->find(':User', $this->request->getSession()->get('user'));
        $this->em->merge($user);
        $post->setUser($user);
        $post->setCreatedAt(new \DateTime('now'));
        $post->setTitle(strip_tags($this->request->getPost()->get('title')));
        $post->setContent(strip_tags($this->request->getPost()->get('content'),'<p><span><style><del><sub><li><ol><ul><a><h1><h2><h3><h4><h5>'));
        $post->setVerified(0);
        $a = 'post';
        $name = $this->imageServices->uploadImage($a);
        $post->setThumbnail($name);
        $this->request->getSession()->set('msuccess', "Post crée avec succès, votre article va être verifier par un administrateur.");
        $this->em->persist($post);
        $this->redirect($post);
    }

    public function edit($post)
    {
        $post->setEditedAt(new \DateTime());
        $post->setTitle(strip_tags($this->request->getPost()->get('title')));
        $post->setContent(strip_tags($this->request->getPost()->get('content'),'<p><span><style><del><sub><li><ol><ul><a><h1><h2><h3><h4><h5>'));
        $img = $this->request->getFiles()->get('form');
        if ($img['error'] === 0)
        {
            $a = 'post';
            $name = $this->imageServices->uploadImage($a);
            $post->setThumbnail($name);
        }
        $this->em->merge($post);
        $this->redirect($post);
    }

    public function redirect($post)
    {

        $this->em->flush();
        if ($post->getVerified() === 1) {
            header('Location:index.php?access=post!read&id=' . $post->getId());
        }else {
            header('Location:index.php');
        }
    }

    public function search()
    {
        $qb = $this->em->getRepository(':Post')->createQueryBuilder('u');
        $qb
            ->where(
                $qb->expr()->andX(
                    $qb->expr()->orX(
                        $qb->expr()->like('u.content', ':query'),
                        $qb->expr()->like('u.title', ':query')
                    )
                )

            )
            ->setParameter('query', '%' . $this->request->getPost()->get('f') . '%' )
        ;
        return $qb
            ->getQuery()
            ->getResult();
    }

}
