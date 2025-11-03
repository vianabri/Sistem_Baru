<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class ChangePassword extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-key';
    protected static string $view = 'filament.pages.change-password';
    protected static ?string $title = 'Ganti Password';

    public $current_password;
    public $new_password;
    public $confirm_password;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('current_password')
                    ->label('Password Lama')
                    ->password()
                    ->required(),

                TextInput::make('new_password')
                    ->label('Password Baru')
                    ->password()
                    ->required()
                    ->minLength(6),

                TextInput::make('confirm_password')
                    ->label('Konfirmasi Password Baru')
                    ->password()
                    ->required(),
            ])
            ->statePath('');
    }

    public function submit()
    {
        $user = Auth::user();

        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Password lama salah.');
            return;
        }

        if ($this->new_password !== $this->confirm_password) {
            $this->addError('confirm_password', 'Konfirmasi password tidak sama.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->new_password),
            'must_change_password' => false,
        ]);

        session()->flash('message', 'Password berhasil diperbarui.');
        return redirect('/admin');
    }
}
