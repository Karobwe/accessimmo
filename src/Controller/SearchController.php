<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Repository\HousingRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     */
    public function index()
    {
        return $this->render('search/index.html.twig', [
            'controller_name' => 'SearchController',
        ]);
    }

    public function searchBar()
    {
        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('results'))
            ->add('query', TextType::class)
//            ->add('submit', SubmitType::class)
            ->getForm();

        return $this->render(
            'search/searchBar.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * @Route("/results", name="results")
     * @param Request $request
     */
    public function handleSearch(Request $request, HousingRepository $housingRepository, PaginatorInterface $paginator)
    {
        $query = $request->request->get('form')['query'];

        $housingRepository = $this->getDoctrine()->getRepository(Housing::class);

        $requestedPage = $request->query->getInt('page', 1);
        $query = $housingRepository->findByTerm('Dijon');
        $houseList = $paginator->paginate($query, $requestedPage, 10);

        return $this->render(
            'search/results.html.twig',
            ['results' => $houseList]
        );
    }
}
