<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MedicineResource\Pages;
use App\Models\Medicine;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class MedicineResource extends Resource
{
    protected static ?string $model = Medicine::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    protected static ?string $navigationGroup = 'إدارة الصيدلية';

    protected static ?string $navigationLabel = 'الأدوية';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('category_id')
                ->relationship('category', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('name')
                ->required()
                ->maxLength(255),

            TextInput::make('scientific_name')
                ->maxLength(255),

            TextInput::make('sku')
                ->required()
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            TextInput::make('barcode')
                ->unique(ignoreRecord: true)
                ->maxLength(255),

            TextInput::make('dosage_form')
                ->required()
                ->maxLength(255),

            TextInput::make('strength')
                ->maxLength(255),

            TextInput::make('unit')
                ->required()
                ->maxLength(255),

            TextInput::make('price')
                ->required()
                ->numeric()
                ->minValue(0),

            TextInput::make('cost')
                ->numeric()
                ->minValue(0),

            TextInput::make('quantity')
                ->required()
                ->numeric()
                ->integer()
                ->minValue(0),

            TextInput::make('reorder_level')
                ->required()
                ->numeric()
                ->integer()
                ->minValue(0),

            DatePicker::make('expires_at'),

            Toggle::make('requires_prescription')
                ->default(false),

            Toggle::make('is_active')
                ->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('info'),

                TextColumn::make('category.name')
                    ->label('التصنيف')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('sku')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('price')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('quantity')
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),

                IconColumn::make('requires_prescription')
                    ->boolean(),

                IconColumn::make('is_active')
                    ->boolean()
                    ->label('نشط'),

                TextColumn::make('expires_at')
                    ->date()
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TernaryFilter::make('is_active')->label('نشط'),
                Tables\Filters\TernaryFilter::make('requires_prescription')->label('يتطلب وصفة'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->visible(fn () => static::canEdit(null)),
                Tables\Actions\DeleteAction::make()->visible(fn () => static::canDelete(null)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->visible(fn () => static::canDeleteAny()),
            ]);
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('view medicines') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('create medicines') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('edit medicines') ?? false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->can('delete medicines') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('delete medicines') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMedicines::route('/'),
            'create' => Pages\CreateMedicine::route('/create'),
            'edit' => Pages\EditMedicine::route('/{record}/edit'),
        ];
    }
}
