<?php

namespace App\DataFixtures;

use App\Entity\Photo;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\UrlHelper;

class PhotoFixtures extends Fixture implements DependentFixtureInterface
{
    private string $testImageUrl;

    public function __construct(UrlHelper $urlHelper, $testPath, $testImage, $publicPath)
    {
        $this->testImageUrl = $urlHelper->getAbsoluteUrl(
            str_replace($publicPath, '', $testPath) . '/' . $testImage
        );
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 100; $i++) {
            $user = $this->getReference('user_' . $i);

            $photo = new Photo;
            $photo->setName($user->getFirstName() . "'s Photo");
            $photo->setUrl($this->testImageUrl);
            $photo->setUser($user);
            $manager->persist($photo);
            $manager->flush();
        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
