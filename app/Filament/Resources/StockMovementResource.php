<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StockMovementResource\Pages;
use App\Models\StockMovement;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class StockMovementResource extends Resource
{
    protected static ?string $model = StockMovement::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $navigationGroup = 'إدارة الصيدلية';

    protected static ?string $navigationLabel = 'حركات المخزون';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('medicine_id')
                ->relationship('medicine', 'name')
                ->searchable()
                ->preload()
                ->required(),

            Select::make('user_id')
                ->relationship('user', 'name')
                ->searchable()
                ->preload(),

            Select::make('type')
                ->options([
                    'in' => 'إدخال',
                    'out' => 'إخراج',
                    'adjustment' => 'تسوية',
                ])
                ->required(),

            TextInput::make('quantity')
                ->required()
                ->numeric()
                ->integer()
                ->minValue(1),

            TextInput::make('reference')
                ->maxLength(255),

            Textarea::make('notes')
                ->rows(4)
                ->columnSpanFull(),

            DateTimePicker::make('performed_at')
                ->required()
                ->default(now()),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),

                TextColumn::make('medicine.name')
                    ->label('الدواء')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('type')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        default => 'warning',
                    }),

                TextColumn::make('quantity')
                    ->sortable()
                    ->badge(),

                TextColumn::make('user.name')
                    ->label('تم بواسطة')
                    ->toggleable(),

                TextColumn::make('performed_at')
                    ->dateTime()
                    ->sortable(),

                TextColumn::make('reference')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'in' => 'إدخال',
                        'out' => 'إخراج',
                        'adjustment' => 'تسوية',
                    ]),
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
        return Auth::user()?->can('view stock-movements') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('create stock-movements') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('edit stock-movements') ?? false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->can('delete stock-movements') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('delete stock-movements') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStockMovements::route('/'),
            'create' => Pages\CreateStockMovement::route('/create'),
            'edit' => Pages\EditStockMovement::route('/{record}/edit'),
        ];
    }
}
