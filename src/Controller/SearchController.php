<?php

namespace App\Controller;

use App\Entity\Housing;
use App\Entity\Status;
use App\Entity\Type;
use App\Repository\HousingRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
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
            ->add('keywords', TextType::class)
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
        $keywords = $request->request->get('form')['keywords'];

        $housingRepository = $this->getDoctrine()->getRepository(Housing::class);

        $requestedPage = $request->query->getInt('page', 1);
        $query = $housingRepository->findByTerm($keywords);
        $houseList = $paginator->paginate($query, $requestedPage, 5);

        return $this->render(
            'search/results.html.twig',
            ['results' => $houseList]
        );
    }

    public function advancedSearch()
    {
        $allTypes = $this->getDoctrine()->getRepository(Type::class)->findAll();
        $allStatus = $this->getDoctrine()->getRepository(Status::class)->findAll();

        return $this->render(
            'search/advanced-search.html.twig',
            [
                'allTypes' => $allTypes,
                'allStatus' => $allStatus
            ]
        );
    }

    /**
     * @Route("/advanced-search", name="advanced-search")
     * @param Request $request
     */
    public function handleAdvancedSearch(Request $request, PaginatorInterface $paginator)
    {
        return $this->render(
            'search/results.html.twig'
        );
    }

    public function legacySearch()
    {
        $allStatus = $this->getDoctrine()->getRepository(Status::class)->findAll();
        $statusArray = array('acheter, louer...' => null);
        foreach ($allStatus as $status) {
            $statusArray[$status->getName()] = $status->getId();
        }

        $allTypes = $this->getDoctrine()->getRepository(Type::class)->findAll();
        $typesArray = array('maison, appartement...' => null);
        foreach ($allTypes as $type) {
            $typesArray[$type->getName()] = $type->getId();
        }

        $form = $this->createFormBuilder(null)
            ->setAction($this->generateUrl('results'))
            ->add(
                'keywords',
                TextType::class,
                [
                    'label' => 'Mots clés',
                    'attr' => [
                        'placeholder' => 'maison, appartement, dijon, auxerre...',
                        'class' => 'col-12 form-control form-control-lg',
                        'required' => false
                    ],
                    'label_attr' => [
                        'class' => 'col-12 pl-0'
                    ]
                ]
            )
            ->add(
                'sizes',
                ChoiceType::class,
                [
                    'label' => 'Quel type de logement désirez vous ?',
                    'choices' => [
                        "Studio" => 1,
                        "T2" => 2,
                        "T3" => 3,
                        "T4" => 4,
                        "T5" => 5,
                        "T6+" => 6
                    ],
                    'expanded' => true,
                    'multiple' => true
                ]
            )
            ->add(
                'types',
                ChoiceType::class,
                [
                    'label' => 'Vous êtes intéréssés par un(e)',
                    'choices' => $typesArray
                ]
            )
            ->add(
                'status',
                ChoiceType::class,
                [
                    'label' => 'Vous voulez',
                    'choices' => $statusArray
                ]
            )
            ->add(
                'minPrice',
                TextType::class,
                [
                    'label' => 'Min',
                    'attr' => [
                        'class' => 'form-control',
                        'required' => false
                    ],
                ]
            )
            ->add(
                'maxPrice',
                TextType::class,
                [
                    'label' => 'Max',
                    'attr' => [
                        'class' => 'form-control',
                        'required' => false
                    ],
                ]
            )
            ->add(
                'minArea',
                TextType::class,
                [
                    'label' => 'Min',
                    'attr' => [
                        'class' => 'form-control',
                        'required' => false
                    ],
                ]
            )
            ->add(
                'maxArea',
                TextType::class,
                [
                    'label' => 'Max',
                    'attr' => [
                        'class' => 'form-control',
                        'required' => false
                    ],
                ]
            )
            ->getForm();

        return $this->render(
            'search/legacy-search.html.twig',
            [
                'form' => $form->createView(),
                'allTypes' => $allTypes,
                'allStatus' => $allStatus
            ]
        );
    }

    /**
     * @Route("/legacy-search", name="results")
     * @param Request $request
     */
    public function handleLegacySearch(Request $request, PaginatorInterface $paginator)
    {
        $formData = $request->request->get('form');
        $allTypes = $this->getDoctrine()->getRepository(Type::class)->findAll();
        $allStatus = $this->getDoctrine()->getRepository(Status::class)->findAll();

        // valeurs par défaut
        $queryData = array(
            'keywords' => [],
            'typeId' => null,
            'statusId' => null,
            'area' => [
                'min' => 0,
                'max' => PHP_INT_MAX
,            ],
            'price' => [
                'min' => 0,
                'max' => PHP_INT_MAX
            ],
            'sizes' => [1, 2, 3, 4, 5, 6]
        );

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Keywords ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

        if(isset($formData['keywords'])) {
            $queryData['keywords'] = explode(' ', trim($formData['keywords']));
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Type ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

        if(isset($formData['types'])) {
            $typeExist = false;
            foreach ($allTypes as $type) {
                if($type->getId() == $formData['types']) {
                    $typeExist = true;
                }
            }

            if($typeExist) {
                $queryData['typeId'] = $formData['types'];
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Status ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

        if(isset($formData['status'])) {
            $statusExist = false;
            foreach ($allStatus as $status) {
                if($status->getId() == $formData['status']) {
                    $statusExist = true;
                }
            }

            if($statusExist) {
                $queryData['statusId'] = $formData['status'];
            }
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Price ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

        if(isset($formData['minPrice'])) {
            $min = $formData['minPrice'];
            if($min < 0) {
                $min = 0;
            }

            if(isset($formData['maxPrice'])) {
                $max = $formData['maxPrice'];
                if($min > $max) {
                    $min = $max;
                }
            }

            $queryData['price']['min'] = $min;
        }

        if(isset($formData['maxPrice'])) {
            $max = $formData['maxPrice'];
            if($max < 0) {
                $max = PHP_INT_MAX;
            }

            if(isset($formData['minPrice'])) {
                $min = $formData['minPrice'];
                if($max < $min) {
                    if ($min < 0) {
                        $min = 0;
                    }
                    $max = PHP_INT_MAX;
                }
            }

            $queryData['price']['max'] = $max;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Area ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //
        if(isset($formData['minArea'])) {
            $min = $formData['minArea'];
            if($min < 0) {
                $min = 0;
            }

            if(isset($formData['maxArea'])) {
                $max = $formData['maxArea'];
                if($min > $max) {
                    $min = $max;
                }
            }

            $queryData['area']['min'] = $min;
        }

        if(isset($formData['maxArea'])) {
            $max = $formData['maxArea'];
            if($max < 0) {
                $max = PHP_INT_MAX;
            }

            if(isset($formData['minArea'])) {
                $min = $formData['minPrice'];
                if($max < $min) {
                    if ($min < 0) {
                        $min = 0;
                    }
                    $max = PHP_INT_MAX;
                }
            }

            $queryData['area']['max'] = $max;
        }

        // ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ Size ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ //

        if(isset($formData['sizes'])) {
            $sizes = $formData['sizes'];
            // Si le tableaux n'est pas vide, on enlève les valeurs par défauts
            if(count($sizes) > 0) {
                $queryData['sizes'] = array();
            }
            foreach ($sizes as $key => $value) {
                array_push($queryData['sizes'], intval($value));
            }
        }

        $housingRepository = $this->getDoctrine()->getRepository(Housing::class);

        $requestedPage = $request->query->getInt('page', 1);
        $query = $housingRepository->advancedSearch($queryData);
        $houseList = $paginator->paginate($query, $requestedPage, 5);

        return $this->render(
            'search/results.html.twig',
            ['results' => $houseList]
        );
    }
}
