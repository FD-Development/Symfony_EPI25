<?php

/**
 * Category Controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Service\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}
