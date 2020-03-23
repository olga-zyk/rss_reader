<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private UserPasswordEncoderInterface $PasswordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->PasswordEncoder = $passwordEncoder;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyz';

        for ($i = 0; $i <= 9; $i++) {
            $user = new User();
            $user->setEmail(substr(str_shuffle($chars), 0, rand(7, 11)) . '@gmail.com');
            $user->setPassword($this->PasswordEncoder->encodePassword(
                $user,
                substr(str_shuffle($chars), 0, rand(8, 12))
            ));
            $user->setRoles($user->getRoles());
            $manager->persist($user);
        }

        $manager->flush();
    }
}
