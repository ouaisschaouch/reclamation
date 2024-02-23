<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReclamationType;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use function Symfony\Component\Translation\t;


#[Route('/rec')]
class RecController extends AbstractController
{



    #[Route('/', name: 'app_rec_index', methods: ['GET', 'POST'])]
    public function home(ReclamationRepository $reclamationRepository, SluggerInterface $slugger, Request $request, EntityManagerInterface $entityManager)
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*Photo upload*/
            $photo = $form->get('img')->getData();
            $originalFilename = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $photo->guessExtension();
            // Move the file to the directory where brochures are stored
            try {
                $photo->move(
                    $this->getParameter('Reclamation_Imgs'),
                    $newFilename
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }
            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            $reclamation->setImg($newFilename);


            $entityManager->persist($reclamation);
            $entityManager->flush();

            $this->addFlash('successAddRec', 'Votre réclamation a été envoyée avec succès.');

            return $this->redirectToRoute('app_rec_index');
        }

        return $this->render('FrontOffice/Reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
            'form' => $form->createView()
        ]);
    }




    // Les méthodes de backoffice

    #[Route('/Admin/Index', name: 'app_rec_index_admin', methods: ['GET', 'POST'])]
    public function IndexAdmin(ReclamationRepository $reclamationRepository)
    {

        return $this->render('BackOffice/Acceuil/index.html.twig', [
        ]);
    }


    #[Route('/Admin/reclamation', name: 'app_admin_reclamation', methods: ['GET', 'POST'])]
    public function IndexAdminReclamation(ReclamationRepository $reclamationRepository)
    {
        $form = $this->createForm(ReponseType::class);

        return $this->render('BackOffice/Reclamation/index.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    #[Route('/reponse', name: 'app_rep_add', methods: ['GET', 'POST'])]
    public function addResponse(Request $request, ReclamationRepository $reclamationRepository, EntityManagerInterface $entityManager): Response
    {
        $reponse = new Reponse();
        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($reponse);
            $entityManager->flush();

            $this->addFlash('success', 'Votre réponse a été envoyée avec succès.');

            return $this->redirectToRoute('app_admin_reclamation');
        }

        return $this->render('BackOffice/Reclamation/formreponse.html.twig', [
            'reclamations' => $reclamationRepository->findAll(),
            'form' => $form->createView()
        ]);
    }


    #[Route('/{id}', name: 'app_rec_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('rec/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_rec_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();


            // Rediriger vers la route appropriée après la mise à jour
            $this->addFlash('successEditRec', 'Votre réclamation a été modifié avec succès.');

            return $this->redirectToRoute('app_admin_reclamation', [], Response::HTTP_SEE_OTHER);

        }

        return $this->renderForm('BackOffice/Reclamation/updatereclamation.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);

    }


    #[Route('/{id}', name: 'app_rec_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reclamation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reclamation);
            $entityManager->flush();
            $this->addFlash('successDeleteRec', 'Votre réclamation a été supprimé avec succès.');

        }


        return $this->redirectToRoute('app_admin_reclamation', [], Response::HTTP_SEE_OTHER);
    }
}
