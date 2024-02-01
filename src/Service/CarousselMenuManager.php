<?php

namespace App\Service;

use App\Entity\Menu;
use App\Entity\Starter;
use App\Entity\Meal;
use App\Entity\Dessert;
use App\Repository\MealRepository;
use App\Repository\ArtistRepository;
use App\Repository\ArtworkRepository;
use App\Repository\DessertRepository;
use App\Repository\StarterRepository;

class CarousselMenuManager
{
    public function getRandomStarter(StarterRepository $starterRepository): array
    {
        $allStarters = $starterRepository->findAll();

        $randomKeysStarter = array_rand($allStarters, 3);

        $randomStarters = [];

        foreach ($randomKeysStarter as $key) {
            $randomStarters[] = $allStarters[$key];
        }

        return $randomStarters;
    }

    public function getRandomMeal(MealRepository $mealRepository): array
    {
        $allMeals = $mealRepository->findAll();

        $randomKeysMeal = array_rand($allMeals, 3);

        $randomMeals  = [];

        foreach ($randomKeysMeal as $key) {
            $randomMeals[] = $allMeals[$key];
        }

        return $randomMeals;
    }

    public function getRandomDessert(DessertRepository $dessertRepository): array
    {
        $allDesserts = $dessertRepository->findAll();

        $randomKeysDessert = array_rand($allDesserts, 3);

        $randomDesserts  = [];

        foreach ($randomKeysDessert as $key) {
            $randomDesserts[] = $allDesserts[$key];
        }

        return $randomDesserts;
    }
}
