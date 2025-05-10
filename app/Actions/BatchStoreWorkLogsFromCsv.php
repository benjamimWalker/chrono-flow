<?php

namespace App\Actions;

use Illuminate\Support\Facades\Concurrency;
use Illuminate\Support\Facades\DB;

class BatchStoreWorkLogsFromCsv
{
    public function handle(string $filePath): void
    {
        $processNumber = (int) shell_exec('nproc --all');

        $tasks = [];

        for ($i = 0; $i < $processNumber; $i++) {
            $tasks[] = function () use ($filePath, $processNumber, $i) {
                $file = fopen($filePath, 'r');
                DB::reconnect();

                $workLogs = [];
                $currentLine = 0;

                while (($line = fgets($file)) !== false) {
                    if ($currentLine++ % $processNumber !== $i) {
                        continue;
                    }

                    $data = str_getcsv($line);

                    $workLogs[] = [
                        'employee_name' => $data[0],
                        'date' => $data[1],
                        'hours' => $data[2],
                        'description' => $data[3],
                    ];

                    if (count($workLogs) >= 1500) {
                        DB::table('work_logs')->insert($workLogs);
                        $workLogs = [];
                    }
                }

                if (!empty($workLogs)) {
                    DB::table('work_logs')->insert($workLogs);
                }

                fclose($file);

                return true;
            };
        }

        Concurrency::run($tasks);
    }
}
