<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesApiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // URL del endpoint
        $url = 'https://tienda.mercadona.es/api/categories/';

        // Realizar la solicitud HTTP para obtener los datos del endpoint
        $response = Http::get($url);

        // Verificar si la solicitud fue exitosa
        if ($response->successful()) {
            $categoriesData = $response->json()['results'];

            // Recorrer los datos de las categorías y guardarlos en la base de datos
            foreach ($categoriesData as $categoryData) {
                $this->saveCategory($categoryData);
            }

            $this->command->info('Categorías importadas correctamente.');
        } else {
            $this->command->error('Error al importar las categorías.');
        }
    }

    private function saveCategory($categoryData, $parentId = null)
    {
        // Crear una nueva categoría en la base de datos
        $category = new Category();
        $category->name = $categoryData['name'];
        // Añadir el ID de la categoría en la API si está disponible
        if (isset($categoryData['id'])) {
            $category->api_id = $categoryData['id'];
        }
        // Establecer el ID del padre si está disponible
        if ($parentId !== null) {
            $category->parent_id = $parentId;
        }
        $category->save();

        // Recorrer las subcategorías si están presentes
        if (isset($categoryData['categories'])) {
            foreach ($categoryData['categories'] as $subCategoryData) {
                $this->saveCategory($subCategoryData, $category->id);
            }
        }
    }
}
