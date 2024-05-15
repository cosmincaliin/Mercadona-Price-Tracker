<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Product;

class UpdateShopController extends Controller
{
    public function __invoke()
    {
        $categories = [];
        $client = new Client();
        $response = $client->request('GET', 'https://tienda.mercadona.es/api/categories/');

        if ($response->getStatusCode() == 200) {
            $data = json_decode($response->getBody()->getContents(), true);
            //var_dump($data['results']);
            foreach ($data['results'] as $section) {
                if (isset($section['categories'])) {
                    foreach ($section['categories'] as $category) {
                        $categories[] = $category['id']; // Agregar el ID al array
                    }
                }
            }
        }
        //Agafem la meitat de les categories
        shuffle($categories);
        $categories = array_slice($categories, 0, intdiv(count($categories), 3));

        foreach ($categories as $cat) {
            $url = "https://tienda.mercadona.es/api/categories/$cat/";
            $response = $client->request('GET', $url);
            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody()->getContents(), true);
                foreach ($data['categories'] as $subCategory) {
                    foreach ($subCategory['products'] as $productData) {
                        $newPrice = $productData['price_instructions']['unit_price'];

                        Product::updateOrCreate(
                            ['_id' => $productData['id']],
                            [

                                // Añade o ajusta los campos según tu modelo Product
                                'slug' => $productData['slug'] ?? null,
                                'published' => $productData['published'] ?? null,
                                'thumbnail' => $productData['thumbnail'] ?? null,
                                'display_name' => $productData['display_name'] ?? null,
                                'historic_price' => json_encode([]), // Asegúrate de que no hay un espacio en 'historic_price '
                            ]
                        );
                        $product = Product::where('_id', $productData['id'])->first();
                        $product->updatePriceHistory($newPrice);
                        var_dump($product->display_name . ';' . $newPrice . '<br>');
                        $product->save();
                    }
                }
            }
        }
    }

    private function seedCategories($categories, $parentId = null)
    {
        foreach ($categories as $cat) {
            $categories[] = $cat['id'];
            if (!empty($cat['categories'])) {
                $this->seedCategories($cat['categories'], $cat['id']);
            }
        }
    }
}
