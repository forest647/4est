<?php

namespace Database\Seeders;

use App\Models\Creation;
use App\Models\CreationTranslation;
use App\Models\GalleryImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CreationSeeder extends Seeder
{
    public function run(): void
    {
        $sqlFile = '/old/estinfo_4est.sql';
        $sql = file_get_contents($sqlFile);

        $this->seedCreations($sql);
        $this->seedGallery($sql);
    }

    private function seedCreations(string $sql): void
    {
        // Match creation INSERT values
        // Pattern: (id, 'name', 'size', 0xHEX, price, 'download', category_id, material_id)
        preg_match_all(
            "/\((\d+),\s*'([^']*)',\s*'([^']*)',\s*0x([0-9a-fA-F]+),\s*([\d.]+),\s*'([^']*)',\s*(\d+),\s*(\d+)\)/",
            $sql,
            $matches,
            PREG_SET_ORDER
        );

        foreach ($matches as $match) {
            $id = (int) $match[1];
            $name = $match[2];
            $size = $match[3];
            $description = hex2bin($match[4]);
            $price = (float) $match[5];
            $download = $match[6] ?: null;
            $categoryId = (int) $match[7];
            $materialId = (int) $match[8];

            $creation = Creation::forceCreate([
                'id' => $id,
                'size' => $size,
                'price' => $price,
                'download' => $download,
                'category_id' => $categoryId,
                'material_id' => $materialId,
            ]);

            CreationTranslation::forceCreate([
                'creation_id' => $creation->id,
                'locale' => 'en',
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => $description,
            ]);
        }
    }

    private function seedGallery(string $sql): void
    {
        // Match all gallery INSERT blocks
        preg_match_all(
            "/INSERT INTO `gallery`.*?VALUES\s*(.*?);/s",
            $sql,
            $insertBlocks
        );

        foreach ($insertBlocks[1] as $block) {
            preg_match_all(
                "/\((\d+),\s*(\d+),\s*'([^']*)',\s*(\d+)\)/",
                $block,
                $rows,
                PREG_SET_ORDER
            );

            foreach ($rows as $row) {
                GalleryImage::forceCreate([
                    'creation_id' => (int) $row[2],
                    'filename' => $row[3],
                    'ranking' => (int) $row[4],
                ]);
            }
        }
    }
}
