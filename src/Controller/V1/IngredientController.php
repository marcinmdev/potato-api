<?php

namespace App\Controller\V1;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredient')]
class IngredientController extends AbstractController
{
    #[OA\Response(
        response: 200,
        description: 'Returns all ingredients',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Ingredient::class, groups: ['index'])),
            minItems: 2
        )
    )]
    #[OA\Tag(name: 'ingredients')]
    #[Route('/', name: 'app_ingredient_index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository): JsonResponse
    {
        $ingredients = $ingredientRepository->findAll();

        return $this->json(
            data: $ingredients,
            status: Response::HTTP_OK,
            context: [
                'groups' => ['index'],
            ]
        );
    }

    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: IngredientType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Creates an ingredient and returns it',
        content: new OA\JsonContent(
            ref: new Model(type: Ingredient::class, groups: ['details'])
        )
    )]
    #[OA\Tag(name: 'ingredients')]
    #[Route('/', name: 'app_ingredient_create', methods: ['POST'])]
    public function create(IngredientRepository $ingredientRepository, Request $request): JsonResponse
    {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientType::class, $ingredient);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $ingredientRepository->add($ingredient);

            return $this->json(
                data: $ingredient,
                status: Response::HTTP_CREATED,
                context: [
                    'groups' => ['details'],
                ]
            );
        }

        return $this->json(
            data: [],
            status: Response::HTTP_BAD_REQUEST,
        );
    }
}
