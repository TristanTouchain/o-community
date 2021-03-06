<?php

namespace App\Controller\Admin;

use App\Entity\AppUser;
use App\Form\AppUserType;
use App\Repository\AppUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Knp\Component\Pager\PaginatorInterface;
use App\Utils\GeneratePassword;
use App\Utils\SendMail;

/**
 * @Route("/user")
 */
class AppUserController extends AbstractController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="app_user_index", methods="GET")
     */
    public function index(AppUserRepository $appUserRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $appUserRepository->findAll();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            15/*limit per page*/
        );
        return $this->render('admin/app_user/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/new", name="app_user_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $appUser = new AppUser();
        $form = $this->createForm(AppUserType::class, $appUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($appUser);
            $em->flush();

            $this->addFlash(
                'success',
                'L\'utilisateur ' . $appUser->__toString() . ' a été ajouté'
            );

            return $this->redirectToRoute('app_user_index');
        }

        return $this->render('admin/app_user/new.html.twig', [
            'app_user' => $appUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_show", methods="GET")
     */
    public function show(AppUser $appUser): Response
    {
        return $this->render('admin/app_user/show.html.twig', ['app_user' => $appUser]);
    }

    /**
     * @Route("/{id}/edit", name="app_user_edit", methods="GET|POST")
     */
    public function edit(Request $request, AppUser $appUser): Response
    {
        $form = $this->createForm(AppUserType::class, $appUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'L\' utilisateur ' . $appUser->__toString() . ' a été modifié'
            );

            // Prevent the serialization of the file property
            $appUser->file = null;

            return $this->redirectToRoute('app_user_index', ['id' => $appUser->getId()]);
        }

        return $this->render('admin/app_user/edit.html.twig', [
            'app_user' => $appUser,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_user_delete", methods="DELETE")
     */
    public function delete(Request $request, AppUser $appUser): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appUser->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($appUser);
            $em->flush();

            $this->addFlash(
                'danger',
                'L\' utilisateur ' . $appUser->__toString() . ' a été supprimé'
            );

        }


        return $this->redirectToRoute('app_user_index');
    }

    /**
     * @Route("/{id}/reset-password", name="app_user_password", methods="POST")
     */
    public function resetPassword(Request $request, AppUser $appUser, GeneratePassword $passwordFactory, SendMail $mailGenerator): Response
    {
        if ($this->isCsrfTokenValid('reset-password'.$appUser->getId(), $request->request->get('_token'))) {
            $appUser->setPassword($passwordFactory->generate());
            $em = $this->getDoctrine()->getManager();
            $mailGenerator->resetPassword($appUser);
            $encodedPassword = $this->passwordEncoder->encodePassword($appUser, $appUser->getPassword());
            $appUser->setPassword($encodedPassword);
            $em->flush();
                $this->addFlash(
                    'success',
                    'Le mot de passe de l\'utilisateur ' . $appUser->__toString() . ' a été réinitialisé'
                );
        }

        return $this->redirectToRoute('app_user_index');
    }

    /**
     * @Route("/{id}/moderate", name="app_user_moderate", methods="POST")
     */
    public function moderate(Request $request, AppUser $appUser): Response
    {
        if ($this->isCsrfTokenValid('moderate'.$appUser->getId(), $request->request->get('_token'))) {
            if($appUser->getIsActive()) {
                $appUser->setIsActive(false);
            } else {
                $appUser->setIsActive(true);
            }
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            if($appUser->getIsActive()) {
                $this->addFlash(
                    'success',
                    'L\'utilisateur ' . $appUser->__toString() . ' a été activé'
                );
            } else {
                $this->addFlash(
                    'warning',
                    'L\'utilisateur ' . $appUser->__toString() . ' a été désactivé'
                );
            }
        }

        return $this->redirectToRoute('app_user_index');
    }
}
