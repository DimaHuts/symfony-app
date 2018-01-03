<?php

namespace App\Entity;


use App\Service\UploadService;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Product
 *
 * @ORM\Entity()
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Table(name="product")
 */
class Product
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 3,
     *     max = 50,
     * )
     *
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThan(0)
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    private $price;

    /**
     * @Assert\NotBlank()
     * @Assert\Length(
     *     min = 10,
     *     max = 100,
     * )
     *
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="array")
     */
    private $files;

    private $uploadedFiles;

    /**
     * @return mixed
     */
    public function getUploadedFiles()
    {
        return $this->uploadedFiles;
    }

    /**
     * @param mixed $uploadedFiles
     */
    public function setUploadedFiles($uploadedFiles)
    {
        $this->uploadedFiles = $uploadedFiles;
    }

    /**
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function prePersist()
    {
        if (!empty($this->getUploadedFiles()))
        {
            if (!empty($this->getFiles()))
            {
                UploadService::removeFiles($this->getFiles());
            }

            $this->setFiles(UploadService::transferFiles($this->getUploadedFiles()));
        }
    }

    /**
     * @ORM\PreRemove()
     */
    public function preDelete()
    {
        if (!empty($this->files))
        {
            UploadService::removeFiles($this->files);
        }
    }

    /**
     * @return mixed
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param mixed $files
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false, name="user", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductCategory")
     * @ORM\JoinTable(
     *     name="products_categories",
     *     joinColumns={
     *          @ORM\JoinColumn(name="product", referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="category", referencedColumnName="id")
     *    }
     * )
     */
    private $category;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $author
     */
    public function setUser(User $author): void
    {
        $this->user = $author;
    }

    /**
     * @return mixed
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param mixed $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

}