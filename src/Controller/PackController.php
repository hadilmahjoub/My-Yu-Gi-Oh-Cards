<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\YGOCard;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Pack;

#[Route('/pack')]
class PackController extends AbstractController
{
    #[Route('/', name: 'app_pack')]
    public function index(): Response
    {
        
        return $this->render('pack/index.html.twig', [
            'controller_name' => 'PackController',
        ]);
        
        // throw $this->createNotFoundException('La page n\'est pas disponible.');
    }
    
    #[Route('/list', name: 'pack_list', methods: ['GET'])]
    #[Route('/index', name: 'pack_index', methods: ['GET'])]
    public function listAction(ManagerRegistry $doctrine): Response
    {
        $entityManager= $doctrine->getManager();
        $packs = $entityManager->getRepository(Pack::class)->findAll();
        
        // dump($todos);
        
        return $this->render(
            'pack/list.html.twig',
            [ 'packs' => $packs]
        );
    }
    
    /**
     * Show a Pack
     *
     * @param Integer $id (note that the id must be an integer)
     */
    
    #[Route('/{id}', name: 'pack_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        $packRepo = $doctrine->getRepository(Pack::class);
        $pack = $packRepo->find($id);
        
        if (!$pack) {
            throw $this->createNotFoundException('The pack does not exist');
        }
        
        // Vérifie si l'utilisateur authentifié est le propriétaire du pack ou un administrateur
        $hasAccess = $this->isGranted('ROLE_ADMIN') || ($this->getUser() === $pack->getUser());
        if (!$hasAccess) {
            throw $this->createAccessDeniedException("You cannot access another member's pack!");
        }
        
        return $this->render('pack/show.html.twig',
            [ 'pack' => $pack]
            );
    }
    
}
