<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Card;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-check';

    protected static ?string $navigationLabel = 'الواظيفة';

    protected static ?string $modelLabel = 'الواظيفة';

    protected static ?string $pluralModelLabel = 'الواظيفة عمل';

    protected static ?string $navigationGroup = 'إدارة المستخدمين';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Card::make()->schema([

                TextInput::make('name')
                    ->label('اسم الواظيفة')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->placeholder('مثال: admin'),

                CheckboxList::make('permissions')
                    ->relationship('permissions', 'name')
                    ->columns(3)
                    ->searchable()
                    ->bulkToggleable()
                    ->label('الصلاحيات'),

            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('واظيفة العمل')
                    ->sortable()
                    ->badge()
                    ->color('success'),

                TextColumn::make('permissions_count')
                    ->counts('permissions')
                    ->label('الصلاحيات')
                    ->badge()
                    ->color('info'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->label('تاريخ الإنشاء')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (Role $record): bool => static::canEdit($record)),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (Role $record): bool => static::canDelete($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn () => static::canDeleteAny()),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('roles.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('roles.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('roles.update') ?? false;
    }

    public static function canDelete($record): bool
    {
        return Auth::user()?->can('roles.delete') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('roles.delete') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => RoleResource\Pages\ListRoles::route('/'),
            'create' => RoleResource\Pages\CreateRole::route('/create'),
            'edit' => RoleResource\Pages\EditRole::route('/{record}/edit'),
        ];
    }
}
