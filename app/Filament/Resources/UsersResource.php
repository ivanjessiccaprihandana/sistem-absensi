<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UsersResource\Pages;
use App\Filament\Resources\UsersResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// UsersResource adalah resource Filament yang digunakan untuk mengelola model User.
// Resource ini menyediakan UI untuk membuat, melihat, mengedit, dan menghapus data pengguna.
class UsersResource extends Resource
{
    // Properti statis ini menentukan model Eloquent yang akan dikelola oleh resource.
    // Resource ini akan berinteraksi dengan tabel 'users' di database.
    protected static ?string $model = User::class;

    // Properti statis ini menentukan ikon navigasi yang akan digunakan di sidebar Filament.
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Metode 'form' mendefinisikan skema formulir untuk membuat atau mengedit data pengguna.
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Membuat komponen input teks untuk nama pengguna.
                Forms\Components\TextInput::make('name')
                    // Memastikan input ini wajib diisi.
                    ->required()
                    // Memastikan nilai yang dimasukkan harus unik dalam tabel.
                    ->unique()
                    // Menentukan panjang maksimum input.
                    ->maxLength(255)
                    // Mengatur label yang akan ditampilkan di formulir.
                    ->label('Nama Pengguna'),

                // Membuat komponen input teks untuk email pengguna.
                Forms\Components\TextInput::make('email')
                    // Memvalidasi format input sebagai email.
                    ->email()
                    // Memastikan input ini wajib diisi.
                    ->required()
                    // Memastikan nilai yang dimasukkan harus unik.
                    ->unique()
                    // Menentukan panjang maksimum input.
                    ->maxLength(255)
                    // Mengatur label.
                    ->label('Email'),

                // Membuat komponen input kata sandi.
                Forms\Components\TextInput::make('password')
                    // Mengubah input menjadi tipe 'password' (karakter tersembunyi).
                    ->password()
                    // Memastikan input ini wajib diisi.
                    ->required()
                    // Menentukan panjang minimum input.
                    ->minLength(8)
                    // Menentukan panjang maksimum input.
                    ->maxLength(255)
                    // Menggunakan closure untuk mengenkripsi kata sandi sebelum disimpan.
                    // Ini adalah praktik keamanan standar.
                    ->dehydrateStateUsing(fn ($state) => bcrypt($state))
                    // Mengatur label.
                    ->label('Kata Sandi'),

                // Membuat komponen Select (dropdown) untuk memilih peran pengguna.
                Forms\Components\Select::make('roles')
                    // Mengisi opsi dropdown secara otomatis dari relasi 'roles'
                    // pada model User, menggunakan kolom 'name' sebagai label.
                    ->relationship('roles', 'name')
                    // Memastikan input ini wajib diisi.
                    ->required()
            ]);
    }

    // Metode 'table' mendefinisikan skema tabel untuk menampilkan daftar data pengguna.
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menambahkan kolom teks untuk menampilkan nama pengguna.
                Tables\Columns\TextColumn::make('name')
                    // Memungkinkan data di kolom ini diurutkan.
                    ->sortable()
                    // Memungkinkan pengguna untuk melakukan pencarian pada kolom ini.
                    ->searchable()
                    // Mengatur label.
                    ->label('Nama Pengguna'),

                // Menambahkan kolom teks untuk menampilkan email pengguna.
                Tables\Columns\TextColumn::make('email')
                    // Memungkinkan data diurutkan.
                    ->sortable()
                    // Memungkinkan data dicari.
                    ->searchable()
                    // Mengatur label.
                    ->label('Email'),
                    
                // Menambahkan kolom teks untuk menampilkan waktu pembuatan pengguna.
                Tables\Columns\TextColumn::make('created_at')
                    // Memformat tampilan tanggal dan waktu.
                    ->dateTime()
                    // Memungkinkan data diurutkan.
                    ->sortable()
                    // Memungkinkan data dicari.
                    ->searchable()
                    // Mengatur label.
                    ->label('Dibuat Pada'),
            ])
            ->filters([
                // Bagian ini digunakan untuk menambahkan filter tabel.
                // Saat ini tidak ada filter yang diterapkan.
            ])
            ->actions([
                // Menambahkan tombol 'Edit' untuk setiap baris data di tabel.
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Menambahkan grup aksi massal untuk mengelola beberapa baris data sekaligus.
                Tables\Actions\BulkActionGroup::make([
                    // Menambahkan aksi 'Delete' massal untuk menghapus beberapa baris terpilih.
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Metode 'getRelations' mendefinisikan manajer relasi untuk resource ini.
    // Saat ini, tidak ada relasi yang didefinisikan.
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    // Metode 'getPages' mendefinisikan halaman-halaman yang terkait dengan resource ini.
    // Ini menghubungkan resource dengan halaman-halaman default Filament.
    public static function getPages(): array
    {
        return [
            // Halaman 'index' menampilkan daftar pengguna dan diakses melalui rute '/'.
            'index' => Pages\ListUsers::route('/'),
            // Halaman 'create' digunakan untuk membuat pengguna baru dan diakses melalui rute '/create'.
            'create' => Pages\CreateUsers::route('/create'),
            // Halaman 'edit' digunakan untuk mengedit pengguna tertentu dan diakses melalui rute '/{record}/edit'.
            'edit' => Pages\EditUsers::route('/{record}/edit'),
        ];
    }
}