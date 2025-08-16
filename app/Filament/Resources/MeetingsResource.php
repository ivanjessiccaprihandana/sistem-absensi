<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Meetings;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MeetingsResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MeetingsResource\RelationManagers;

// MeetingsResource adalah resource Filament yang digunakan untuk mengelola model Meetings.
// Resource ini menyediakan UI untuk membuat, melihat, mengedit, dan menghapus data pertemuan.
class MeetingsResource extends Resource
{
    // Properti statis ini menentukan model Eloquent yang akan dikelola oleh resource.
    // Filament akan menggunakan model ini untuk berinteraksi dengan tabel 'meetings' di database.
    protected static ?string $model = Meetings::class;

    // Properti statis ini menentukan ikon navigasi yang akan digunakan di sidebar Filament.
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    // Metode 'form' mendefinisikan skema formulir untuk membuat atau mengedit data pertemuan.
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Membuat komponen Select (dropdown) untuk memilih mata pelajaran.
                Forms\Components\Select::make('subject_id')
                    // Mengisi opsi dropdown secara otomatis dari relasi 'subject'
                    // pada model Meetings, menggunakan kolom 'nama' sebagai label.
                    ->relationship('subject', 'nama'),

                // Membuat komponen Select (dropdown) untuk memilih kelas.
                Forms\Components\Select::make('class_id')
                    // Mengisi opsi dropdown dari relasi 'class' pada model Meetings,
                    // menggunakan kolom 'name' sebagai label.
                    ->relationship('class', 'name'),

                // Membuat komponen DatePicker untuk memilih tanggal pertemuan.
                Forms\Components\DatePicker::make('meeting_date'),

                // Membuat komponen TextInput untuk nomor pertemuan.
                Forms\Components\TextInput::make('meeting_number')
                    // Memastikan input hanya menerima nilai numerik.
                    ->numeric(),
            ]);
    }

    // Metode 'table' mendefinisikan skema tabel untuk menampilkan daftar data pertemuan.
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Menambahkan kolom teks yang menampilkan nama mata pelajaran.
                // Filament secara cerdas mengakses relasi 'subject' dan kolom 'nama'.
                TextColumn::make('subject.nama')->label('Mata pelajaran'),

                // Menambahkan kolom teks yang menampilkan nama kelas.
                // Filament mengakses relasi 'class' dan kolom 'name'.
                TextColumn::make('class.name')->label('Kelas'),

                // Menambahkan kolom teks untuk menampilkan tanggal pertemuan.
                TextColumn::make('meeting_date')->label('Tanggal pertemuan'),
                
                // Menambahkan kolom teks untuk menampilkan nomor pertemuan.
                TextColumn::make('meeting_number')->label('pertemuan')
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
    // Manajer relasi dapat digunakan untuk menampilkan data terkait (misalnya, daftar kehadiran).
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
            // Halaman 'index' menampilkan daftar pertemuan dan diakses melalui rute '/'.
            'index' => Pages\ListMeetings::route('/'),
            // Halaman 'create' digunakan untuk membuat pertemuan baru dan diakses melalui rute '/create'.
            'create' => Pages\CreateMeetings::route('/create'),
            // Halaman 'edit' digunakan untuk mengedit pertemuan tertentu dan diakses melalui rute '/{record}/edit'.
            'edit' => Pages\EditMeetings::route('/{record}/edit'),
        ];
    }
}