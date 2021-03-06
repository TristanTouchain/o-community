<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

class IndexAdminController extends AbstractController
{
    /**
     * @Route("/", name="admin_index", methods="GET")
     */
    public function index(): Response
    {
        return $this->render('admin/index/index.html.twig');
    }
}