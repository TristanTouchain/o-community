<?php

namespace App\Controller\Admin;

use App\Entity\Promotion;
use App\Form\PromotionType;
use App\Repository\PromotionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/promotion")
 */
class PromotionController extends AbstractController
{
    /**
     * @Route("/", name="promotion_index", methods="GET")
     */
    public function index(PromotionRepository $promotionRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $query = $promotionRepository->findAll();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            8/*limit per page*/
        );
        return $this->render('admin/promotion/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * @Route("/new", name="promotion_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $promotion = new Promotion();
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($promotion);
            $em->flush();

            $this->addFlash(
                'success',
                'La promotion ' . $promotion->getName() . ' a été ajoutée'
            );            

            return $this->redirectToRoute('promotion_index');
        }

        return $this->render('admin/promotion/new.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_show", methods="GET")
     */
    public function show(Promotion $promotion): Response
    {
        return $this->render('admin/promotion/show.html.twig', ['promotion' => $promotion]);
    }

    /**
     * @Route("/{id}/edit", name="promotion_edit", methods="GET|POST")
     */
    public function edit(Request $request, Promotion $promotion): Response
    {
        $form = $this->createForm(PromotionType::class, $promotion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash(
                'success',
                'La promotion ' . $promotion->getName() . ' a été supprimée'
            );                        

            return $this->redirectToRoute('promotion_index', ['id' => $promotion->getId()]);
        }

        return $this->render('admin/promotion/edit.html.twig', [
            'promotion' => $promotion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="promotion_delete", methods="DELETE")
     */
    public function delete(Request $request, Promotion $promotion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$promotion->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($promotion);
            $em->flush();

            $this->addFlash(
                'danger',
                'La promotion ' . $promotion->getName() . ' a été supprimée'
            );            

        }

        return $this->redirectToRoute('promotion_index');
    }
}
