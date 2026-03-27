<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PatientResource\Pages;
use App\Models\Patient;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class PatientResource extends Resource
{
    protected static ?string $model = Patient::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'المرضى';

    protected static ?string $modelLabel = 'مريض';

    protected static ?string $pluralModelLabel = 'المرضى';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('بيانات المريض')
                ->schema([

                    Forms\Components\TextInput::make('name')
                        ->label('الاسم الكامل')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\TextInput::make('national_id')
                        ->label('الرقم تعريف'),

                    Forms\Components\TextInput::make('phone')
                        ->label('رقم الهاتف'),

                    Forms\Components\Select::make('gender')
                        ->label('الجنس')
                        ->options([
                            'male' => 'ذكر',
                            'female' => 'أنثى',
                        ])
                        ->placeholder('اختر الجنس'),

                    Forms\Components\Select::make('blood_type')
                        ->label('فصيلة الدم')
                        ->options([
                            'A+' => 'A+',
                            'A-' => 'A-',
                            'B+' => 'B+',
                            'B-' => 'B-',
                            'AB+' => 'AB+',
                            'AB-' => 'AB-',
                            'O+' => 'O+',
                            'O-' => 'O-',
                        ])
                        ->searchable(),

                    Forms\Components\Textarea::make('address')
                        ->label('العنوان')
                        ->rows(3),

                    Forms\Components\TextInput::make('balance')
                        ->label('الرصيد')
                        ->numeric()
                        ->prefix('د.ل')
                        ->disabled(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('الاسم')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('phone')
                    ->label('رقم الهاتف'),

                Tables\Columns\TextColumn::make('gender')
                    ->label('الجنس')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                        default => '-',
                    }),

                Tables\Columns\TextColumn::make('blood_type')
                    ->label('فصيلة الدم'),

                Tables\Columns\TextColumn::make('balance')
                    ->label('الرصيد')
                    ->money('LYD', true),

            ])
            ->filters([
                Tables\Filters\SelectFilter::make('gender')
                    ->label('الجنس')
                    ->options([
                        'male' => 'ذكر',
                        'female' => 'أنثى',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->label('عرض'),
                Tables\Actions\EditAction::make()->label('تعديل')
                    ->visible(fn (Patient $record): bool => static::canEdit($record)),

                Tables\Actions\DeleteAction::make()->label('حذف')
                    ->visible(fn (Patient $record): bool => static::canDelete($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()->label('حذف المحدد')
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
        return Auth::user()?->can('patients.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('patients.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('patients.update') ?? false;
    }

    public static function canDelete($record): bool
    {
        if ((int) $record->id === (int) Auth::id()) {
            return false;
        }

        return Auth::user()?->can('patients.delete') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('patients.delete') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPatients::route('/'),
            'create' => Pages\CreatePatient::route('/create'),
            'edit' => Pages\EditPatient::route('/{record}/edit'),
        ];
    }
}
