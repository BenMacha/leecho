<?php

namespace App\DataFixtures;

use App\Entity\Author;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Article;

class AppFixtures extends Fixture
{

    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $authors = array();
        for ($i=1; $i <= 10; $i++) {
            $author = new Author();
            $author
                ->setFirstName($this->faker->firstName())
                ->setLastName($this->faker->lastName());

            $manager->persist($author);
            $authors[$i] = $author;
        }

        $tags = array();
        for ($i=1; $i <= 25; $i++) {
            $tag = new Tag();
            $tag
                ->setName($this->faker->sentence(1));

            $manager->persist($tag);
            $tags[$i] = $tag;
        }

        for ($i=1; $i <= 50; $i++) {
            $article = new Article();
            $article
                ->setTitle($this->faker->sentence(rand(5, 10)))
                ->setSlug($this->faker->slug)
                ->setContent($this->faker->paragraph)
                ->setAuthor($authors[rand(1,10)]);

            for ($j=1; $j <= rand(1,5); $j++){
                $article->addTag($tags[rand(1,25)]);
            }


            $manager->persist($article);
        }
        $manager->flush();
    }
}
