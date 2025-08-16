<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Subject;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SubjectResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SubjectResource\RelationManagers;

// SubjectResource adalah resource Filament yang digunakan untuk mengelola model Subject.
// Resource ini menyediakan UI untuk membuat, melihat, mengedit, dan menghapus data mata pelajaran.
class SubjectResource extends Resource
{
    // Properti statis ini menentukan model Eloquent yang akan dikelola oleh resource.
    // Resource ini akan berinteraksi dengan tabel 'subjects' di database.
    protected static ?string $model = Subject::class;

    // Properti statis ini menentukan ikon navigasi yang akan digunakan di sidebar Filament.
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Metode 'form' mendefinisikan skema formulir untuk membuat atau mengedit data mata pelajaran.
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Membuat komponen input teks untuk kolom 'nama'.
                TextInput::make('nama')
                    // Memastikan input ini wajib diisi.
                    ->required()
                    // Memastikan nilai yang dimasukkan harus unik dalam tabel.
                    ->unique(),
            ]);
    }

    // Metode 'table' mendefinisikan skema tabel untuk menampilkan daftar data mata pelajaran.
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menambahkan kolom teks yang menampilkan nilai dari kolom 'nama' model.
                Tables\Columns\TextColumn::make('nama')
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
    // Manajer relasi dapat digunakan untuk menampilkan data terkait.
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
            // Halaman 'index' menampilkan daftar mata pelajaran dan diakses melalui rute '/'.
            'index' => Pages\ListSubjects::route('/'),
            // Halaman 'create' digunakan untuk membuat mata pelajaran baru dan diakses melalui rute '/create'.
            'create' => Pages\CreateSubject::route('/create'),
            // Halaman 'edit' digunakan untuk mengedit mata pelajaran tertentu dan diakses melalui rute '/{record}/edit'.
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }
}