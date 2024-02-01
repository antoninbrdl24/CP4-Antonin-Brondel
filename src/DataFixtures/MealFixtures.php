<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Meal;
use App\Entity\Menu;

class MealFixtures extends Fixture implements DependentFixtureInterface
{
    public const MEAL = [
        ['name' => 'Coq au Vin',
        'description' => 'Laissez-vous transporter par le goût riche et profond du Coq au Vin. Un plat traditionnel 
        français où le poulet mijote lentement dans un mélange succulent de vin rouge, de champignons, d\'oignons 
        et d\'herbes aromatiques, créant une symphonie de saveurs qui fond dans la bouche.',
        'Menu' => 'menu_Tradition',
        'picture' => 'coq_au_vin.png'],

        ['name' => 'Ratatouille',
        'description' => 'Plongez dans la magie méditerranéenne avec la Ratatouille, un assortiment coloré de 
        légumes frais mijotés dans une sauce tomate parfumée aux herbes de Provence. Chaque bouchée est une 
        explosion de saveurs méditerranéennes.',
        'Menu' => 'menu_Méditérranée',
        'picture' => 'ratatouille.png'],

        ['name' => 'Bouillabaisse',
        'description' => 'Plongez dans les saveurs de la mer avec la Bouillabaisse, un ragoût de poissons 
        méditerranéens mijoté dans une soupe parfumée aux herbes, aux épices et au safran. Un festin qui 
        capture l\'essence même de la cuisine provençale.',
        'Menu' => 'menu_Provence',
        'picture' => 'bouillabaisse.png'],

    ];

    public function load(ObjectManager $manager): void
    {
        $uploadMealDir = '/uploads/meal';

        if (!is_dir(__DIR__ . '/../../public' . $uploadMealDir)) {
            mkdir(__DIR__ . '/../../public' . $uploadMealDir, recursive: true);
        }

        foreach (self::MEAL as $mealData) {
            copy(
                __DIR__ . '/data/meal/' . $mealData['picture'],
                __DIR__ . '/../../public' . $uploadMealDir . '/' . $mealData['picture']
            );

            $meal = new Meal();
            $meal->setName($mealData['name']);
            $meal->setDescription($mealData['description']);

            $menu = $this->getReference($mealData['Menu']);
            $meal->setMenu($menu);
            $menu->addSarter($meal);
            $meal->setPicture($mealData['picture']);

            $manager->persist($meal);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        // Tu retournes ici toutes les classes de fixtures dont ProgramFixtures dépend
        return [
          MenuFixtures::class,
        ];
    }
}
