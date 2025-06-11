<?php

namespace App\Filament\Pages;

use App\Models\Transaction;
use App\Models\Vehicle;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Forms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Support\Facades\Response;

class ReportsPage extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.reports-page';

    protected static ?string $title = 'Laporan Detail';
    protected static ?string $navigationGroup = 'Laporan & Statistik';
    protected static ?int $navigationSort = 2;

    public function getDailyReportData(): array
    {
        return [
            'total_vehicles' => Vehicle::count(),
            'active_parkings' => Transaction::whereNull('end')->count(),
            'daily_revenue' => Transaction::whereNotNull('end')->whereDate('tanggal', now()->toDateString())->sum('biaya'),
            'total_transactions_completed' => Transaction::whereNotNull('end')->count(),
            'monthly_revenue' => Transaction::whereNotNull('end')->whereMonth('tanggal', now()->month)->sum('biaya'),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(Transaction::query()->whereNotNull('end')->latest())
            ->columns([
                Tables\Columns\TextColumn::make('tanggal')->date()->label('Tanggal'),
                Tables\Columns\TextColumn::make('vehicle.nopol')->label('Nopol'),
                Tables\Columns\TextColumn::make('areaParkir.name')->label('Area'),
                Tables\Columns\TextColumn::make('start')->time()->label('Masuk'),
                Tables\Columns\TextColumn::make('end')->time()->label('Keluar'),
                Tables\Columns\TextColumn::make('biaya')->money('IDR', 0, '.', ',')->label('Biaya'),
                Tables\Columns\TextColumn::make('status_pembayaran')->badge()->label('Status'),
            ])
            ->filters([
                // Filter tambahan untuk laporan
                Tables\Filters\Filter::make('tanggal')
                    ->form([
                        DatePicker::make('from'),
                        DatePicker::make('to'),
                    ])
                    ->query(function (\Illuminate\Database\Eloquent\Builder $query, array $data): \Illuminate\Database\Eloquent\Builder {
                        return $query
                            ->when($data['from'], fn(\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tanggal', '>=', $date))
                            ->when($data['to'], fn(\Illuminate\Database\Eloquent\Builder $query, $date): \Illuminate\Database\Eloquent\Builder => $query->whereDate('tanggal', '<=', $date));
                    }),
                Tables\Filters\SelectFilter::make('area_parkir_id')
                    ->relationship('areaParkir', 'name')
                    ->label('Area Parkir'),
            ])
            ->paginated(true)
            ->headerActions([
                Tables\Actions\Action::make('export_csv')
                    ->label('Export CSV')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->action(function () {
                        $this->exportTransactionsToCsv();
                    }),
            ]);
    }

    public function exportTransactionsToCsv()
    {
        $query = $this->getTable()->getLivewire()->getFilteredTableQuery();

        $transactions = $query->get();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan_transaksi_parkir_' . date('Ymd_His') . '.csv"',
        ];

        $callback = function () use ($transactions) {
            $file = fopen('php://output', 'w');

            fputcsv($file, [
                'ID Transaksi',
                'Nopol Kendaraan',
                'Jenis Kendaraan',
                'Nama Pemilik',
                'Area Parkir',
                'Slot',
                'Tanggal Masuk',
                'Waktu Masuk',
                'Waktu Keluar',
                'Durasi (Menit)',
                'Biaya (Rp)',
                'Status Pembayaran',
                'Tanggal Dibuat'
            ]);

            foreach ($transactions as $transaction) {
                $nopol = $transaction->vehicle->nopol ?? 'N/A';
                $vehicleTypeName = $transaction->vehicle->vehicleType->name ?? 'N/A';
                $ownerName = $transaction->vehicle->pemilik_rel->name ?? 'N/A';
                $areaName = $transaction->areaParkir->name ?? 'N/A';

                // Hitung durasi dalam menit  laporan
                $startTime = Carbon::parse($transaction->start);
                $endTime = $transaction->end ? Carbon::parse($transaction->end) : now(); 
                $durationInMinutes = $startTime->diffInMinutes($endTime);


                fputcsv($file, [
                    $transaction->id,
                    $nopol,
                    $vehicleTypeName,
                    $ownerName,
                    $areaName,
                    $transaction->keterangan,
                    $transaction->tanggal ? Carbon::parse($transaction->tanggal)->format('Y-m-d') : 'N/A',
                    $transaction->start,
                    $transaction->end ?? 'Belum Keluar',
                    $durationInMinutes,
                    $transaction->biaya, 
                    $transaction->status_pembayaran,
                    $transaction->created_at ? Carbon::parse($transaction->created_at)->format('Y-m-d H:i:s') : 'N/A'
                ]);
            }
            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }
}
