<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @property UserPasswordEncoderInterface passwordEncoder
 */
class UserFixture extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;

    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('black.widow@gmail.com');
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '123qwe456rty'
        ));
        $manager->persist($user);


        $manager->flush();
    }
}
