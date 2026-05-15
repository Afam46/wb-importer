<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Stock;

class ImportStocksData extends Command
{
    protected $signature = 'import:stocks';
    protected $description = 'Import stocks data from external API';

    public function handle()
    {
        $host = config('app.api_host');
        $key = config('app.api_key');
        $dateFrom = now()->format('Y-m-d');
        $page = 1;
        $limit = 500;
        $totalImported = 0;

        $this->info("Начинаем импорт за {$dateFrom}");

        while (true) {
            $url = "{$host}/api/stocks?dateFrom={$dateFrom}&page={$page}&limit={$limit}&key={$key}";
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
                    'date' => $item['date'],
                    'supplier_article' => $item['supplier_article'],
                    'quantity' => $item['quantity'],
                    'quantity_full' => $item['quantity_full'],
                    'warehouse_name' => $item['warehouse_name'],
                    'nm_id' => $item['nm_id'],
                    'price' => (float) $item['price'],
                    'discount' => $item['discount'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $imported = Stock::insertOrIgnore($dataToInsert);
            $totalImported += $imported;
            $this->info("Страница {$page}: импортировано {$imported} записей. Всего: {$totalImported}");

            $lastPage = $responseData['meta']['last_page'];
            if ($page >= $lastPage) {
                $this->info("Достигнута последняя страница {$lastPage}");
                break;
            }

            $page++;
        }

        $this->info("Импорт остатков завершён. Всего записей: {$totalImported}");
        return Command::SUCCESS;
    }
}