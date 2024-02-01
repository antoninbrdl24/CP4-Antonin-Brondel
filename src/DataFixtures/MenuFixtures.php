<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\StarterFixtures;
use App\DataFixtures\MealFixtures;
use App\DataFixtures\DessertFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Starter;
use App\Entity\Meal;
use App\Entity\Dessert;
use App\Entity\Menu;

class MenuFixtures extends Fixture
{
    public const MENU = [
        ['title' => 'Tradition',
        'description' => 'Découvrez la quintessence de la cuisine française avec notre menu 
        qui célèbre des plats emblématiques, concoctés selon les traditions culinaires séculaires.'],

        ['title' => 'Méditérranée',
        'description' => 'Explorez les saveurs envoûtantes de la Méditerranée à travers notre menu, 
        où chaque bouchée raconte une histoire de soleil, d\'herbes aromatiques et d\'authenticité.'],

        ['title' => 'Provence',
        'description' => 'Plongez dans l\'élégance de la Provence avec notre menu, une délicieuse escapade 
        gastronomique qui capture l\'esprit de la région à travers des saveurs raffinées et équilibrées.'],

    ];

    public function load(ObjectManager $manager): void
    {

        foreach (self::MENU as $menuData) {
            $menu = new Menu();
            $menu->setTitle($menuData['title']);
            $menu->setDescription($menuData['description']);

            $this->addReference('menu_' . $menuData['title'], $menu);
            $manager->persist($menu);
        }

        $manager->flush();
    }
}
