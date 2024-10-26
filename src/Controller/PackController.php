<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\YGOCard;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Pack;

#[Route('/')]
class PackController extends AbstractController
{
    #[Route('/', name: 'home')]
    #[Route('/pack', name: 'app_pack')]
    public function index(): Response
    {
        
        return $this->render('pack/index.html.twig', [
            'controller_name' => 'PackController',
        ]);
        
        // throw $this->createNotFoundException('La page n\'est pas disponible.');
    }
    
    #[Route('/pack/list', name: 'pack_list', methods: ['GET'])]
    #[Route('/pack/index', name: 'pack_index', methods: ['GET'])]
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
    
    /*
    public function listAction(ManagerRegistry $doctrine)
    {
        $htmlpage = '<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>packs list!</title>
    </head>
    <body>
        <h1>Liste des Packs de tous les membres</h1>
        <ul>';
        
        $entityManager= $doctrine->getManager();
        $packs = $entityManager->getRepository(Pack::class)->findAll();
        foreach($packs as $pack) {
            
            $url = $this->generateUrl(
                'pack_show',
                ['id' => $pack->getId()]);
            
            $htmlpage .= '<li><a href="'. $url . '"> PACK '. $pack->getId().'</a></li>';
            
            
            $ygoCards = $pack->getYgoCards();
             
            $htmlpage .= '<ul>';
            foreach ($ygoCards as $ygoCard){
                $htmlpage .= '<li> ' . $ygoCard->getName() .'</li>';
            }
                
            $htmlpage .= '</ul>';
            
        }
        $htmlpage .= '</ul>';
        
        $htmlpage .= '</body></html>';
        
        return new Response(
            $htmlpage,
            Response::HTTP_OK,
            array('content-type' => 'text/html')
            );
    }*/
    
    /**
     * Show a Pack
     *
     * @param Integer $id (note that the id must be an integer)
     */
    
    #[Route('/pack/{id}', name: 'pack_show', requirements: ['id' => '\d+'])]
    public function show(ManagerRegistry $doctrine, $id): Response
    {
        $packRepo = $doctrine->getRepository(Pack::class);
        $pack = $packRepo->find($id);
        
        if (!$pack) {
            throw $this->createNotFoundException('The pack does not exist');
        }
        
        return $this->render('pack/show.html.twig',
            [ 'pack' => $pack]
            );
    }
    
    /*
    public function show(ManagerRegistry $doctrine, $id) : Response
    {
        $packRepo = $doctrine->getRepository(Pack::class);
        $pack = $packRepo->find($id);
        
        if (!$pack) {
            throw $this->createNotFoundException('The pack does not exist');
        }
        
        $res = '<h2> PACK '. $pack->getId() .'</h2>';
        
        $ygoCards = $pack->getYgoCards();
        
        $res .= '<ul>';
        foreach ($ygoCards as $ygoCard){
            $res .= '<li> ' . $ygoCard->getName() .'</li>';
        }
        $res .= '</ul>';
        
        $res .= '<p/><a href="' . $this->generateUrl('pack_index') . '">Back</a>';
        
        return new Response('<html><body>'. $res . '</body></html>');
    }
    */
    
}
