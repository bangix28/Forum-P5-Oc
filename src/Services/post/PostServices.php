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

    public function editVerification($post,$em)
    {
        if (!empty($this->request->getPost()->get('title')) && !empty($this->request->getPost()->get('content')))
        {
            $this->edit($post,$em);
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
        $post->setTitle($this->request->getPost()->get('title'));
        $post->setContent(strip_tags($this->request->getPost()->get('content'),'<p><span><style><del><sub><li><ol><ul>'));
        $a = 'post';
        $name = $this->imageServices->uploadImage($a,$this->em);
        $post->setThumbnail($name);
        $this->request->getSession()->set('msuccess', 'Post crée avec succès');
        $this->redirect($this->em,$post);
    }

    public function edit($post,$em)
    {
        $post->setEditedAt(new \DateTime());
        $post->setTitle($this->request->getPost()->get('title'));
        $post->setContent(strip_tags($this->request->getPost()->get('content'),'<p><span><style><del><sub><li><ol><ul>'));
        $img = $this->request->getFiles()->get('form');
        if ($img['error'] === 0)
        {
            $a = 'post';
            $name = $this->imageServices->uploadImage($a,$em);
            $post->setThumbnail($name);
        }
        $this->request->getSession()->set('msuccess', 'Post éditée avec succès');
       $this->redirect($em,$post);
    }

    public function redirect($em,$post)
    {
        $em->persist($post);
        $em->flush();
        header('Location:index.php?access=post!read&id='. $post->getId());
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
