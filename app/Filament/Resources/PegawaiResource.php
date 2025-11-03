<?php

namespace App\Filament\Resources;

use App\Models\Pegawai;
use App\Models\OrgUnit;
use App\Models\User;

use Filament\Resources\Resource;
use Filament\Forms;
use Filament\Tables;

use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Forms\Get;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;

use App\Filament\Resources\PegawaiResource\Pages;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Data Pegawai';
    protected static ?string $navigationGroup = 'Kepegawaian';
    protected static ?string $modelLabel = 'Pegawai';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Identitas Pegawai')->schema([
                TextInput::make('nip')
                    ->label('NIP')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('nama_lengkap')
                    ->label('Nama Lengkap')
                    ->required(),

                TextInput::make('tempat_lahir')->label('Tempat Lahir'),
                DatePicker::make('tanggal_lahir')->label('Tanggal Lahir'),

                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                    ->native(false),

                FileUpload::make('foto')
                    ->label('Foto Pegawai')
                    ->image()
                    ->directory('foto-pegawai')
                    ->imagePreviewHeight(150)
                    ->openable()
                    ->downloadable(),
            ])->columns(2),

            Section::make('Kontak')->schema([
                TextInput::make('email_kantor')
                    ->label('Email Kantor')
                    ->email()
                    ->unique(ignoreRecord: true),

                TextInput::make('no_hp')
                    ->label('Nomor HP')
                    ->mask('+62###########'),
            ]),

            Section::make('Posisi & Penempatan')->schema([
                TextInput::make('jabatan')
                    ->label('Jabatan')
                    ->required(),

                Select::make('org_unit_id')
                    ->label('Unit Organisasi')
                    ->options(OrgUnit::orderBy('name')->pluck('name', 'id'))
                    ->searchable()
                    ->preload(),

                TextInput::make('departemen')
                    ->label('Departemen (Legacy)')
                    ->helperText('Tidak wajib. Lama-lama akan dihapus karena digantikan Unit Organisasi.'),

                TextInput::make('lokasi_kerja')->label('Lokasi Kerja'),
                DatePicker::make('tanggal_masuk')->label('Tanggal Masuk'),

                Select::make('status_kepegawaian')
                    ->label('Status Kepegawaian')
                    ->options([
                        'Tetap' => 'Pegawai Tetap',
                        'Kontrak' => 'Pegawai Kontrak',
                        'Magang' => 'Magang',
                    ])
                    ->default('Tetap'),
            ])->columns(2),

            Section::make('Relasi Login Sistem')->schema([

                Select::make('user_id')
                    ->label('Akun User (opsional)')
                    ->options(function (Get $get) {
                        $current = $get('user_id');

                        return User::query()
                            ->whereDoesntHave('pegawai')
                            ->when($current, fn ($q) => $q->orWhere('id', $current))
                            ->orderBy('email')
                            ->pluck('email', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Satu akun user hanya boleh terhubung dengan satu pegawai.'),
            ]),

        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([

            ImageColumn::make('foto')
                ->label('')
                ->square()
                ->size(40),

            TextColumn::make('nip')
                ->label('NIP')
                ->sortable()
                ->searchable(),

            TextColumn::make('nama_lengkap')
                ->label('Nama')
                ->sortable()
                ->searchable(),

            TextColumn::make('jabatan')
                ->label('Jabatan')
                ->sortable()
                ->searchable(),

            TextColumn::make('orgUnit.name')
                ->label('Unit Organisasi')
                ->badge()
                ->color('primary')
                ->sortable()
                ->searchable(),

            TextColumn::make('departemen')
                ->label('Departemen')
                ->toggleable(),

            BadgeColumn::make('status_kepegawaian')
                ->label('Status')
                ->colors([
                    'success' => 'Tetap',
                    'warning' => 'Kontrak',
                    'info' => 'Magang',
                ]),

            TextColumn::make('tanggal_lahir')
                ->label('Usia')
                ->formatStateUsing(fn ($record) => $record->tanggal_lahir ? $record->tanggal_lahir->age . ' tahun' : '-'),

            TextColumn::make('user.email')
                ->label('Akun Sistem')
                ->badge()
                ->placeholder('-'),
        ])
        ->defaultSort('nama_lengkap', 'asc')
        ->filters([
            Tables\Filters\SelectFilter::make('status_kepegawaian')
                ->label('Status')
                ->options(['Tetap' => 'Tetap', 'Kontrak' => 'Kontrak', 'Magang' => 'Magang']),

            Tables\Filters\SelectFilter::make('org_unit_id')
                ->label('Unit Organisasi')
                ->options(OrgUnit::orderBy('name')->pluck('name', 'id')),

            Tables\Filters\SelectFilter::make('departemen')
                ->label('Departemen')
                ->options(
                    Pegawai::query()->whereNotNull('departemen')->distinct()->pluck('departemen', 'departemen')
                ),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getRelations(): array
    {
        return [
            \App\Filament\Resources\PegawaiResource\RelationManagers\RiwayatJabatansRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
