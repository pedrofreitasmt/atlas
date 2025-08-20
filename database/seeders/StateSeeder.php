<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('sql/estado.sql');

        if (!file_exists($path)) {
            $this->command->error("Arquivo não encontrado: {$path}");
            return;
        }

        $sql = file_get_contents($path);

        // Captura somente INSERTs
        preg_match_all('/INSERT\s+INTO\s+`?states`?.*?;[\r\n]/is', $sql, $matches);

        if (empty($matches[0])) {
            $this->command->warn('Nenhum INSERT encontrado em estado.sql');
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('states')->truncate();

        foreach ($matches[0] as $stmt) {
            $stmt = preg_replace('/INSERT\s+INTO\s+`?states`?/i', 'INSERT INTO `states`', $stmt);

            // Trata cada tupla para evitar NULL em country (5ª) e ddd (6ª)
            $stmt = preg_replace_callback('/\(([^()]+)\)/', function ($m) {
                $parts = preg_split('/,(?=(?:[^\'"]|\'[^\']*\'|"[^"]*")*$)/', $m[1]);
                if (count($parts) === 6) {
                    $parts = array_map('trim', $parts);

                    // country
                    if (strcasecmp($parts[4], 'NULL') === 0) {
                        $parts[4] = '1'; // id de país padrão
                    }

                    // ddd
                    if (strcasecmp($parts[5], 'NULL') === 0) {
                        // usa string vazia; se preferir coloque '0' ou 'NULL' (e torne a coluna nullable)
                        $parts[5] = "''";
                    }

                    return '(' . implode(', ', $parts) . ')';
                }
                return '(' . $m[1] . ')';
            }, $stmt);

            DB::unprepared(trim($stmt));
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        $this->command->info('States importados.');
    }
}
