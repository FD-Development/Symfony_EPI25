<?php

/**
 * Listing Controller.
 */

namespace App\Controller;

use App\Service\ListingService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 *  @class ListingController.
 */
#[Route('/')]
class ListingController extends AbstractController
{
    /**
     * Constructor.
     */
    public function __construct(private ListingService $listingService)
    {
    }

    /**
     * Index Action.
     *
     * @return Response HTTP response
     */
    #[Route('', name: 'listings_index', methods: ['GET'])]
    public function index(): Response
    {
        $listing = $this->listingService->getListings();

        return $this->render(
            'listing/index.html.twig',
            ['listings' => $listing]
        );
    }
}
