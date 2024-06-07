<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\HttpFoundation\UrlHelper;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private Generator $faker;
    private UserPasswordEncoderInterface $encoder;
    private string $testAvatarUrl;

    public function __construct(UserPasswordEncoderInterface $encoder, UrlHelper $urlHelper, $testPath, $testImage, $publicPath)
    {
        $this->encoder = $encoder;
        $this->faker = Factory::create();
        $this->testAvatarUrl = $urlHelper->getAbsoluteUrl(
            str_replace($publicPath, '', $testPath) . '/' . $testImage
        );
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setFirstName($this->faker->firstName);
            $user->setLastName($this->faker->lastName);
            $user->setEmail($this->faker->email);
            $user->setPassword($this->encoder->encodePassword($user, 'Test1234'));
            $user->setAvatar($this->testAvatarUrl);
            $manager->persist($user);
            $manager->flush();

            $this->addReference('user_' . $i, $user);
        }
    }
}
