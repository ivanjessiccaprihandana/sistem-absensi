<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClassesResource\Pages;
use App\Filament\Resources\ClassesResource\RelationManagers;
use App\Models\Classes;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

// ClassesResource adalah resource Filament yang digunakan untuk mengelola model Classes.
class ClassesResource extends Resource
{
    // Properti statis ini mendefinisikan model Eloquent yang akan dikelola oleh resource.
    // Dalam kasus ini, resource ini akan berinteraksi dengan model Classes.
    protected static ?string $model = Classes::class;

    // Properti statis ini menentukan ikon navigasi yang akan digunakan di sidebar Filament.
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Metode 'form' mendefinisikan skema formulir untuk membuat atau mengedit data kelas.
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Membuat komponen input teks untuk kolom 'name'.
                Forms\Components\TextInput::make('name')
                    // Memastikan input ini wajib diisi.
                    ->required()
                    // Memastikan nilai yang dimasukkan harus unik dalam tabel.
                    ->unique()
                    // Mengatur label yang akan ditampilkan di formulir.
                    ->label('Nama Kelas'),
            ]);
    }

    // Metode 'table' mendefinisikan skema tabel untuk menampilkan daftar data kelas.
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menambahkan kolom teks yang menampilkan nilai dari kolom 'name' model.
                Tables\Columns\TextColumn::make('name')
                    // Memungkinkan pengguna untuk melakukan pencarian pada kolom ini.
                    ->searchable(),
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
            // Halaman 'index' menampilkan daftar kelas dan diakses melalui rute '/'.
            'index' => Pages\ListClasses::route('/'),
            // Halaman 'create' digunakan untuk membuat kelas baru dan diakses melalui rute '/create'.
            'create' => Pages\CreateClasses::route('/create'),
            // Halaman 'edit' digunakan untuk mengedit kelas tertentu dan diakses melalui rute '/{record}/edit'.
            'edit' => Pages\EditClasses::route('/{record}/edit'),
        ];
    }
}