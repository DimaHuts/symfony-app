<?php

namespace App\Controller;


use App\Entity\ProductCategory;
use App\Events;
use App\Form\AddCategoryType;
use App\Service\DbService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Security("has_role('ROLE_ADMIN')")
 */
class AdminController extends Controller
{

    private $dbService;
    private $eventDispatcher;

    /**
     * AdminController constructor.
     * @param $dbService
     */
    public function __construct(EventDispatcherInterface $eventDispatcher, DbService $dbService)
    {
        $this->dbService = $dbService;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/admin", name="admin")
     * @Method("GET")
     */
    public function admin()
    {
        return $this->render('admin/admin.html.twig', [
            'categories' => $this->dbService->findByCriteria(ProductCategory::class, [], ['id' => 'DESC'])
        ]);
    }

    /**
     * @Route("/admin/category/edit/{id}", name="category-edit")
     * @Method({"GET", "POST"})
     */
    public function editCategory(Request $request, ProductCategory $category)
    {
        $form = $this->createForm(AddCategoryType::class, $category);
        $form->handleRequest($request);

        if (!$form->isSubmitted() or !$form->isValid())
        {
            return $this->render('admin/category-add.html.twig', [
                'form' => $form->createView()
            ]);
        }

        if ($this->dbService->saveData([$category]))
        {
            $this->eventDispatcher->dispatch(Events::CATEGORY_UPDATE, new Event());
        }

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/category/add", name="category-add")
     * @Method({"GET", "POST"})
     */
    public function addCategory(Request $request)
    {
        $category = new ProductCategory();
        $form = $this->createForm(AddCategoryType::class, $category);
        $form->handleRequest($request);

        if (!$form->isSubmitted() or !$form->isValid())
        {
            return $this->render('admin/category-add.html.twig', [
                'form' => $form->createView()
            ]);
        }

        if ($this->dbService->saveData([$category]))
        {
            $this->eventDispatcher->dispatch(Events::CATEGORY_UPDATE, new Event());
        }

        return $this->redirectToRoute('admin');
    }

    /**
     * @Route("/admin/category/delete/{id}", name="category-delete")
     */
    public function deleteCategory(Request $request, ProductCategory $category)
    {
        if (is_null($category))
        {
            $this->eventDispatcher->dispatch(Events::ENTITY_DOES_NOT_EXIST);
            return $this->redirectToRoute('admin');
        }

        if ($this->dbService->deleteData($category))
        {
            $this->eventDispatcher->dispatch(Events::CATEGORY_DELETE);
        }

        return $this->redirectToRoute('admin');
    }
}