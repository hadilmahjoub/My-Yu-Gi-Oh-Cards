<?php

namespace App\Controller;

use App\Entity\YGOCard;
use App\Entity\Pack;
use App\Form\YGOCardType;
use App\Repository\YGOCardRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/ygoCard')]
#[IsGranted('ROLE_USER')]
final class YGOCardController extends AbstractController
{
    #[Route(name: 'app_ygo_card_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refusé : Vous devez être administrateur pour accéder à cette page.')]
    public function index(ygoCardRepository $ygoCardRepository): Response
    {
        if ($this->isGranted('ROLE_ADMIN')) {
            $ygoCards = $ygoCardRepository->findAll();
        }
        else {
            $user = $this->getUser();
            $ygoCards = $ygoCardRepository->findUserYGOCards($user);
        }
        
        return $this->render('ygo_card/index.html.twig', [
            //'ygo_cards' => $ygoCardRepository->findAll(),
            'ygo_cards' => $ygoCards,
        ]);
    }

    #[Route('/new/{id}', name: 'app_ygo_card_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, Pack $pack): Response
    {
        // Vérifier si l'utilisateur connecté est le propriétaire de Pack ou un administrateur
        $currentUser = $this->getUser();
        
        if (!$currentUser || ($currentUser !== $pack->getUser() && !$this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Accès refusé : Vous n\'avez pas l\'autorisation d\'ajouter une YGOCard à ce Pack.');
        }
        
        $ygoCard = new ygoCard();
        $ygoCard->setPack($pack);
        
        
        $form = $this->createForm(ygoCardType::class, $ygoCard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            /*
            // Change content-type according to image's
            $imagefile = $ygoCard->getImageFile();
            if($imagefile) {
                $mimetype = $imagefile->getMimeType();
                $ygoCard->setContentType($mimetype);
            }
            */
            
            $entityManager->persist($ygoCard);
            $entityManager->flush();

            // return $this->redirectToRoute('app_ygo_card_index', [], Response::HTTP_SEE_OTHER);
            /*
            return $this->redirectToRoute('pack_show', [
                'id' => $ygoCard->getPack()->getId(),
            ], Response::HTTP_SEE_OTHER);
            */
            
            return $this->redirectToRoute('app_ygo_card_show', [
                'id' => $ygoCard->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ygo_card/new.html.twig', [
            'ygo_card' => $ygoCard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ygo_card_show', methods: ['GET'])]
    public function show(ygoCard $ygoCard): Response
    {
        return $this->render('ygo_card/show.html.twig', [
            'ygo_card' => $ygoCard,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_ygo_card_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ygoCard $ygoCard, EntityManagerInterface $entityManager): Response
    {
        
        // Vérifier si l'utilisateur connecté est le propriétaire de Pack ou un administrateur
        $currentUser = $this->getUser();
        
        if (!$currentUser || ($currentUser !== $ygoCard->getPack()->getUser() && !$this->isGranted('ROLE_ADMIN'))) {
            throw $this->createAccessDeniedException('Accès refusé : Vous n\'avez pas l\'autorisation d\'éditer une YGOCard d\'un autre Pack.');
        }
        
        $form = $this->createForm(ygoCardType::class, $ygoCard);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // return $this->redirectToRoute('app_ygo_card_index', [], Response::HTTP_SEE_OTHER);
            /*
            return $this->redirectToRoute('pack_show', [
                'id' => $ygoCard->getPack()->getId(),
            ], Response::HTTP_SEE_OTHER);
            */
            
            return $this->redirectToRoute('app_ygo_card_show', [
                'id' => $ygoCard->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ygo_card/edit.html.twig', [
            'ygo_card' => $ygoCard,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_ygo_card_delete', methods: ['POST'])]
    public function delete(Request $request, ygoCard $ygoCard, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ygoCard->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ygoCard);
            $entityManager->flush();
        }

        // return $this->redirectToRoute('app_ygo_card_index', [], Response::HTTP_SEE_OTHER);
        return $this->redirectToRoute('pack_show', [
            'id' => $ygoCard->getPack()->getId(),
        ], Response::HTTP_SEE_OTHER);
        
    }
}
