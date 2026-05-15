<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Order;

class ImportOrdersData extends Command
{
    protected $signature = 'import:orders';
    protected $description = 'Import orders data from external API';

    public function handle()
    {
        $host = config('app.api_host');
        $key = config('app.api_key');
        $dateFrom = now()->subDays(7)->format('Y-m-d');
        $dateTo = now()->format('Y-m-d');
        $page = 1;
        $limit = 500;
        $totalImported = 0;

        $this->info("Начинаем импорт заказов с {$dateFrom} по {$dateTo}");

        while (true) {
            $url = "{$host}/api/orders?dateFrom={$dateFrom}&dateTo={$dateTo}&page={$page}&limit={$limit}&key={$key}";
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
                    'external_id' => $item['g_number'],
                    'date' => $item['date'],
                    'supplier_article' => $item['supplier_article'],
                    'total_price' => (float) $item['total_price'],
                    'discount_percent' => $item['discount_percent'] ?? null,
                    'warehouse_name' => $item['warehouse_name'] ?? null,
                    'oblast' => $item['oblast'] ?? null,
                    'nm_id' => $item['nm_id'] ?? null,
                    'category' => $item['category'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $imported = Order::insertOrIgnore($dataToInsert);
            $totalImported += $imported;
            $this->info("Страница {$page}: импортировано {$imported} записей. Всего: {$totalImported}");

            $lastPage = $responseData['meta']['last_page'];
            if ($page >= $lastPage) {
                $this->info("Достигнута последняя страница {$lastPage}");
                break;
            }

            $page++;
        }

        $this->info("Импорт заказов завершён. Всего записей: {$totalImported}");
        return Command::SUCCESS;
    }
}