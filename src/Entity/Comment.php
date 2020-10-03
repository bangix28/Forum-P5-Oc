<?php


namespace App\Entity;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


/**
 * Class CommentRepository
 * @package App\Entity
 * @ORM\Entity()
 * @ORM\Table(name="comment")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="integer", name="is_valid")
     */
    private $isValid;

    /**
     * @ORM\ManyToOne(targetEntity=Post::class, inversedBy="comments")
     * @ORM\JoinColumn(name="post_id", referencedColumnName="id", onDelete="cascade")
     */
    private $post;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="comments")
     */
    private $user;


    /**
     * CommentRepository constructor.
     * @param $user
     */
    public function __construct()
    {

    }

    /**
     * @return ArrayCollection
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getPost()
    {
        return $this->post;
    }

    public function setPost($post)
    {
        $this->post = $post;
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }


    /**
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param mixed $content
     */
    public function setContent($content)
    {
        $this->content = $content;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getIsValid()
    {
        return $this->isValid;
    }

    /**
     * @param mixed $isValid
     */
    public function setIsValid($isValid)
    {
        $this->isValid = $isValid;
    }



}
