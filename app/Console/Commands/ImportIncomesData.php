<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Income;

class ImportIncomesData extends Command
{
    protected $signature = 'import:incomes';
    protected $description = 'Import incomes data from external API';

    public function handle()
    {
        $host = config('app.api_host');
        $key = config('app.api_key');
        $dateFrom = now()->subDays(100)->format('Y-m-d');
        $dateTo = now()->format('Y-m-d');
        $page = 1;
        $limit = 500;
        $totalImported = 0;

        $this->info("Начинаем импорт доходов с {$dateFrom} по {$dateTo}");

        while (true) {
            $url = "{$host}/api/incomes?dateFrom={$dateFrom}&dateTo={$dateTo}&page={$page}&limit={$limit}&key={$key}";
            $this->line("Запрос страницы {$page}...");

            $response = Http::get($url);

            if (!$response->successful()) {
                $this->error("Ошибка API: " . $response->status());
                break;
            }

            $responseData = $response->json();
            $data = $responseData['data'];

            if (empty($data)) {
                $this->info("Данные закончились на странице {$page}");
                break;
            }

            $dataToInsert = [];
            foreach ($data as $item) {
                $dataToInsert[] = [
                    'external_id' => $item['income_id'],
                    'date' => $item['date'],
                    'supplier_article' => $item['supplier_article'],
                    'quantity' => $item['quantity'],
                    'total_price' => (float) $item['total_price'],
                    'warehouse_name' => $item['warehouse_name'] ?? null,
                    'nm_id' => $item['nm_id'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $imported = Income::insertOrIgnore($dataToInsert);
            $totalImported += $imported;
            $this->info("Страница {$page}: импортировано {$imported} записей. Всего: {$totalImported}");

            $lastPage = $responseData['meta']['last_page'];
            if ($page >= $lastPage) {
                $this->info("Достигнута последняя страница {$lastPage}");
                break;
            }

            $page++;
        }

        $this->info("Импорт доходов завершён. Всего записей: {$totalImported}");
        return Command::SUCCESS;
    }
}