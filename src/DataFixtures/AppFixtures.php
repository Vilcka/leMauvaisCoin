<?php
namespace App\DataFixtures;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
class AppFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        foreach ($this->getUserData() as [$firstName, $lastName, $username, $password, $email, $roles]) {
            $user = new User();
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setUsername($username);
            $user->setPassword($this->passwordEncoder->encodePassword($user, $password));
            $user->setEmail($email);
            $user->setRoles($roles);
            $manager->persist($user);
            $this->addReference($username, $user);
        }
        $manager->flush();
    }
    private function getUserData(): array
    {
        return [
            // $userData = [$firstName, $lastName, $username, $password, $email, $roles];
            ['User0', 'yolo', 'User0Yolo','azerty', 'jane_admin@symfony.com', ['ROLE_ADMIN']],
            ['Tom ', 'Doe', 'TomDoe' ,'kitten', 'tom_admin@symfony.com', ['ROLE_ADMIN']],
            ['John', ' Doe', 'JohnDoe','kitten', 'john_user@symfony.com', ['ROLE_USER']],
        ];
    }
}