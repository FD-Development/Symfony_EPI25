<?php

/**
 * Category Controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Service\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

/**
 *  @class CategoryController.
 */
#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService Category Service
     */
    public function __construct(private readonly CategoryServiceInterface $categoryService)
    {
    }

    /**
     * Action Index.
     *
     * @return Response HTTP Response
     */
    #[Route('', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $categories = $this->categoryService->getAll(); // For now

        return $this->render(
            'category/index.html.twig',
            [
                'categories' => $categories,
            ]
        );
    }

    /**
     * Action Create.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route('/create', name: 'create', methods: ['GET|POST'])]
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/create.html.twig',
            ['form' => $form->createView()]
        );
    }
}
