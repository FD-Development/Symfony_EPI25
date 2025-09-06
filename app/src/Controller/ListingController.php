<?php

/**
 * Listing Controller.
 */

namespace App\Controller;

use App\Service\ListingServiceInterface;
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
     * @param ListingServiceInterface $listingService Listing Service
     */
    public function __construct(private readonly ListingServiceInterface $listingService)
    {
    }

    /**
     * Index Action.
     *
     * @param int $page Page number
     *
     * @return Response HTTP response
     */
    #[Route('', name: 'listings_index', methods: ['GET'])]
    public function index(#[MapQueryParameter] int $page = 1): Response
    {

        $pagination = $this->listingService->getPaginatedListings($page);

        return $this->render(
            'listing/index.html.twig',
            ['listings' => $pagination]
        );
    }
}
