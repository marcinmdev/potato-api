<?php

namespace App\Controller\V1;

use App\Entity\Ingredient;
use App\Form\FilterType;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/ingredient')]
class IngredientController extends AbstractController
{
    #[OA\Response(
        response: 200,
        description: 'Returns ingredients',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Ingredient::class, groups: ['index'])),
            minItems: 2
        )
    )]
    #[OA\Parameter(name: 'limit', description: 'No higher than 20', in: 'query', schema: new OA\Schema(type: 'int'))]
    #[OA\Parameter(name: 'offset', in: 'query', schema: new OA\Schema(type: 'int'))]
    #[OA\Tag(name: 'ingredient')]
    #[Route('/', name: 'app_ingredient_index', methods: ['GET'])]
    public function index(IngredientRepository $ingredientRepository, Request $request): JsonResponse
    {
        $form = $this->createForm(FilterType::class);
        $form->submit($request->query->all());

        if ($form->isValid()) {
            /** @var array{limit: int, offset: int} $filterData */
            $filterData = (array) $form->getData();

            $filteredIngredients = $ingredientRepository->filter($filterData);

            return $this->json(
                data: $filteredIngredients,
                status: Response::HTTP_OK,
                context: [
                    'groups' => ['index'],
                ]
            );
        }

        return $this->json(
            data: [],
            status: Response::HTTP_BAD_REQUEST,
        );
    }

    #[OA\Response(
        response: 200,
        description: 'Returns ingredient',
        content: new OA\JsonContent(ref: new Model(type: Ingredient::class, groups: ['details']))
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found',
        content: new OA\JsonContent(type: 'object')
    )]
    #[OA\Tag(name: 'ingredient')]
    #[Route('/{id}', name: 'app_ingredient_get', methods: ['GET'])]
    public function get(IngredientRepository $ingredientRepository, int $id): JsonResponse
    {
        $ingredient = $ingredientRepository->find($id);

        if ($ingredient) {
            return $this->json(
                data: $ingredient,
                status: Response::HTTP_OK,
                context: [
                    'groups' => ['details'],
                ]
            );
        }

        return $this->json(
            data: [],
            status: Response::HTTP_NOT_FOUND,
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
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(type: 'object')
    )]
    #[OA\Tag(name: 'ingredient')]
    #[Route('/', name: 'app_ingredient_create', methods: ['POST'])]
    public function create(IngredientRepository $ingredientRepository, Request $request): JsonResponse
    {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientType::class, $ingredient);

        try {
            $form->submit((array) json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (Throwable $e) {
            return $this->json(
                data: $e->getMessage(),
                status: Response::HTTP_BAD_REQUEST,
            );
        }

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

    #[OA\Response(
        response: 204,
        description: 'Deletes ingredient',
        content: new OA\JsonContent(type: 'object')
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found',
        content: new OA\JsonContent(type: 'object')
    )]
    #[OA\Tag(name: 'ingredient')]
    #[Route('/{id}', name: 'app_ingredient_delete', methods: ['DELETE'])]
    public function delete(IngredientRepository $ingredientRepository, int $id): JsonResponse
    {
        $ingredient = $ingredientRepository->find($id);

        if ($ingredient) {
            $ingredientRepository->remove($ingredient);

            return $this->json(
                data: [],
                status: Response::HTTP_NO_CONTENT,
            );
        }

        return $this->json(
            data: [],
            status: Response::HTTP_NOT_FOUND,
        );
    }
}
