<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        // Caminho do arquivo original
        $path = database_path('sql/cidade.sql');

        if (!file_exists($path)) {
            $this->command->error("Arquivo não encontrado: {$path}");
            return;
        }

        $raw = file_get_contents($path);

        // Mantemos somente as linhas de INSERT
        $lines = preg_split("/;\r?\n/", $raw);

        // Limpa tabela
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('cities')->truncate();

        foreach ($lines as $stmt) {
            $stmt = trim($stmt);
            if ($stmt === '' || stripos($stmt, 'INSERT') === false) {
                continue;
            }

            // Unifica nomes de tabela: `cidade` ou `cities` -> `cities`
            $stmt = str_replace(['`cidade`', '`cities`'], '`cities`', $stmt);

            // Ajusta coluna `nome` para `name`
            $stmt = str_replace('`nome`', '`name`', $stmt);

            // Já existem duas formas de cabeçalho:
            // (`id`, `name`, `uf`, `ibge`)
            // Vamos inserir também `state_id` duplicando `uf`
            if (preg_match('/INSERT INTO `cities`\s*\(`id`, `name`, `uf`, `ibge`\)\s*VALUES/i', $stmt)) {
                $stmt = preg_replace(
                    '/INSERT INTO `cities`\s*\(`id`, `name`, `uf`, `ibge`\)\s*VALUES/i',
                    'INSERT INTO `cities` (`id`, `name`, `uf`, `ibge`, `state_id`) VALUES',
                    $stmt
                );

                // Para cada tupla: (id,'name',uf,ibge) -> (id,'name',uf,ibge,uf)
                $stmt = preg_replace_callback(
                    '/\(\s*(\d+)\s*,\s*\'((?:\\\\\'|[^\'])*)\'\s*,\s*(\d+)\s*,\s*(\d+)\s*\)/',
                    function ($m) {
                        // $m[1]=id, $m[2]=name, $m[3]=uf, $m[4]=ibge
                        return '(' . $m[1] . ", '" . $m[2] . "', " . $m[3] . ', ' . $m[4] . ', ' . $m[3] . ')';
                    },
                    $stmt
                );
            }

            // Executa
            DB::unprepared($stmt . ';');
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
