<?php


namespace App\Controller;


use App\Services\comment\CommentServices;

class CommentController extends MainController
{
    private $commentServices;

    private $em;

    public function __construct()
    {
        parent::__construct();
        $this->commentServices = new CommentServices();
        $this->em = $this->orm->entityManager();
    }

    public function createMethod()
    {
        $this->commentServices->Verification();
        $this->request->getSession()->set('msuccess', "commentaires créés avec succès !");
    }
    public function editMethod()
    {
        if (!empty($this->request->getSession()->get('user'))) {
            $comment = $this->em->find(':Comment', $this->request->getGet()->get('id'));
            if ($this->request->getSession()->get('user')->getId() == $comment->getUser()->getId() || $this->request->getSession()->get('user')->getId() == $comment->getPost()->getUser()->getId()) {
                $submit = $this->request->getPost()->get('submit');
                if (isset($submit)) {
                    if (!empty($this->request->getPost()->get('content'))) {
                        $comment->setContent($this->request->getPost()->get('content'));
                        $this->em->merge($comment);
                        $this->em->flush();
                        header('Location:index.php?access=post!read&id=' . $comment->getPost()->getId());
                    } else {
                        return $message = 'Ne laissez pas de champs vide';
                    }
                }
                return $this->render('post/editComment.html.twig');
            } else {
                return $this->render('security/403.html.twig');
            }
        } else {
            return $this->render('security/403.html.twig');
        }
    }

    public function deleteMethod()
    {
        if (!empty($this->request->getSession()->get('user'))) {
            $comment = $this->em->find(':Comment', $this->request->getGet()->get('id'));
            if ($this->request->getSession()->get('user')->getId() === $comment->getUser()->getId() || $this->request->getSession()->get('user')->getId() == $comment->getPost()->getUser()->getId()) {
                $this->em->remove($comment);
                $this->em->flush();
                $this->request->getSession()->set('msuccess', "commentaires supprimés !");
                header('Location:' . $_SERVER['HTTP_REFERER']);
            }
            return $this->render('security/403.html.twig');
        }
        return $this->render('security/403.html.twig');
    }

    public function ViewMethod()
    {
        if (!empty($this->request->getSession()->get('user'))) {
            $post = $this->em->getRepository(':Post')->findBy(['user' => $this->request->getSession()->get('user')]);
            $comment = $this->em->getRepository(':Comment')->findBy(['post' => $post]);
            return $this->render('user/validateComment.html.twig', [
                'comment' => $comment,
                'post' => $post
            ]);
        }
        return $this->render('security/404.html.twig');
    }

    public function validateMethod()
    {
        if (!empty($this->request->getSession()->get('user'))) {
            if (!empty($this->request->getGet()->get('id'))) {
                $comment = $this->em->find(':Comment', $this->request->getGet()->get('id'));
                if ($this->request->getSession()->get('user')->getId() === $comment->getPost()->getUser()->getId()) {
                    $comment->setIsValid(1);
                    $this->em->persist($comment);
                    $this->em->flush();
                    $this->request->getSession()->set('msuccess', "commentaires validés !");
                    header('Location:' . $_SERVER['HTTP_REFERER']);
                }
                return $this->render('security/403.html.twig');
            }
            return $this->render('security/404.html.twig');
        }
        return $this->render('security/403.html.twig');
    }
}
