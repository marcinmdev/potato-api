<?php

namespace App\Controller\V1;

use App\Entity\Ingredient;
use App\Repository\IngredientRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ingredient')]
#[OA\Response(
    response: 200,
    description: 'Returns the rewards of an user',
    content: new OA\JsonContent(
        type: 'array',
        items: new OA\Items(ref: new Model(type: Ingredient::class, groups: ['index']))
    )
)]
#[OA\Tag(name: 'ingredients')]
class IngredientController extends AbstractController
{
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
}
