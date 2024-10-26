<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Showcase;
use App\Entity\YGOCard;
use App\Entity\Pack;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;


class AppFixtures extends Fixture
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
        
        // Create a Pack for each user and fill it with random YGO cards
        foreach ($users as $user) {
            $pack = $this->createPackForUser($manager, $user);
            $this->fillPackWithRandomYGOCard($manager, $pack);
            
            // Create two showcases for the user and fill them with cards from the user's pack
            $this->loadShowcasesForUser($manager, $user, $pack);
        }
        
        $manager->flush();
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
    
    
    private function createPackForUser(ObjectManager $manager, User $user): Pack
    {
        // Crée un Pack pour l'utilisateur avec un titre spécifique
        $pack = new Pack();
        $pack->setTitle("YGO Pack of " . $user->getEmail());
        $pack->setUser($user); // Associe le Pack à l'utilisateur
        
        $manager->persist($pack);
        $manager->flush();
        
        return $pack;
    }
    
    // TODO LATER : not random cuz one card can't be in more than one pack
    private function fillPackWithRandomYGOCard(ObjectManager $manager, Pack $pack): void
    {
        // Crée un ensemble de noms de cartes YGO de manière aléatoire
        $cardNames = [
            'Blue-Eyes White Dragon', 'Dark Magician', 'Red-Eyes Black Dragon',
            'Summoned Skull', 'Celtic Guardian', 'Harpie Lady',
            'Kuriboh', 'Mystical Elf', 'Gaia the Fierce Knight',
            'Time Wizard', 'Jinzo', 'Exodia the Forbidden One',
            'Obelisk the Tormentor', 'Slifer the Sky Dragon',
            'Ra the Winged Dragon', 'Levia-Dragon - Daedalus',
            'Elemental Hero Neos', 'Cyber Dragon', 'Stardust Dragon',
            'Black Luster Soldier'
        ];
        
        // Remplir le pack avec un nombre aléatoire de cartes (par exemple, entre 5 et 10)
        $numberOfCards = rand(5, 10);
        shuffle($cardNames); // Mélange les noms pour avoir un choix aléatoire
        
        for ($i = 0; $i < $numberOfCards; $i++) {
            $card = new YGOCard();
            $card->setName($cardNames[$i]);
            $card->setPack($pack); // Associe la carte au Pack
            $manager->persist($card);
        }
        
        $manager->flush();
    }
    
    private function loadShowcasesForUser(ObjectManager $manager, User $user, Pack $pack): void
    {
        // Crée deux showcases pour chaque utilisateur
        for ($i = 1; $i <= 2; $i++) {
            $showcase = new Showcase();
            $showcase->setDescription("Showcase $i for user {$user->getEmail()}");
            $showcase->setCreator($user); // Associe la showcase à l'utilisateur
            $showcase->setPublished(true); // Showcase publique par défaut
            
            // Ajoute des cartes aléatoires depuis le Pack de l'utilisateur
            $this->loadRandomCardsIntoShowcase($manager, $showcase, $pack);
            
            $manager->persist($showcase);
        }
        
        $manager->flush();
    }
    
    private function loadRandomCardsIntoShowcase(ObjectManager $manager, Showcase $showcase, Pack $pack): void
    {
        // Récupère toutes les cartes YGO du Pack spécifique de l'utilisateur
        $cards = $manager->getRepository(YGOCard::class)->findBy(['pack' => $pack]);
        
        // Associe 3 cartes aléatoires à la showcase
        shuffle($cards);
        $cards_to_add = array_slice($cards, 0, 3);
        
        foreach ($cards_to_add as $card) {
            $showcase->addYgoCard($card); // Associe la carte à la showcase
        }
    }
    
    /*
    private function createPacks(ObjectManager $manager) : array {
        
        $all_packs = [];
        
        $pack_list_names = ["DARK", 
							"DIVINE", 
							"EARTH", 
							"FIRE", 
							"LIGHT", 
							"WATER", 
							"WIND"];
        
        foreach ($pack_list_names as $pack_name) {
            $pack = new Pack();
            $pack->setTitle($pack_name);
            $manager->persist($pack);
            
            $all_packs[] = $pack;
        }
        
        $manager->flush();
        
        return $all_packs;
    }
    
    private function getYGOCardsData()
    {
        yield 'DARK' => ['Dark Magician', 'Summoned Skull', 'Kuriboh'];
        yield 'DIVINE' => ['Obelisk the Tormentor', 'Slifer the Sky Dragon'];
        yield 'EARTH' => ['Gaia the Fierce Knight', 'Celtic Guardian'];
        yield 'FIRE' => ['Red-Eyes Black Dragon', 'Blazing Inpachi'];
        yield 'LIGHT' => ['Blue-Eyes White Dragon', 'Mystical Elf'];
        yield 'WATER' => ['Levia-Dragon - Daedalus', 'Abyss Soldier'];
        yield 'WIND' => ['Harpie Lady', 'Elemental Hero Avian'];
    }
    
    private function loadsYGOCards(ObjectManager $manager, array $all_packs) {
  
        foreach ($all_packs as $pack) {
            $packTitle = $pack->getTitle();
            
            $cards = $this->getYGOCardsData();
            
            foreach ($cards as $title => $cardsNames) {
                if ($title === $packTitle) {
                    foreach ($cardsNames as $cardName) {
                        $card = new YGOCard();
                        $card->setName($cardName);
                        $card->setPack($pack); // Associate the card with the pack
                        $manager->persist($card);
                    }
                }
            }
        }
        
        $manager->flush();
    }*/
    
    private function loadShowcases(ObjectManager $manager, array $users): array
    {
        $showcases = [];
        
        // Création des showcases pour chaque utilisateur
        foreach ($users as $user) {
            for ($i = 1; $i <= 2; $i++) { // Crée deux showcases par utilisateur
                $showcase = new Showcase();
                $showcase->setDescription("Showcase $i pour l'utilisateur {$user->getEmail()}");
                $showcase->setCreator($user); // Associe l'utilisateur à la showcase
                $showcase->setPublished(true); // Showcase publique par défaut
                
                $manager->persist($showcase);
                
                $showcases[] = $showcase;
            }
        }
        
        $manager->flush();
        
        return $showcases;
    }
    
    private function loadCardsIntoShowcases(ObjectManager $manager, array $showcases): void
    {
        // Sélectionne quelques cartes YGO au hasard pour les associer aux showcases
        foreach ($showcases as $showcase) {
            // Récupère les cartes YGO depuis la base de données
            $cards = $manager->getRepository(YGOCard::class)->findAll();
            
            dump($cards);
            
            // Associe 3 cartes aléatoires à chaque showcase
            shuffle($cards);
            $cards_to_add = array_slice($cards, 0, 3); // Prend les 3 premières cartes aléatoires
            
            foreach ($cards_to_add as $card) {
                $showcase->addYgoCard($card); // Associe la carte à la showcase
            }
            
            $manager->persist($showcase);
        }
        
        $manager->flush();
    }
    
}
