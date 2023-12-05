<?php
// src/Controller/MovieController.php

namespace App\Controller; // Upewnij się, że masz poprawną przestrzeń nazw dla kontrolera

use App\Entity\Movie;
use App\Form\MovieType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 

class MovieController extends AbstractController
{
    #[Route("/movies", name: "movie_index")]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $movies = $entityManager->getRepository(Movie::class)->findAll();

        return $this->render('index.html.twig', [
            'movies' => $movies,
        ]);
    }

    #[Route("/add", name: "movie_add")]
    public function add(EntityManagerInterface $entityManager, Request $request): Response
    {
        $movie = new Movie();
        $form = $this->createForm(MovieType::class, $movie);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($movie);
            $entityManager->flush();

            return $this->redirectToRoute('movie_index');
        }

        return $this->render('add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
?>
