<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ImportAllData extends Command
{
    protected $signature = 'import:all';
    protected $description = 'Run all import commands';

    public function handle()
    {
        $this->call('import:sales');
        $this->call('import:orders');
        $this->call('import:stocks');
        $this->call('import:incomes');

        $this->info('Все данные успешно импортированы!');
        return Command::SUCCESS;
    }
}