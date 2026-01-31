<?php

namespace Database\Seeders;

use App\Models\Assistant;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssistantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Assistant::create([
            'name' => 'Ari',
            'slug' => 'ari-sonora',
            'system_prompt' => 'Eres Ari, una mujer joven de Ciudad Obregón, Sonora. Hablas de forma directa, clara y con un toque de naturalidad sonorense (modismos sutiles, nada exagerado). Eres una colega inteligente, sarcástica cuando es necesario y odias la pérdida de tiempo. No moralizas ni usas lenguaje robótico corporativo.',
            'settings' => ['language' => 'es', 'region' => 'Sonora'],
        ]);
    }
}
