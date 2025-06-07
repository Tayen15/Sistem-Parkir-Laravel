<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class ReportsPage extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static string $view = 'filament.pages.reports-page';

    protected static ?string $title = 'Laporan Detail'; 
    protected static ?string $navigationGroup = 'Laporan & Statistik'; 
    protected static ?int $navigationSort = 2; 
    protected static ?string $slug = 'reports';
}
