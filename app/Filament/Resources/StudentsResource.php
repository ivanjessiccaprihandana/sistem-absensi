<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Students;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\StudentsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\StudentsResource\RelationManagers;

// StudentsResource adalah resource Filament yang digunakan untuk mengelola model Students.
// Resource ini menyediakan UI untuk membuat, melihat, mengedit, dan menghapus data siswa.
class StudentsResource extends Resource
{
    // Properti statis ini menentukan model Eloquent yang akan dikelola oleh resource.
    // Resource ini akan berinteraksi dengan tabel 'students' di database.
    protected static ?string $model = Students::class;

    // Properti statis ini menentukan ikon navigasi yang akan digunakan di sidebar Filament.
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Metode 'form' mendefinisikan skema formulir untuk membuat atau mengedit data siswa.
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Menggunakan Card untuk mengelompokkan elemen formulir secara visual.
                Card::make()
                    ->schema([
                        // Membuat komponen input teks untuk nama siswa.
                        TextInput::make('name')
                            // Memastikan input ini wajib diisi.
                            ->required()
                            // Memastikan nilai yang dimasukkan harus unik dalam tabel.
                            ->unique(),
                        
                        // Membuat komponen Select (dropdown) untuk memilih kelas.
                        Select::make('class_id')
                            // Mengisi opsi dropdown secara otomatis dari relasi 'class'
                            // pada model Students, menggunakan kolom 'name' sebagai label.
                            ->relationship('class', 'name'),
                    ])
                    // Mengatur layout Card agar memiliki 2 kolom.
                    ->columns(2),
            ]);
    }

    // Metode 'table' mendefinisikan skema tabel untuk menampilkan daftar data siswa.
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menambahkan kolom teks yang menampilkan nama siswa.
                TextColumn::make('name')
                    // Memungkinkan data di kolom ini diurutkan.
                    ->sortable()
                    // Memungkinkan pengguna melakukan pencarian pada kolom ini.
                    ->searchable(),
                
                // Menambahkan kolom teks yang menampilkan nama kelas.
                // Filament secara cerdas mengakses relasi 'class' dan kolom 'name' di dalamnya.
                TextColumn::make('class.name')
                    // Mengatur label kolom menjadi 'kelas'.
                    ->label('kelas'),
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
    // Manajer relasi dapat digunakan untuk menampilkan data terkait (misalnya, daftar kehadiran siswa).
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
            // Halaman 'index' menampilkan daftar siswa dan diakses melalui rute '/'.
            'index' => Pages\ListStudents::route('/'),
            // Halaman 'create' digunakan untuk membuat siswa baru dan diakses melalui rute '/create'.
            'create' => Pages\CreateStudents::route('/create'),
            // Halaman 'edit' digunakan untuk mengedit siswa tertentu dan diakses melalui rute '/{record}/edit'.
            'edit' => Pages\EditStudents::route('/{record}/edit'),
        ];
    }
}