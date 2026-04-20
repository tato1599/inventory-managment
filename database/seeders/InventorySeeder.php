<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Seeder;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Ciencias', 'description' => 'Equipos y modelos para el departamento de ciencias.'],
            ['name' => 'Artes', 'description' => 'Instrumentos y materiales artísticos.'],
            ['name' => 'Historia', 'description' => 'Documentos, mapas y artefactos históricos.'],
            ['name' => 'Tecnología', 'description' => 'Dispositivos electrónicos y consumibles.'],
        ];

        foreach ($categories as $catData) {
            $category = Category::create($catData);

            if ($category->name === 'Ciencias') {
                Item::create([
                    'category_id' => $category->id,
                    'sku' => 'SCI-MOD-001',
                    'name' => 'Modelo Anatómico Humano',
                    'description' => 'Modelo a escala real para estudios biológicos.',
                    'status' => 'available',
                ]);
                Item::create([
                    'category_id' => $category->id,
                    'sku' => 'SCI-MIC-042',
                    'name' => 'Microscopio Digital HD',
                    'description' => 'Microscopio de alta resolución con salida HDMI.',
                    'status' => 'loaned',
                ]);
            }

            if ($category->name === 'Historia') {
                Item::create([
                    'category_id' => $category->id,
                    'sku' => 'HIS-MAP-V04',
                    'name' => 'Mapa Victoriano de Londres',
                    'description' => 'Mapa original de 1888 en buen estado.',
                    'status' => 'available',
                    'metadata' => ['age' => '136 years', 'origin' => 'London'],
                ]);
                Item::create([
                    'category_id' => $category->id,
                    'sku' => 'HIS-DOC-MS88',
                    'name' => 'Manuscrito Raro MS-88',
                    'description' => 'Manuscrito medieval sobre pergamino.',
                    'status' => 'maintenance',
                    'metadata' => ['humidity_threshold' => '55%', 'current_humidity' => '62%'],
                ]);
            }

            if ($category->name === 'Tecnología') {
                Item::create([
                    'category_id' => $category->id,
                    'sku' => 'TEC-LAP-X01',
                    'name' => 'Laptop Workstation Pro',
                    'description' => 'Equipo de alto rendimiento para edición.',
                    'status' => 'available',
                ]);
            }
        }
    }
}
