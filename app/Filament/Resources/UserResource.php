<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'User Sistem';
    protected static ?string $modelLabel = 'User Sistem';
    protected static ?string $navigationGroup = 'Manajemen Sistem';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('Nama')
                ->required(),

            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),

            Forms\Components\TextInput::make('password')
                ->password()
                ->revealable()
                ->dehydrateStateUsing(fn($state) => filled($state) ? Hash::make($state) : null)
                ->dehydrated(fn($state) => filled($state))
                ->label('Password')
                ->helperText('Kosongkan saat edit jika tidak mengubah password'),

            Forms\Components\CheckboxList::make('roles')
                ->relationship('roles', 'name')
                ->options(fn() => Role::query()->pluck('name', 'name')->toArray())
                ->columns(2)
                ->label('Peran'),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('name')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('email')->sortable()->searchable(),
            Tables\Columns\BadgeColumn::make('roles.name')
                ->label('Peran')
                ->colors([
                    'primary',
                    'success' => 'pengurus',
                    'warning' => 'pengawas',
                    'info' => 'manajer',
                    'danger' => 'kebag_koorcab',
                ])
                ->separator(', '),
            Tables\Columns\TextColumn::make('pegawai.nama_lengkap')
                ->label('Pegawai')
                ->wrap(),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
