<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        $filePath = __DIR__ . '/../../data/amazon.json';

        if (!file_exists($filePath)) {
            throw $this->createNotFoundException('Archivo JSON no encontrado');
        }

        $jsonContent = file_get_contents($filePath);
        $data = json_decode($jsonContent, true);

        // Extraemos la lista de items
        $items = $data['SearchResult']['Items'] ?? [];

        return $this->render('index.html.twig', [
            'products' => $items,
        ]);
    }
}
