<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ProductCategory
 *
 * @ORM\Entity()
 * @ORM\Table(name="product_category")
 */
class ProductCategory
{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="category_name", type="string", length=40, nullable=false, unique=true)
     */
    private $categoryName;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param integer $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * @param string $categoryName
     */
    public function setCategoryName($categoryName): void
    {
        $this->categoryName = $categoryName;
    }

}