<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //$this->getProducts();
        $this->getDetailsProducts();
    }

    private function getProducts()
    {
        // Obtener las categorías que no tienen una categoría padre
        $categorias = Category::all();

        foreach ($categorias as $categoria) {
            // Obtener la API ID de la categoría actual
            $apiId = $categoria->api_id;

            // Obtener los datos de la API para la categoría actual
            $response = Http::get("https://tienda.mercadona.es/api/categories/{$apiId}");

            // Verificar si la solicitud fue exitosa
            if ($response->ok()) {
                $data = $response->json();

                // Iterar sobre los productos de la categoría
                foreach ($data['categories'] as $categoriaAPI) {
                    $category_id = $categoriaAPI['id'];

                    foreach ($categoriaAPI['products'] as $productoAPI) {
                        // Verificar si la categoría existe
                        $category = Category::where('api_id', $category_id)->first();

                        // Si la categoría no existe, puedes decidir qué hacer, por ejemplo, omitir este producto
                        if (!$category) {
                            continue; // Opcional: saltar este producto y continuar con el siguiente
                        }

                        // Crear un nuevo producto en la base de datos
                        Product::create([
                            'api_id_product' => $productoAPI['id'],
                            'category_id' => $category_id,

                            'thumbnail' => $productoAPI['thumbnail'],
                            'display_name' => $productoAPI['display_name'],
                            'iva' => $productoAPI['price_instructions']['iva'],
                            'unit_price' => $productoAPI['price_instructions']['unit_price'],
                        ]);
                    }
                }
            }
        }
    }

    private function getDetailsProducts()
    {
        $products = Product::all();

        foreach ($products as $product) {
            $apiId = $product->api_id_product;

            $response = Http::get("https://tienda.mercadona.es/api/products/{$apiId}");
            if ($response->ok()) {
                $data = $response->json();
                $product->update([
                    'slug' => $data['slug'],
                    'share_url' => $data['share_url'],
                    'brand' => $data['brand'],
                    'origin' => $data['origin'],
                ]);
            }
        }
    }
}
