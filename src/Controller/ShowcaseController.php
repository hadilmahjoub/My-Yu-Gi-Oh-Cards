<?php

namespace App\Controller;

use App\Entity\Showcase;
use App\Entity\YGOCard;
use App\Entity\User;
use App\Form\ShowcaseType;
use App\Repository\ShowcaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

use Doctrine\Persistence\ManagerRegistry;

#[Route('/showcase')]
#[IsGranted('ROLE_USER')]
final class ShowcaseController extends AbstractController
{
    #[Route('/', name:  'app_showcase_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN', message: 'Accès refusé : Vous devez être administrateur pour accéder à cette page.')]
    public function index(ShowcaseRepository $showcaseRepository): Response
    {
        return $this->render('showcase/index.html.twig', [
            'showcases' => $showcaseRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_showcase_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, User $user): Response
    {
        // Contrôle d'accès : seul le user authentifié ou un administrateur peut créer un Pack
        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser() !== $user) {
            throw $this->createAccessDeniedException(
                'Accès refusé : Vous ne pouvez pas créer un Showcase pour un autre user.'
                );
        }
        $showcase = new Showcase();
        $showcase->setCreator($user);
        
        $form = $this->createForm(ShowcaseType::class, $showcase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($showcase);
            $entityManager->flush();

            // return $this->redirectToRoute('app_showcase_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_user_showcases_show', [
                'id' => $user->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('showcase/new.html.twig', [
            'user' => $user,
            'showcase' => $showcase,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_showcase_show', methods: ['GET'])]
    public function show(Showcase $showcase): Response
    {
        $hasAccess = false;
        $currentUser = $this->getUser();
        
        if( $currentUser && ( $this->isGranted('ROLE_ADMIN') || $showcase->isPublished() )) {
            $hasAccess = true;
        }
        else {
            if ( $currentUser &&  ($currentUser == $showcase->getCreator()) ) {
                $hasAccess = true;
            }
        }
        
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access the requested resource!");
        }
        
        return $this->render('showcase/show.html.twig', [
            'showcase' => $showcase,
        ]);
    }
    
    /**
     * Show a ygoCard in the context of a Showcase
     *
     * @param Showcase $showcase --> the showcase which diplays the ygoCard (galerie)
     * @param YGOCard $ygoCard   --> the ygoCard to display (objet)
     */
    #[Route('/{showcase_id}/ygocard/{ygocard_id}',
        methods: ['GET'],
        name: 'app_showcase_ygocard_show', 
        requirements: [
            'showcase_id' => '\d+', 
            'ygocard_id' => '\d+'
        ])]
    public function ygoCardShow(        
        #[MapEntity(id: 'showcase_id')]
        Showcase $showcase,
        
        #[MapEntity(id: 'ygocard_id')]
        YGOCard $ygocard
    ): Response
    {
        
        // dump($todos);
        
        /*
        $ygoCardRepo = $doctrine->getRepository(YGOCard::class);
        $ygoCard = $ygoCardRepo->find($id);
        
        if (!$ygoCard) {
            throw $this->createNotFoundException('The YGOCard does not exist');
        }
        */
        
        if(! $showcase->getYgoCards()->contains($ygocard)) {
            throw $this->createNotFoundException("Couldn't find such a Yu-Gi-Oh! Card in this Showcase!");
        }
        
        // if(! $showcase->isPublished()) {
        //   throw $this->createAccessDeniedException("You cannot access the requested ressource!");
        //}
        
        $hasAccess = false;
        
        if($this->isGranted('ROLE_ADMIN') || $showcase->isPublished()) {
            $hasAccess = true;
        }
        else {
            $member = $this->getUser();
            if ( $member &&  ($member == $showcase->getCreator()) ) {
                $hasAccess = true;
            }
        }
        
        if(! $hasAccess) {
            throw $this->createAccessDeniedException("You cannot access the requested ressource!");
        }
        
        return $this->render(
            'showcase/ygocard_show.html.twig',
            [ 
                'ygo_card' => $ygocard,
                'showcase' => $showcase,
            ]
            );
    }

    #[Route('/{id}/edit', name: 'app_showcase_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Showcase $showcase, EntityManagerInterface $entityManager): Response
    {
        // Contrôle d'accès : seul le user authentifié ou un administrateur peut créer un Pack
        if (!$this->isGranted('ROLE_ADMIN') && $this->getUser() !== $showcase->getCreator()) {
            throw $this->createAccessDeniedException(
                'Accès refusé : Vous ne pouvez pas éditer un Showcase d\'un autre user.'
                );
        }
        $form = $this->createForm(ShowcaseType::class, $showcase);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // return $this->redirectToRoute('app_showcase_index', [], Response::HTTP_SEE_OTHER);
            return $this->redirectToRoute('app_user_showcases_show', [
                'id' => $showcase->getCreator()->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('showcase/edit.html.twig', [
            'showcase' => $showcase,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_showcase_delete', methods: ['POST'])]
    public function delete(Request $request, Showcase $showcase, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$showcase->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($showcase);
            $entityManager->flush();
        }

        // return $this->redirectToRoute('app_showcase_index', [], Response::HTTP_SEE_OTHER);
        return $this->redirectToRoute('app_user_showcases_show', [
            'id' => $showcase->getCreator()->getId()
        ], Response::HTTP_SEE_OTHER);
    }
}
