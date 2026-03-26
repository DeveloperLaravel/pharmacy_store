<?php

namespace App\Filament\Resources;

use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationIcon = 'heroicon-o-key';

    protected static ?string $navigationGroup = 'إدارة المستخدمين';

    public static function form(Form $form): Form
    {
        return $form->schema([

            Section::make('بيانات الصلاحية')
                ->schema([

                    TextInput::make('name')
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->placeholder('مثال: view_user'),

                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('id')->sortable(),

                TextColumn::make('name')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable(),

            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->before(fn ($record) => str_contains($record->name, 'admin')
                            ? throw new \Exception('لا يمكن حذف صلاحية مهمة')
                            : null
                    ),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => PermissionResource\Pages\ListPermissions::route('/'),
            'create' => PermissionResource\Pages\CreatePermission::route('/create'),
            'edit' => PermissionResource\Pages\EditPermission::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('view permissions') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('create permissions') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('edit permissions') ?? false;
    }

    public static function canDelete($record): bool
    {
        if ((int) $record->id === (int) Auth::id()) {
            return false;
        }

        return Auth::user()?->can('delete permissions') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('delete permissions') ?? false;
    }
}
