<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Dessert;
use App\Entity\Menu;

class DessertFixtures extends Fixture implements DependentFixtureInterface
{
    public const DESSERT = [
        ['name' => 'Crème Brûlée',
        'description' => 'Terminez votre repas en douceur avec la Crème Brûlée. Une crème veloutée et légèrement 
        vanillée, délicieusement caramélisée à la perfection en surface. La cassure du caramel croquant révèle 
        une texture onctueuse et une explosion de saveurs qui raviront vos papilles.',
        'Menu' => 'menu_Tradition',
        'picture' => 'creme.png'],

        ['name' => 'Tarte Tatin',
        'description' => 'Finissez votre repas en beauté avec la Tarte Tatin, une création pâtissière délicate 
        où les pommes caramélisées fondent dans une croûte feuilletée dorée. Accompagnée d\'une touche de crème 
        fraîche, cette tarte offre une finale sucrée inoubliable.',
        'Menu' => 'menu_Méditérranée',
        'picture' => 'tarte.png'],

        ['name' => 'Île Flottante',
        'description' => 'Terminez votre repas avec l\'Île Flottante, un nuage de blancs d\'œufs délicatement 
        sucré, flottant sur un océan de crème anglaise vanillée. Une douceur aérienne qui fond en bouche pour 
        une fin de repas légère et délicieuse.',
        'Menu' => 'menu_Provence',
        'picture' => 'isle.jpg'],

    ];

    public function load(ObjectManager $manager): void
    {
        $uploadDessertDir = '/uploads/dessert';

        if (!is_dir(__DIR__ . '/../../public' . $uploadDessertDir)) {
            mkdir(__DIR__ . '/../../public' . $uploadDessertDir, recursive: true);
        }

        foreach (self::DESSERT as $dessertData) {
            copy(
                __DIR__ . '/data/dessert/' . $dessertData['picture'],
                __DIR__ . '/../../public' . $uploadDessertDir . '/' . $dessertData['picture']
            );

            $dessert = new Dessert();
            $dessert->setName($dessertData['name']);
            $dessert->setDescription($dessertData['description']);

            $menu = $this->getReference($dessertData['Menu']);
            $dessert->setMenu($menu);
            $menu->addDessert($dessert);
            $dessert->setPicture($dessertData['picture']);

            $manager->persist($dessert);
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
