<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\PackRepository;
use App\Repository\ShowcaseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/user')]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {
        // Affiche la liste des membres
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
    
    #[Route('/{id}', name: 'app_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {   
        // Affiche les détails d'un membre spécifique
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'pack' => $user->getPack(),
            'showcases' => $user->getShowcases(),
        ]);
    }
    
    #[Route('/{id}/showcases', name: 'app_user_showcases_show', methods: ['GET'])]
    public function showShowcases(User $user, ShowcaseRepository $showcaseRepository): Response
    {
        // Récupère les showcases publics de l'utilisateur
        $publicShowcases = $showcaseRepository->findPublicShowcasesByUser($user);
        
        // Affiche les détails d'un membre spécifique
        return $this->render('user/show_user_showcases.html.twig', [
            'user' => $user,
            //'showcases' => $user->getShowcases(),
            'showcases' => $publicShowcases
        ]);
    }
}
