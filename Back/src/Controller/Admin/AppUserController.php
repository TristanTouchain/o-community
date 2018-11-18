<?php

namespace App\Controller\Admin;

use App\Entity\AppUser;
use App\Form\AppUserType;
use App\Repository\AppUserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/user")
 */
class AppUserController extends AbstractController
{
    /**
     * @Route("/", name="app_user_index", methods="GET")
     */
    public function index(AppUserRepository $appUserRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $appUserRepository->findAll();
        $pagination = $paginator->paginate(
        $query, /* query NOT result */
        $request->query->getInt('page', 1)/*page number*/,
        4/*limit per page*/
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
        }

        return $this->redirectToRoute('app_user_index');
    }
}
