<?php

namespace App\Controller\V1;

use App\Entity\Meal;
use App\Form\MealType;
use App\Repository\MealRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

#[Route('/meal')]
class MealController extends AbstractController
{
    #[OA\Response(
        response: 200,
        description: 'Returns all meals',
        content: new OA\JsonContent(
            type: 'array',
            items: new OA\Items(ref: new Model(type: Meal::class, groups: ['index'])),
            minItems: 2
        )
    )]
    #[OA\Tag(name: 'meal')]
    #[Route('/', name: 'app_meal_index', methods: ['GET'])]
    public function index(MealRepository $mealRepository): JsonResponse
    {
        $meals = $mealRepository->findAll();

        return $this->json(
            data: $meals,
            status: Response::HTTP_OK,
            context: [
                'groups' => ['index'],
            ]
        );
    }

    #[OA\Response(
        response: 200,
        description: 'Returns meal',
        content: new OA\JsonContent(ref: new Model(type: Meal::class, groups: ['details']))
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found',
        content: new OA\JsonContent(type: 'object')
    )]
    #[OA\Tag(name: 'meal')]
    #[Route('/{id}', name: 'app_meal_get', methods: ['GET'])]
    public function get(MealRepository $mealRepository, int $id): JsonResponse
    {
        $meal = $mealRepository->find($id);

        if ($meal) {
            return $this->json(
                data: $meal,
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

    #[OA\RequestBody(required: true, content: new OA\JsonContent(ref: new Model(type: MealType::class)))]
    #[OA\Response(
        response: 200,
        description: 'Creates a meal and returns it',
        content: new OA\JsonContent(
            ref: new Model(type: Meal::class, groups: ['details'])
        )
    )]
    #[OA\Response(
        response: 400,
        description: 'Bad request',
        content: new OA\JsonContent(type: 'object')
    )]
    #[OA\Tag(name: 'meal')]
    #[Route('/', name: 'app_meal_create', methods: ['POST'])]
    public function create(MealRepository $mealRepository, Request $request): JsonResponse
    {
        $meal = new Meal();

        $form = $this->createForm(MealType::class, $meal);

        try {
            $form->submit((array) json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR));
        } catch (Throwable $e) {
            return $this->json(
                data: $e->getMessage(),
                status: Response::HTTP_BAD_REQUEST,
            );
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $mealRepository->add($meal);

            return $this->json(
                data: $meal,
                status: Response::HTTP_CREATED,
                context: [
                    'groups' => ['details'],
                ]
            );
        }

        $delme = $form->getErrors(true, false);

        return $this->json(
            data: [],
            status: Response::HTTP_BAD_REQUEST,
        );
    }

    #[OA\Response(
        response: 204,
        description: 'Deletes meal',
        content: new OA\JsonContent(type: 'object')
    )]
    #[OA\Response(
        response: 404,
        description: 'Not found',
        content: new OA\JsonContent(type: 'object')
    )]
    #[OA\Tag(name: 'meal')]
    #[Route('/{id}', name: 'app_meal_delete', methods: ['DELETE'])]
    public function delete(MealRepository $mealRepository, int $id): JsonResponse
    {
        $meal = $mealRepository->find($id);

        if ($meal) {
            $mealRepository->remove($meal);

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
