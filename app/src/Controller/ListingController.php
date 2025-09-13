<?php

/**
 * Listing Controller.
 */

namespace App\Controller;

use App\Entity\Listing;
use App\Form\Type\ListingType;
use App\Service\CategoryServiceInterface;
use App\Service\ListingServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

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
     * @param TranslatorInterface      $translator      Translation Interface
     */
    public function __construct(private readonly ListingServiceInterface $listingService, private readonly CategoryServiceInterface $categoryService, private readonly TranslatorInterface $translator)
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
    #[Route('', name: 'listing_index', methods: ['GET'])]
    public function index(#[MapQueryParameter] int $page = 1, #[MapQueryParameter('categoryId')] ?int $categoryId = null): Response
    {
        $pagination = $this->listingService->getActivatedPaginatedListings($page, $categoryId);
        $categories = $this->categoryService->getAll();

        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = $this->categoryService->getOne($categoryId);
        }

        return $this->render(
            'listing/index.html.twig',
            [
                'listings' => $pagination,
                'categories' => $categories,
                'selectedCategory' => $selectedCategory,
            ]
        );
    }

    /**
     * Activate Index Action.
     *
     * @param int      $page       Page number
     * @param int|null $categoryId Category Id
     *
     * @return Response HTTP response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/activate', name: 'listing_index_activate', methods: ['GET'])]
    public function indexActivate(#[MapQueryParameter] int $page = 1, #[MapQueryParameter('categoryId')] ?int $categoryId = null): Response
    {
        $pagination = $this->listingService->getNonActivatedPaginatedListings($page, $categoryId);
        $categories = $this->categoryService->getAll();

        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = $this->categoryService->getOne($categoryId);
        }

        return $this->render(
            'listing/index_activate.html.twig',
            [
                'listings' => $pagination,
                'categories' => $categories,
                'selectedCategory' => $selectedCategory,
            ]
        );
    }

    /**
     * View Action.
     *
     * @param int $id Listing id number
     *
     * @return Response HTTP Response
     */
    #[Route('/listing/{id}', name: 'listing_view', requirements: ['id' => '[1-9][0-9]*'], methods: ['GET'])]
    public function view(int $id): Response
    {
        $listing = $this->listingService->getOne($id);

        return $this->render(
            'listing/view.html.twig',
            ['listing' => $listing]
        );
    }

    /**
     * Create Action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP Response
     */
    #[Route('/listing/create', name: 'listing_create', methods: ['GET|POST'])]
    public function create(Request $request): Response
    {
        $listing = new Listing();
        $form = $this->createForm(ListingType::class, $listing);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->listingService->save($listing);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('listing_index');
        }

        return $this->render(
            'listing/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Update Action.
     *
     * @param Request $request HTTP Request
     * @param Listing $listing Listing Entity
     *
     * @return Response HTTP Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/listing/update/{id}', name: 'listing_update', requirements: ['id' => '[1-9][0-9]*'], methods: ['GET|PUT'])]
    public function update(Request $request, Listing $listing): Response
    {
        $form = $this->createForm(
            ListingType::class,
            $listing,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('listing_update', ['id' => $listing->getId()]),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->listingService->save($listing);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('listing_view', ['id' => $listing->getId()]);
        }

        return $this->render(
            'listing/update.html.twig',
            [
                'form' => $form->createView(),
                'listing' => $listing,
            ]
        );
    }

    /**
     * Delete Action.
     *
     * @param Request $request HTTP Request
     * @param Listing $listing Listing Entity
     *
     * @return Response HTTP Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('listing/delete/{id}', name: 'listing_delete', requirements: ['id' => '[1-9][0-9]*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Listing $listing): Response
    {
        $form = $this->createForm(FormType::class, $listing, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('listing_delete', ['id' => $listing->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->listingService->delete($listing);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('listing_index');
        }

        return $this->render(
            'listing/delete.html.twig',
            [
                'form' => $form->createView(),
                'listing' => $listing,
            ]
        );
    }

    /**
     * Activate Action.
     *
     * @param Request $request HTTP Request
     * @param Listing $listing Listing Entity
     *
     * @return Response HTTP Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('listing/activate/{id}', name: 'listing_activate', requirements: ['id' => '[1-9][0-9]*'], methods: 'GET|POST')]
    public function activate(Request $request, Listing $listing): Response
    {
        $form = $this->createFormBuilder($listing, [
            'method' => 'POST',
            'action' => $this->generateUrl('listing_activate', ['id' => $listing->getId()]),
        ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->listingService->activate($listing);
            $this->listingService->save($listing);

            $this->addFlash(
                'success',
                $this->translator->trans('message.activated_successfully')
            );

            return $this->redirectToRoute('listing_view', ['id' => $listing->getId()]);
        }

        return $this->render('listing/activate.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
