<?php

/**
 * Category Controller.
 */

namespace App\Controller;

use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Service\CategoryServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 *  Class CategoryController.
 */
#[IsGranted('ROLE_ADMIN')]
#[Route('/category', name: 'category_')]
class CategoryController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param CategoryServiceInterface $categoryService Category Service
     * @param TranslatorInterface      $translator      Translation Interface
     */
    public function __construct(private readonly CategoryServiceInterface $categoryService, private readonly TranslatorInterface $translator)
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

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Update Action.
     *
     * @param Request  $request  HTTP Request
     * @param Category $category Category Entity
     *
     * @return Response HTTP Response
     */
    #[Route('/update/{id}', name: 'update', requirements: ['id' => '[1-9][0-9]*'], methods: ['GET|PUT'])]
    public function update(Request $request, Category $category): Response
    {
        $form = $this->createForm(
            CategoryType::class,
            $category,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('category_update', ['id' => $category->getId()]),
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/update.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * Delete Action.
     *
     * @param Request  $request  HTTP Request
     * @param Category $category Category Entity
     *
     * @return Response HTTP Response
     */
    #[Route('/delete/{id}', name: 'delete', requirements: ['id' => '[1-9][0-9]*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Category $category): Response
    {
        $form = $this->createForm(FormType::class, $category, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('category_delete', ['id' => $category->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->delete($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/delete.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }
}
