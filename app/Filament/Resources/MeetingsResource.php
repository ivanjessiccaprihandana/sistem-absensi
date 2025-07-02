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

class MeetingsResource extends Resource
{
    protected static ?string $model = Meetings::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('subject_id')
                    ->relationship('subject', 'nama'),
                Forms\Components\Select::make('class_id')
                    ->relationship('class', 'name'),
                Forms\Components\DatePicker::make('meeting_date'),
                Forms\Components\TextInput::make('meeting_number')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('subject.nama')->label('Mata pelajaran'),
                TextColumn::make('class.name')->label('Kelas'),
                TextColumn::make('meeting_date')->label('Tanggal pertemuan'),
                TextColumn::make('meeting_number')->label('pertemuan')
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMeetings::route('/'),
            'create' => Pages\CreateMeetings::route('/create'),
            'edit' => Pages\EditMeetings::route('/{record}/edit'),
        ];
    }
}
