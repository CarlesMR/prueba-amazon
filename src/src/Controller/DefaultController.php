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

        $items = $data['SearchResult']['Items'] ?? [];

        // Por cada producto generamos una valoración aleatoria
        foreach ($items as &$item) {
            $item['Reviews'] = $this->generateRandomRaiting();
        }

        return $this->render('index.html.twig', [
            'products' => $items,
        ]);
    }

    private function generateRandomRaiting()
    {
        // Rating aleatorio entre 5 y 10 
        $rating = mt_rand(50, 100) / 10;

        // Convertimos rating 1-10 a 5 estrellas
        $starsDecimal = $rating / 2; // 10 -> 5 estrellas, 5 -> 2.5 estrellas
        $fullStars = floor($starsDecimal); // n. estrellas completas
        $halfStar = ($starsDecimal - $fullStars) >= 0.5; // si hay media estrella
        $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0); // n. vacías

        // Etiquetas según la puntuación
        $rantingData = match (true) {
            $rating >= 9.5 => ['label' => 'Excepcional', 'color' => 'success'],
            $rating >= 9 => ['label' => 'Excelente', 'color' => 'primary'],
            $rating >= 8 => ['label' => 'Genial', 'color' => 'info'],
            $rating >= 7 => ['label' => 'Bueno', 'color' => 'warning'],
            default => ['label' => 'Regular', 'color' => 'danger'],
        };

        return [
            'Rating' => round($rating, 1),  // puntuación 1-10
            'Stars' => (int) $fullStars, // estrellas completas
            'HalfStar' => $halfStar, // media estrella
            'EmptyStars' => (int) $emptyStars, // estrellas vacías
            'Label' => $rantingData['label'], // etiqueta tipo Genial / Excelente
            'Color' => $rantingData['color'], // color badge
        ];
    }
}
