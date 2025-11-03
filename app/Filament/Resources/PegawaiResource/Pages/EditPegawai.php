<?php

namespace App\Filament\Resources\PegawaiResource\Pages;

use App\Filament\Resources\PegawaiResource;
use App\Models\OrgUnit;
use App\Models\RiwayatJabatan;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;
use Filament\Forms;
use Illuminate\Support\Facades\DB;
use Filament\Notifications\Notification;

class EditPegawai extends EditRecord
{
    protected static string $resource = PegawaiResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('mutasi')
                ->label('Mutasi / Promosi / Rotasi / Demosi')
                ->icon('heroicon-o-arrows-right-left')
                ->form(function () {
                    /** @var \App\Models\Pegawai $pegawai */
                    $pegawai = $this->record;

                    return [
                        Forms\Components\DatePicker::make('tanggal')
                            ->label('Tanggal Efektif')
                            ->required()
                            ->maxDate(now()),

                        Forms\Components\Select::make('jenis')
                            ->label('Jenis Perubahan')
                            ->options([
                                'Promosi' => 'Promosi',
                                'Mutasi'  => 'Mutasi',
                                'Rotasi'  => 'Rotasi',
                                'Demosi'  => 'Demosi',
                            ])
                            ->required()
                            ->native(false),

                        Forms\Components\TextInput::make('ke_jabatan')
                            ->label('Jabatan Baru')
                            ->default($pegawai->jabatan)
                            ->required(),

                        Forms\Components\Select::make('ke_org_unit_id')
                            ->label('Unit Baru')
                            ->options(fn () => OrgUnit::orderBy('name')->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->default($pegawai->org_unit_id)
                            ->required(),

                        Forms\Components\Textarea::make('keterangan')
                            ->label('Keterangan (Opsional)')
                            ->rows(3),
                    ];
                })
                ->action(function (array $data) {
                    /** @var \App\Models\Pegawai $pegawai */
                    $pegawai = $this->record;

                    // -------- VALIDASI CERDAS --------
                    if ($data['jenis'] === 'Promosi' && $data['ke_jabatan'] === $pegawai->jabatan) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Promosi harus mengubah jabatan pegawai.')
                            ->danger()->send();
                        return;
                    }

                    if (in_array($data['jenis'], ['Mutasi', 'Rotasi'], true)
                        && (int) $data['ke_org_unit_id'] === (int) $pegawai->org_unit_id) {
                        Notification::make()
                            ->title('Gagal')
                            ->body($data['jenis'].' harus memindahkan pegawai ke unit yang berbeda.')
                            ->danger()->send();
                        return;
                    }

                    if (
                        $data['jenis'] === 'Demosi'
                        && $data['ke_jabatan'] === $pegawai->jabatan
                        && (int) $data['ke_org_unit_id'] === (int) $pegawai->org_unit_id
                    ) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Demosi harus mengubah jabatan atau unit.')
                            ->danger()->send();
                        return;
                    }

                    // -------- TRANSAKSI AMAN --------
                    try {
                        DB::transaction(function () use ($pegawai, $data) {
                            RiwayatJabatan::create([
                                'pegawai_id'       => $pegawai->id,
                                'tanggal'          => $data['tanggal'],
                                'jenis'            => $data['jenis'],
                                'dari_jabatan'     => $pegawai->jabatan,
                                'ke_jabatan'       => $data['ke_jabatan'],
                                'dari_org_unit_id' => $pegawai->org_unit_id,
                                'ke_org_unit_id'   => $data['ke_org_unit_id'],
                                'keterangan'       => $data['keterangan'] ?? null,
                                'dibuat_oleh'      => auth()->user->id(),
                            ]);

                            $pegawai->update([
                                'jabatan'     => $data['ke_jabatan'],
                                'org_unit_id' => $data['ke_org_unit_id'],
                            ]);
                        });
                    } catch (\Throwable $e) {
                        Notification::make()
                            ->title('Gagal menyimpan perubahan')
                            ->body(config('app.debug') ? $e->getMessage() : 'Terjadi kesalahan saat menyimpan. Silakan coba lagi.')
                            ->danger()->send();
                        return;
                    }

                    // -------- NOTIFIKASI & REFRESH FORM --------
                    Notification::make()
                        ->title('Perubahan berhasil')
                        ->body('Data pegawai diperbarui dan riwayat jabatan dicatat.')
                        ->success()->send();

                    // Segarkan data record & isi form supaya tampilan ikut update
                    $this->record->refresh();
                    $this->fillForm();
                }),

            Actions\DeleteAction::make(),
        ];
    }
}
