<?php

/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\ChangePasswordType;
use App\Form\Type\UserType;
use App\Service\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class UserController.
 */
#[IsGranted('IS_AUTHENTICATED_FULLY')]
#[Route('/user')]
class UserController extends AbstractController
{
    /**
     * Constructor.
     *
     * @param UserServiceInterface $userService User service
     * @param TranslatorInterface  $translator  Translation Interface
     */
    public function __construct(private readonly UserServiceInterface $userService, private readonly TranslatorInterface $translator)
    {
    }

    /**
     * View Action.
     *
     * @return Response HTTP response
     */
    #[Route('', name: 'user_view', requirements: ['id' => '[1-9]\d*'], methods: 'GET')]
    public function view(): Response
    {
        $user = $this->getUser();

        return $this->render(
            'user/view.html.twig',
            ['user' => $user]
        );
    }

    /**
     * Update Action.
     *
     * @param Request $request HTTP request
     * @param User    $user    User entity
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/update', name: 'user_update', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function update(Request $request, User $user): Response
    {
        $form = $this->createForm(
            UserType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('user_update', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.updated_successfully')
            );

            return $this->redirectToRoute('user_view');
        }

        return $this->render(
            'user/update.html.twig',
            ['form' => $form->createView(), 'user' => $user]
        );
    }

    /**
     * Change Password Action.
     *
     * @param Request $request HTTP Request
     * @param User    $user    User entity
     *
     * @return Response HTTP Response
     */
    #[Route('/{id}/password-update', name: 'password_update', requirements: ['id' => '[1-9][0-9]*'], methods: 'GET|PUT')]
    public function updatePassword(Request $request, User $user): Response
    {
        $form = $this->createForm(
            ChangePasswordType::class,
            $user,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('password_update', ['id' => $user->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $user->getPassword();

            $this->userService->changePassword($user, $newPassword);
            $this->userService->save($user);

            $this->addFlash(
                'success',
                $this->translator->trans('message.password_changed_successfully')
            );

            return $this->redirectToRoute('user_view');
        }

        return $this->render(
            'user/update_password.html.twig',
            ['form' => $form->createView(), 'user' => $user]
        );
    }

    //    /**
    //     * Delete action.
    //     *
    //     * @param Request $request HTTP request
    //     * @param User    $user    User Entity
    //     *
    //     * @return Response HTTP response
    //     */
    //    #[Route(
    //        '/{id}/delete',
    //        name: 'user_delete',
    //        requirements: ['id' => '[1-9]\d*'],
    //        methods: 'GET|DELETE'
    //    )]
    //    public function delete(Request $request, User $user): Response
    //    {
    //        $form = $this->createForm(
    //            FormType::class,
    //            $user,
    //            [
    //                'method' => 'DELETE',
    //                'action' => $this->generateUrl('user_delete', ['id' => $user->getId()]),
    //            ]
    //        );
    //        $form->handleRequest($request);
    //
    //        if ($form->isSubmitted() && $form->isValid()) {
    //            $this->addFlash(
    //                'success',
    //                $this->translator->trans('message.deleted_successfully')
    //            );
    //
    //            if ($user === $this->getUser()) {
    //                $this->userService->delete($user);
    //                $request->getSession()->invalidate();
    //                $this->container->get('security.token_storage')->setToken(null);
    //
    //                return $this->redirectToRoute('app_login');
    //            }
    //        }
    //
    //        return $this->render(
    //            'user/delete.html.twig',
    //            ['form' => $form->createView(), 'user' => $user]
    //        );
    //    }
}
