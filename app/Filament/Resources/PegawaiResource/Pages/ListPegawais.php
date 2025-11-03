<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

use Maatwebsite\Excel\Facades\Excel;
use App\Imports\PegawaiImport;

class ListPegawais extends ListRecords
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            Action::make('import_excel')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    FileUpload::make('file')
                        ->label('File Excel (.xlsx)')
                        ->required()
                        ->directory('temp-imports')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ]),
                ])
                ->action(function (array $data) {
                    Excel::import(new PegawaiImport, storage_path('app/' . $data['file']));

                    Notification::make()
                        ->title('Import Berhasil')
                        ->body('Data pegawai berhasil ditambahkan / diperbarui.')
                        ->success()
                        ->send();
                }),

            Action::make('download_template')
                ->label('Download Template Excel')
                ->icon('heroicon-o-document-arrow-down')
                ->url(asset('template/template_import_pegawai.xlsx'))
                ->openUrlInNewTab(),

                Action::make('cetak')
    ->label('Cetak PDF')
    ->icon('heroicon-o-printer')
    ->url(route('pegawai.cetak'))
    ->openUrlInNewTab(),
        ];
    }
}
