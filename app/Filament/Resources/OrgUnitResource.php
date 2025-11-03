<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrgUnitResource\Pages;
use App\Models\OrgUnit;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

use Filament\Forms\Components\{Section, TextInput, Select, Textarea};
use Filament\Tables\Columns\{TextColumn, BadgeColumn};
use Filament\Forms\Get;

class OrgUnitResource extends Resource
{
    protected static ?string $model = OrgUnit::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationGroup = 'Kepegawaian';
    protected static ?string $navigationLabel = 'Struktur Organisasi';
    protected static ?string $modelLabel = 'Unit Organisasi';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('Identitas Unit')->schema([

                TextInput::make('code')
                    ->label('Kode Unit')
                    ->required()
                    ->unique(ignoreRecord: true),

                TextInput::make('name')
                    ->label('Nama Unit')
                    ->required(),

                Select::make('type')
                    ->label('Jenis Unit')
                    ->options([
                        'Cabang' => 'Cabang',
                        'Koorcab' => 'Koorcab',
                        'Unit' => 'Unit',
                    ])
                    ->required()
                    ->native(false),

                Select::make('parent_id')
                    ->label('Induk (Parent)')
                    ->options(function (Get $get) {
                        $current = $get('id'); // Jika edit, rekam ID sedang aktif
                        return OrgUnit::query()
                            ->when($current, fn($q) => $q->where('id', '!=', $current))
                            ->orderBy('name')
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->preload()
                    ->nullable()
                    ->helperText('Kosongkan jika ini adalah unit tingkat teratas.'),

                Select::make('head_pegawai_id')
                    ->label('Pimpinan Unit')
                    ->options(
                        Pegawai::orderBy('nama_lengkap')
                            ->pluck('nama_lengkap', 'id')
                    )
                    ->searchable()
                    ->preload()
                    ->nullable(),

            ])->columns(2),

            Section::make('Kontak')->schema([
                Textarea::make('alamat')->label('Alamat')->rows(2),
                TextInput::make('telepon')->label('Telepon'),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([

            TextColumn::make('code')
                ->label('Kode')
                ->sortable()
                ->searchable(),

            TextColumn::make('name')
                ->label('Nama Unit')
                ->sortable()
                ->searchable(),

            BadgeColumn::make('type')
                ->label('Jenis')
                ->colors([
                    'success' => 'Cabang',
                    'warning' => 'Koorcab',
                    'info' => 'Unit',
                ]),

            TextColumn::make('parent.name')
                ->label('Induk')
                ->placeholder('-'),

            TextColumn::make('head.nama_lengkap')
                ->label('Pimpinan')
                ->placeholder('-'),

            TextColumn::make('pegawais_count')
                ->counts('pegawais')
                ->label('Jumlah Pegawai'),

        ])
        ->defaultSort('type')
        ->actions([Tables\Actions\EditAction::make()])
        ->bulkActions([Tables\Actions\DeleteBulkAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrgUnits::route('/'),
            'create' => Pages\CreateOrgUnit::route('/create'),
            'edit' => Pages\EditOrgUnit::route('/{record}/edit'),
            'tree' => Pages\OrganizationTree::route('/tree'),
        ];
    }
}
