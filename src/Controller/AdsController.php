<?php

namespace App\Controller;

use App\Entity\Ads;
use App\Form\AdsType;
use App\Repository\AdsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ads")
 */
class AdsController extends AbstractController
{
    /**
     * @Route("/", name="ads_index", methods={"GET"})
     */
    public function index(AdsRepository $adsRepository): Response
    {
        return $this->render('ads/index.html.twig', [
            'ads' => $adsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="ads_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ad = new Ads();
        $form = $this->createForm(AdsType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ad);
            $entityManager->flush();

            return $this->redirectToRoute('ads_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ads/new.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="ads_show", methods={"GET"})
     */
    public function show(Ads $ad): Response
    {
        return $this->render('ads/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="ads_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Ads $ad, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdsType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ads_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('ads/edit.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="ads_delete", methods={"POST"})
     */
    public function delete(Request $request, Ads $ad, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ad->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ad);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ads_index', [], Response::HTTP_SEE_OTHER);
    }
}
