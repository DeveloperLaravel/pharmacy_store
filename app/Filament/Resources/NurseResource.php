<?php

namespace App\Filament\Resources;

use App\Filament\Resources\NurseResource\Pages;
use App\Models\Nurses;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class NurseResource extends Resource
{
    protected static ?string $model = Nurses::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'الممرضات';

    protected static ?string $pluralModelLabel = 'الممرضات';

    protected static ?string $modelLabel = 'ممرضة';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->label('الاسم')
                ->required(),
            Forms\Components\TextInput::make('phone')
                ->label('رقم الهاتف'),
            Forms\Components\Select::make('gender')
                ->label('الجنس')
                ->options([
                    'male' => 'ذكر',
                    'female' => 'أنثى',
                ])
                ->required(),
            Forms\Components\Select::make('department_id')
                ->label('القسم')
                ->relationship('department', 'name')
                ->required(),
            Forms\Components\Toggle::make('is_active')
                ->label('نشطة')
                ->default(true)
                ->inline(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('#')->sortable(),
                Tables\Columns\TextColumn::make('name')->label('الاسم')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('رقم الهاتف'),
                Tables\Columns\TextColumn::make('gender')->label('الجنس'),
                Tables\Columns\TextColumn::make('department.name')->label('القسم'),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('نشطة')
                    ->boolean()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department_id')->relationship('department', 'name')->label('القسم'),
                Tables\Filters\SelectFilter::make('is_active')
                    ->label('نشطة')
                    ->options([
                        1 => 'نعم',
                        0 => 'لا',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->label('تعديل')
                    ->visible(fn (Nurses $record): bool => static::canEdit($record)),

                Tables\Actions\DeleteAction::make()->label('حذف')
                    ->visible(fn (Nurses $record): bool => static::canDelete($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف متعدد')
                    ->visible(fn () => static::canDeleteAny()),
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
        return Auth::user()?->can('nurses.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('nurses.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('nurses.update') ?? false;
    }

    public static function canDelete($record): bool
    {
        if ((int) $record->id === (int) Auth::id()) {
            return false;
        }

        return Auth::user()?->can('nurses.delete') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('nurses.deleteAny') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListNurses::route('/'),
            'create' => Pages\CreateNurse::route('/create'),
            'edit' => Pages\EditNurse::route('/{record}/edit'),
        ];
    }
}
