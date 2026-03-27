<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DoctorResource\Pages;
use App\Models\Doctor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DoctorResource extends Resource
{
    protected static ?string $model = Doctor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'الدكتره';

    protected static ?string $pluralModelLabel = 'الدكتره';

    protected static ?string $modelLabel = 'اظاف دكتور';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('اسم الطبيب')
                    ->required()
                    ->maxLength(255),

                Forms\Components\TextInput::make('phone')
                    ->label('الهاتف')
                    ->tel(),

                Forms\Components\TextInput::make('specialization')
                    ->label('التخصص')
                    ->required(),

                Forms\Components\TextInput::make('license_number')
                    ->label('رقم الترخيص'),

                Forms\Components\Select::make('department_id')
                    ->label('القسم')
                    ->relationship('department', 'name')
                    ->required(),

                Forms\Components\Toggle::make('is_active')
                    ->label('مفعل')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('اسم الطبيب')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('specialization')->label('التخصص')->sortable(),
                Tables\Columns\TextColumn::make('department.name')->label('القسم')->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->label('الحالة')
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('الحالة'),
                Tables\Filters\SelectFilter::make('department_id')->relationship('department', 'name')->label('القسم'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Doctor $record): bool => static::canEdit($record)),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Doctor $record): bool => static::canDelete($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn (): bool => static::canDeleteAny()),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('doctors.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('doctors.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('doctors.update') ?? false;
    }

    public static function canDelete($record): bool
    {
        if ((int) $record->id === (int) Auth::id()) {
            return false;
        }

        return Auth::user()?->can('doctors.delete') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('doctors.deleteAny') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDoctors::route('/'),
            'create' => Pages\CreateDoctor::route('/create'),
            'edit' => Pages\EditDoctor::route('/{record}/edit'),
        ];
    }
}
