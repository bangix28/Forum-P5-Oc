<?php


namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package App\Entity
 * @ORM\Entity(repositoryClass="Repository\User")
 * @ORM\Table(name="user")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $email;

    /**
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @ORM\Column(type="integer")
     */
    private $key;

    /**
     * @ORM\Column(type="integer")
     */
    private $logo;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="user", orphanRemoval=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="user", orphanRemoval=true)
     */
    private $post;




    public function __construct()
    {
        $this->post = new ArrayCollection();
        $this->comment = new ArrayCollection();
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
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * @param array $roles
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        $roles[] = 'ROLE_USER';

    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function setKey($key)
    {
        return $this->key = $key;
    }



    /**
     * @return ArrayCollection
     */
    public function getPosts()
    {
        return $this->post;
    }

    /**
     * @return ArrayCollection
     */
    public function getComments()
    {
        return $this->comment;
    }

    /**
     * @return mixed
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * @param mixed $logo
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;
    }

}
