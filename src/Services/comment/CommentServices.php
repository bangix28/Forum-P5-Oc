<?php


namespace App\Services\comment;


use App\Controller\MainController;
use App\Entity\Comment;

class CommentServices extends MainController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function Verification()
    {
        if (!empty($this->request->getPost()->get('content'))) {
            $this->create();
        }
    }

    public function create()
    {
        $em = $this->orm->entityManager();
        $post = $em->find(':Post', $this->request->getPost()->get('post'));
        $em->merge($post);
        $user = $em->find(':User', $this->request->getSession()->get('id'));
        $em->merge($user);
        $comment = New Comment();
        $comment->setContent($this->request->getPost()->get('content'));
        $comment->setCreatedAt(new \DateTime('now'));
        $comment->setIsValid('0');
        $comment->setPost($post);
        $comment->setUser($user);
        $em->persist($comment);
        $em->flush();
        header('Location:index.php?access=post!read&id=' . $this->request->getPost()->get('post'));
    }
}
