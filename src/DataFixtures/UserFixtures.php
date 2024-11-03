<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;


class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;
    
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    
    
     
    public function load(ObjectManager $manager): void
    {
        
        // Generate users test data
        $users = $this->loadUsersData($manager);
    }
    
    /**
     * Generates initialization data for members :
     *  [email, plain text password]
     * @return \\Generator
     */
    private function membersGenerator()
    {
        yield ['olivier@localhost','123456', 'ROLE_USER'];
        yield ['slash@localhost','123456', 'ROLE_USER'];
        yield ['admin@localhost','123456', 'ROLE_ADMIN'];
    }
    
    
    private function loadUsersData(ObjectManager $manager) : array {
        $users = [];
        
        foreach ($this->membersGenerator() as [$email, $plainPassword, $role]) {
            $user = new User();
            $password = $this->hasher->hashPassword($user, $plainPassword);
            $user->setEmail($email);
            $user->setPassword($password);
            
            $roles = array();
            $roles[] = $role;
            $user->setRoles($roles);
            
            $manager->persist($user);
            
            $users[] = $user;
        }
        
        $manager->flush();
        
        return $users;
    }
    
}
