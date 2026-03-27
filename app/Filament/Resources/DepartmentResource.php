<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartmentResource\Pages;
use App\Models\Department;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class DepartmentResource extends Resource
{
    protected static ?string $model = Department::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationLabel = 'القسام';

    protected static ?string $pluralModelLabel = 'قسام';

    protected static ?string $modelLabel = 'اظاف قسم';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Section::make('معلومات القسم')
                    ->description('أدخل بيانات القسم بشكل دقيق')
                    ->icon('heroicon-o-building-office')
                    ->schema([

                        Forms\Components\TextInput::make('name')
                            ->label('اسم القسم')
                            ->placeholder('مثال: قسم الطوارئ')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->live(onBlur: true),

                        Forms\Components\Textarea::make('description')
                            ->label('الوصف')
                            ->placeholder('وصف مختصر عن القسم...')
                            ->rows(4)
                            ->columnSpanFull(),

                        Forms\Components\Toggle::make('is_active')
                            ->label('مفعل')
                            ->default(true)
                            ->inline(false),

                    ])->columns(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                // 🆔 ID
                Tables\Columns\TextColumn::make('id')
                    ->label('#')
                    ->sortable()
                    ->toggleable(),

                // 📛 اسم القسم
                Tables\Columns\TextColumn::make('name')
                    ->label('اسم القسم')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(30),

                // 📝 الوصف
                Tables\Columns\TextColumn::make('description')
                    ->label('الوصف')
                    ->limit(50)
                    ->toggleable(),

                // 🟢 الحالة
                Tables\Columns\IconColumn::make('is_active')
                    ->label('الحالة')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),

                // 📅 تاريخ الإنشاء
                Tables\Columns\TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime('Y-m-d')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

            ])

            ->filters([

                // 🔍 فلترة حسب الحالة
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('الحالة'),

            ])

            ->actions([

                // ✏️ تعديل
                Tables\Actions\EditAction::make()
                    ->visible(fn (Department $record): bool => static::canEdit($record)),

                // 🗑️ حذف
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Department $record): bool => static::canDelete($record)),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDepartments::route('/'),
            'create' => Pages\CreateDepartment::route('/create'),
            'edit' => Pages\EditDepartment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('departments.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('departments.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('departments.update') ?? false;
    }

    public static function canDelete($record): bool
    {
        if ((int) $record->id === (int) Auth::id()) {
            return false;
        }

        return Auth::user()?->can('department.delete') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('department.delete') ?? false;
    }
}
