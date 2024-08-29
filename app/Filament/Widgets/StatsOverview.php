<?php

namespace App\Filament\Widgets;

use App\Models\Transaction;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Carbon\Carbon;
use Filament\Widgets\Concerns\InteractsWithPageFilters;

class StatsOverview extends BaseWidget
{
    use InteractsWithPageFilters;

    protected function getStats(): array
    {

        $startDate = ! is_null($this->filters['startDate'] ?? null) ?
            Carbon::parse($this->filters['startDate']) :
            null;

        $endDate = ! is_null($this->filters['endDate'] ?? null) ?
            Carbon::parse($this->filters['endDate']) :
            now();

        $incomes = Transaction::incomes()->get()->whereBetween('date_transaction', [$startDate, $endDate])->sum('amount');
        $expenses = Transaction::expenses()->get()->whereBetween('date_transaction', [$startDate, $endDate])->sum('amount');

        return [
            Stat::make('Total Expenses', $expenses),
            Stat::make('Total Income', $incomes),
            Stat::make('Difference', $incomes - $expenses),
        ];
    }
}
