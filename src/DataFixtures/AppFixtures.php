<?php

/**
 * PHP version 8.2 & Symfony 6.4.
 * LICENSE: This source file is subject to version 3.01 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * https://www.php.net/license/3_01.txt.
 *
 * developed by Ben Macha.
 *
 * @category   Symfony Project Les Echos
 *
 * @author     Ali BEN MECHA       <contact@benmacha.tn>
 *
 * @copyright  Ⓒ 2024 benmacha.tn
 *
 * @see       https://www.benmacha.tn
 *
 *
 */

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Author;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $authors = [];
        for ($i = 1; $i <= 10; ++$i) {
            $author = new Author();
            $author
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName());

            $manager->persist($author);
            $authors[$i] = $author;
        }

        $tags = [];
        for ($i = 1; $i <= 25; ++$i) {
            $tag = new Tag();
            $tag
                ->setName($this->faker->sentence(1));

            $manager->persist($tag);
            $tags[$i] = $tag;
        }

        for ($i = 1; $i <= 50; ++$i) {
            $article = new Article();
            $article
                ->setTitle($this->faker->sentence(random_int(5, 10)))
                ->setSlug($this->faker->slug)
                ->setContent($this->faker->paragraph)
                ->setAuthor($authors[random_int(1, 10)]);

            for ($j = 1; $j <= random_int(1, 5); ++$j) {
                $article->addTag($tags[random_int(1, 25)]);
            }

            $manager->persist($article);
        }
        $manager->flush();
    }
}
