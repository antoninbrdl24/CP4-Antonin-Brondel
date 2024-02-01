<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Starter;
use App\Entity\Menu;

class StarterFixtures extends Fixture implements DependentFixtureInterface
{
    public const STARTER = [
        ['name' => 'Soupe à l\'oignon',
        'description' => 'Savourez le réconfort d\'une soupe à l\'oignon française classique, délicieusement préparée 
        avec des oignons caramélisés, une base de bouillon riche et agrémentée d\'une généreuse couche de fromage 
        fondu. Chaque cuillerée offre une explosion de saveurs chaleureuses et réconfortantes.',
        'Menu' => 'menu_Tradition',
        'picture' => 'oignon_soup.png'],

        ['name' => 'Escargots de Bourgogne',
        'description' => 'Commencez votre expérience gastronomique avec les Escargots de Bourgogne, un délice exquis
         où les escargots tendres sont préparés avec une persillade généreuse à base de beurre, d\'ail et de persil. 
         Une entrée qui éveillera vos sens.',
        'Menu' => 'menu_Méditérranée',
        'picture' => 'escargot.png'],

        ['name' => 'Quiche Lorraine',
        'description' => 'Commencez votre repas en douceur avec la Quiche Lorraine, une tarte salée parfaite 
        composée d\'une garniture généreuse de lardons fumés et d\'une crème délicieusement liée. Une expérience 
        gastronomique emblématique de la cuisine française.',
        'Menu' => 'menu_Provence',
        'picture' => 'quiche_lorraine.png'],

    ];

    public function load(ObjectManager $manager): void
    {
        $uploadStarterDir = '/uploads/starter';

        if (!is_dir(__DIR__ . '/../../public' . $uploadStarterDir)) {
            mkdir(__DIR__ . '/../../public' . $uploadStarterDir, recursive: true);
        }

        foreach (self::STARTER as $starterData) {
            copy(
                __DIR__ . '/data/starter/' . $starterData['picture'],
                __DIR__ . '/../../public' . $uploadStarterDir . '/' . $starterData['picture']
            );

            $starter = new Starter();
            $starter->setName($starterData['name']);
            $starter->setDescription($starterData['description']);

            $menu = $this->getReference($starterData['Menu']);
            $starter->setMenu($menu);
            $menu->addStarter($starter);
            $starter->setPicture($starterData['picture']);

            $manager->persist($starter);
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
