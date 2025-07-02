<?php

namespace App\Filament\Resources\ClassesResource\Pages;

use App\Filament\Resources\ClassesResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateClasses extends CreateRecord
{
    protected static string $resource = ClassesResource::class;

    //buat jika sudah create kembali ke halaman index
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
