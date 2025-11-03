<?php

namespace App\Filament\Resources\PegawaiResource\RelationManagers;

use App\Models\OrgUnit;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class RiwayatJabatansRelationManager extends RelationManager
{
    protected static string $relationship = 'riwayatJabatans';
    protected static ?string $title = 'Riwayat Jabatan & Mutasi';

    public function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\DatePicker::make('tanggal')->label('Tanggal')->required()->maxDate(now()),
            Forms\Components\Select::make('jenis')->label('Jenis Perubahan')->options([
                'Promosi'=>'Promosi','Mutasi'=>'Mutasi','Rotasi'=>'Rotasi','Demosi'=>'Demosi'
            ])->required()->native(false),
            Forms\Components\TextInput::make('dari_jabatan')->label('Dari Jabatan'),
            Forms\Components\TextInput::make('ke_jabatan')->label('Ke Jabatan'),
            Forms\Components\Select::make('dari_org_unit_id')->label('Dari Unit')
                ->options(fn()=>OrgUnit::orderBy('name')->pluck('name','id'))->searchable()->preload(),
            Forms\Components\Select::make('ke_org_unit_id')->label('Ke Unit')
                ->options(fn()=>OrgUnit::orderBy('name')->pluck('name','id'))->searchable()->preload(),
            Forms\Components\Textarea::make('keterangan')->rows(3),
        ])->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('tanggal')->date()->label('Tanggal')->sortable(),
            Tables\Columns\BadgeColumn::make('jenis')->colors([
                'success'=>'Promosi','warning'=>'Mutasi','info'=>'Rotasi','danger'=>'Demosi'
            ]),
            Tables\Columns\TextColumn::make('dari_jabatan')->label('Dari Jabatan'),
            Tables\Columns\TextColumn::make('ke_jabatan')->label('Ke Jabatan'),
            Tables\Columns\TextColumn::make('dariUnit.name')->label('Dari Unit'),
            Tables\Columns\TextColumn::make('keUnit.name')->label('Ke Unit'),
            Tables\Columns\TextColumn::make('pencatat.name')->label('Dicatat Oleh')->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('keterangan')->limit(40),
        ])
        ->headerActions([
            Tables\Actions\CreateAction::make()
                ->mutateFormDataUsing(fn(array $data) => $data + ['dibuat_oleh'=>auth()->user->id()]),
        ])
        ->actions([
            Tables\Actions\EditAction::make()
                ->mutateFormDataUsing(fn(array $data) => $data + ['dibuat_oleh'=>auth()->user->id()]),
            Tables\Actions\DeleteAction::make(),
        ])
        ->defaultSort('tanggal','desc');
    }
}
