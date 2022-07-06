<?php

namespace App\Story;

use Zenstruck\Foundry\Story;

final class GlobalStory extends Story
{
    public function build(): void
    {
        UserAccountStory::load();
        IngredientStory::load();
        MealStory::load();
    }
}
