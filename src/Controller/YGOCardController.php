<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\YGOCard;

#[Route('/ygocard')]
class YGOCardController extends AbstractController
{
    #[Route('/', name: 'app_ygocard')]
    public function index(): Response
    {
        return $this->render('ygo_card/index.html.twig', [
            'controller_name' => 'YGOCardController',
        ]);
    }
    
    #[Route('/{id}', name: 'ygocard_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id): Response
    {

        // dump($todos);
        
        $ygoCardRepo = $doctrine->getRepository(YGOCard::class);
        $ygoCard = $ygoCardRepo->find($id);
        
        if (!$ygoCard) {
            throw $this->createNotFoundException('The YGOCard does not exist');
        }
        
        return $this->render(
            'ygo_card/show.html.twig',
            [ 'ygoCard' => $ygoCard]
            );
    }
}
