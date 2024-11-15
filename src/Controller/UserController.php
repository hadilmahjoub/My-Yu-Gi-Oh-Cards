<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\PackRepository;
use App\Repository\ShowcaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/', name: 'home', methods: ['GET'])]
    public function home(): Response
    {
        $user = $this->getUser();
        return $this->render('user/home.html.twig', [
            'user' => $user,
        ]);
    }
    
    #[Route('/user', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        // Affiche la liste des membres
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    
    #[Route('/user/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {   
        // Affiche les détails d'un membre spécifique
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'pack' => $user->getPack(),
            'showcases' => $user->getShowcases(),
        ]);
    }
    
    #[Route('/user/{id}/showcases', name: 'app_user_showcases_show', methods: ['GET'])]
    public function showShowcases(User $user, ShowcaseRepository $showcaseRepository): Response
    {
        $currentUser = $this->getUser();
        
        // Récupère les showcases publics de l'utilisateur
        $publicShowcases = $showcaseRepository->findPublicShowcasesByUser($user);
        
        // Initialize array to store showcases to be displayed
        $showcases = $publicShowcases;
        
        // If the current user is the owner or an admin, also load private showcases
        if ($currentUser === $user || $this->isGranted('ROLE_ADMIN')) {
            $privateShowcases = $showcaseRepository->findBy([
                'published' => false,
                'creator' => $user,
            ]);
            
            // Merge public and private showcases
            $showcases = array_merge($publicShowcases, $privateShowcases);
        }
        
        // Affiche les détails d'un membre spécifique
        return $this->render('user/show_user_showcases.html.twig', [
            'user' => $user,
            //'showcases' => $user->getShowcases(),
            'showcases' => $showcases
        ]);
    }
}
