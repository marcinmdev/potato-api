<?php

namespace App\Story;

use App\Factory\IngredientFactory;
use Zenstruck\Foundry\Story;

final class IngredientStory extends Story
{
    public const INGREDIENT_POTATO = 'potato';
    public const INGREDIENT_ONION = 'onion';
    public const INGREDIENT_CABBAGE = 'cabbage';

    public function build(): void
    {
        foreach ($this->getIngredientData() as $ingredientData) {
            IngredientFactory::createOne($ingredientData);
        }
    }

    /**
     * @return array{array{name: string, price: int, weight: int}}
     */
    private function getIngredientData(): array
    {
        return [
            ['name' => self::INGREDIENT_POTATO, 'price' => 2000, 'weight' => 5],
            ['name' => self::INGREDIENT_ONION, 'price' => 1000, 'weight' => 3],
            ['name' => self::INGREDIENT_CABBAGE, 'price' => 500, 'weight' => 2],
        ];
    }
}
