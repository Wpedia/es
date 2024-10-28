<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ProductController extends AbstractController
{
    private HttpClientInterface $client;

    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    #[Route('/orders/stock', name: 'stock_by_article', methods: ['GET'])]
    public function stockByArticle(Request $request): JsonResponse
    {
        $article = $request->query->get('Article');
        $apiKey = $request->query->get('api_key');

        if (!$article || !$apiKey) {
            return $this->json(['error' => 'Missing required parameters'], 400);
        }

        $apiUrl = 'http://api.tmparts.ru/api/orders/StockByArticle?Article=' . $article . '&api_key=' . $apiKey;
        
        $response = $this->client->request('GET', $apiUrl);

        $data = $response->toArray();

        $result = [];
        foreach ($data as $item) {
            $result[] = [
                'brand' => $item['brand'] ?? '',
                'article' => $item['article'] ?? '',
                'name' => $item['name'] ?? '',
                'quantity' => $item['quantity'] ?? 0,
                'price' => $item['price'] ?? 0,
                'delivery_duration' => $item['delivery_duration'] ?? 0,
                'vendorId' => $item['vendorId'] ?? '',
                'warehouseAlias' => $item['warehouseAlias'] ?? '',
            ];
        }

        return $this->json($result);
    }
}
# здесь много чего нет. Это решнение гипотетически рабоче))
# нужна авторизация в апи транспортной, 
###  также есть index html twig, можно сделать интерфейс и связать то что мы тут получаем в респонсе и раскидать в интерфейсе 
### Гипотетически норм
## 
