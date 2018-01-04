<?php

namespace App\Controller;

use App\Entity\CSVFile;
use App\Entity\Product;
use App\Events;
use App\Form\CSVFileType;
use App\Form\ProductType;
use App\Service\DbService;
use App\Service\PaginatingService;
use App\Service\ProductService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ProductController
 *
 * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
 */
class ProductController extends AbstractController
{

    private $dbService;
    private $translator;
    private $paginationService;
    private $serializer;
    private $eventDispatcher;
    private $productService;

    /**
     * ProductController constructor.
     *
     * @param DbService $dbService
     * @param TranslatorInterface $translator
     * @param PaginatingService $paginationService
     * @param SerializerInterface $serializer
     * @param EventDispatcherInterface $eventDispatcher
     * @param ProductService $productService
     */
    public function __construct(DbService $dbService,
                                TranslatorInterface $translator,
                                PaginatingService $paginationService,
                                SerializerInterface $serializer,
                                EventDispatcherInterface $eventDispatcher,
                                ProductService $productService)
    {
        $this->dbService = $dbService;
        $this->translator = $translator;
        $this->paginationService = $paginationService;
        $this->serializer = $serializer;
        $this->eventDispatcher = $eventDispatcher;
        $this->productService = $productService;
    }


    /**
     * @Route("/")
     * @Method("GET")
     *
     * @return Response
     */
    public function listAction(Request $request)
    {
        $filter = $request->get('filter');

        $queryData = $this->productService->getUserByFilter($filter, $this->getUser());

        $thisPage = $request->get('page', 1);
        $products = $this->paginationService->paginate($queryData, $thisPage);
        $maxPages = $this->paginationService->getMaxPages();
        $thisPage = $this->paginationService->getCurrentPage();

        $csvForm = $this->createForm(CSVFileType::class, new CSVFile())->createView();

        return $this->render('product/list.html.twig', compact('products', 'maxPages', 'thisPage', 'filter', 'csvForm'));
    }

    /**
     * @Route("/add", name="add-product")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     * @return Response
     *
     * This method adds a new product object to database
     */
    public function addAction(Request $request)
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if (!$form->isSubmitted() or !$form->isValid())
        {
            return $this->render('product/add.html.twig', ['form' => $form->createView()]);
        }

        $this->dbService->saveData([$product], Events::PRODUCT_ADD, $this->getUser());

        return $this->redirectToRoute('homepage', ['filter' => 'my']);
    }

    /**
     * @Route("/edit/{id}", name="edit-product", requirements={"id": "\d+"})
     * @Method({"GET", "POST"})
     *
     * This method modifies specific product and shows all products
     */
    public function editAction(Request $request)
    {
        $id = $request->get('id');
        $product = $this->dbService->findOneByCriteria('App:Product', ['id' => $id]);

        if (is_null($product))
        {
            $this->eventDispatcher->dispatch(Events::ENTITY_DOES_NOT_EXIST);
            return $this->redirectToRoute('homepage');
        }

        $this->productService->isDenied($product->getUser(), $this->getUser());

        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if (!$form->isSubmitted() or !$form->isValid())
        {
            return $this->render('product/add.html.twig', ['form' => $form->createView()]);
        }

        $this->dbService->saveData([$product], Events::PRODUCT_MODIFIED, $this->getUser());

        return $this->redirectToRoute('homepage', ['filter' => 'my']);
    }

    /**
     * @Route("delete/{id}", name="delete-product", requirements={"id": "\d+"})
     * @Method("POST")
     *
     * This method removes one product
     */
    public function deleteAction(Request $request)
    {
        $id = (int)$request->get('id');
        $product = $this->dbService->findOneByCriteria('App:Product', ['id' => $id]);

        if (is_null($product))
        {
            $this->eventDispatcher->dispatch(Events::ENTITY_DOES_NOT_EXIST);
            return $this->redirectToRoute('homepage');
        }

        $this->productService->isDenied($product->getUser(), $this->getUser());

        $this->dbService->deleteData($product, Events::PRODUCT_DELETED);

        return $this->redirectToRoute('homepage', ['filter' => 'my']);
    }

    /**
     * @Route("import-products", name="import-products")
     * @Method("POST")
     *
     * This method obtains products from .csv and add to a bd
     * @param Request $request
     * @return Response
     */
    public function importFromCSVAction(Request $request)
    {
        $message = "";

        if ($request->isXmlHttpRequest())
        {
            $file = new CSVFile();
            $form = $this->createForm(CSVFileType::class, $file);
            $form->handleRequest($request);

            if ($request->getMethod() == 'POST')
            {
                if ($form->isValid())
                {
                    $csvFile = $file->getCsvFile();
                    if (($handle = fopen($csvFile, "r")) !== FALSE)
                    {

                        $listProducts = [];
                        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
                        {
                            $product = new Product();
                            $parsedProduct = explode(";", $data[0]);
                            $product->setName($parsedProduct[0]);
                            $product->setPrice($parsedProduct[1]);
                            $product->setDescription($parsedProduct[2]);
                            $product->setUser($this->getUser());
                            array_push($listProducts, $product);
                        }

                        fclose($handle);

                        $this->dbService->saveData($listProducts, Events::PRODUCT_ADD, $this->getUser());
                        $message = $this->translator->trans('product.saved');
                    }
                }
                else
                {
                    $message = $this->getFormErrors($form)["csvFile"][0];
                }
            }
        }

        return new Response($this->serializer->serialize($message, 'json'));
    }

    protected function getFormErrors(Form $form)
    {
        $errors = array();

        // Global
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }

        // Fields
        foreach ($form as $child) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $errors[$child->getName()][] = $error->getMessage();
                }
            }
        }

        return $errors;
    }



}