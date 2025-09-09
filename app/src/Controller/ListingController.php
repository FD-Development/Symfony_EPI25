<?php

/**
 * Listing Controller.
 */

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Service\ListingServiceInterface;
use App\Service\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;

/**
 *  @class ListingController.
 */
#[Route('/')]
class ListingController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param ListingServiceInterface  $listingService  Listing Service
     * @param CategoryServiceInterface $categoryService Category Service
     */
    public function __construct(private readonly ListingServiceInterface $listingService, private readonly CategoryServiceInterface $categoryService)
    {
    }

    /**
     * Index Action.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Category Id
     *
     * @return Response HTTP response
     */
    #[Route('', name: 'listings_index', methods: ['GET'])]
    public function index(#[MapQueryParameter] int $page = 1, #[MapQueryParameter('categoryId')] ?int $categoryId = null): Response
    {

        $pagination = $this->listingService->getPaginatedListings($page, $categoryId);
        $categories = $this->categoryService->getAll();


        return $this->render(
            'listing/index.html.twig',
            [
                'listings' => $pagination,
                'categories' => $categories,
            ]
        );
    }

    /**
     * View Action.
     *
     * @param int $listingId Id number
     *
     * @return Response HTTP response
     */
    #[Route('/listing/{listingId}', name: 'listings_view', requirements: ['listingId' => '[1-9][0-9]*'], methods: ['GET'])]
    public function view(int $listingId): Response
    {
        $listing = $this->listingService->getOne($listingId);

        return $this->render(
            'listing/view.html.twig',
            ['listing' => $listing]
        );
    }

}
