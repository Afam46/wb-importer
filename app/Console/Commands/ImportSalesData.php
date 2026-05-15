<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Sale;

class ImportSalesData extends Command
{
    protected $signature = 'import:sales';
    protected $description = 'Import sales data from external API';

    public function handle()
    {
        $host = config('app.api_host');
        $key = config('app.api_key');
        $dateFrom = now()->subDays(7)->format('Y-m-d');
        $dateTo = now()->format('Y-m-d');
        $page = 1;
        $limit = 500;
        $totalImported = 0;

        $this->info("Начинаем импорт продаж с {$dateFrom} по {$dateTo}");

        while (true) {
            $url = "{$host}/api/sales?dateFrom={$dateFrom}&dateTo={$dateTo}&page={$page}&limit={$limit}&key={$key}";
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

            $imported = 0;
            $dataToInsert = [];
            foreach ($data as $item) {
                $dataToInsert[] = [
                    'external_id' => $item['sale_id'],
                    'sale_date' => $item['date'],
                    'product_name' => $item['supplier_article'],
                    'quantity' => 1,
                    'total' => (float) $item['total_price'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $imported = Sale::insertOrIgnore($dataToInsert);
            $totalImported += $imported;
            $this->info("Страница {$page}: импортировано {$imported} записей. Всего: {$totalImported}");

            $lastPage = $responseData['meta']['last_page'];
            if ($page >= $lastPage) {
                $this->info("Достигнута последняя страница {$lastPage}");
                break;
            }

            $page++;
        }

        $this->info("Импорт продаж завершён. Всего записей: {$totalImported}");
        return Command::SUCCESS;
    }
}