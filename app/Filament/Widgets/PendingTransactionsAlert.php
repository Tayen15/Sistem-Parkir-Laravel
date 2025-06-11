<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\Widget;

class PendingTransactionsAlert extends Widget
{
    protected static string $view = 'filament.widgets.pending-transactions-alert';
    protected static ?int $sort = 5;

    protected function getTransactionsCount(): int
    {
        return Transaction::whereNull('end') 
            ->where('status_pembayaran', '!=', 'completed') 
            ->count();
    }
}
