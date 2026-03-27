<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'حسابات';

    protected static ?string $navigationGroup = 'إدارة المستخدمين';

    protected static ?string $modelLabel = 'اظافة حسابات';

    protected static ?string $pluralModelLabel = 'إدارة حسابات';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                        ->label('الاسم الكامل')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('email')
                        ->label('البريد الإلكتروني')
                        ->email()
                        ->required()
                        ->unique(ignoreRecord: true)
                        ->maxLength(255),

                    TextInput::make('password')
                        ->label('كلمة المرور')
                        ->password()
                        ->revealable()
                        ->rule(Password::defaults())
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->dehydrated(fn (?string $state): bool => filled($state))
                        ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                        ->same('password_confirmation'),

                    TextInput::make('password_confirmation')
                        ->label('تأكيد كلمة المرور')
                        ->password()
                        ->revealable()
                        ->required(fn (string $operation): bool => $operation === 'create')
                        ->dehydrated(false),

                    Toggle::make('is_active')
                        ->label('نشط')
                        ->default(true)
                        ->inline(false),

                    Select::make('roles')
                        ->label('الواظيفة')
                        ->relationship('roles', 'name')
                        ->multiple()
                        ->preload()
                        ->searchable(),
                ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->label('اسم')

                    ->sortable()
                    ->weight('medium'),

                TextColumn::make('email')
                    ->searchable()
                    ->label('البريد الكترواني')

                    ->sortable()
                    ->copyable(),

                TextColumn::make('roles.name')
                    ->label('الواظيفة')
                    ->badge()
                    ->separator(',')
                    ->color('info'),

                IconColumn::make('is_active')
                    ->label('نشط')
                    ->boolean()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('تاريخ الإنشاء')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('الحالة')
                    ->trueLabel('نشط')
                    ->falseLabel('غير نشط')
                    ->native(false),

                SelectFilter::make('roles')
                    ->label('الواظيفة')
                    ->relationship('roles', 'name')
                    ->multiple()
                    ->preload(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn (User $record): bool => static::canEdit($record)),
                Tables\Actions\DeleteAction::make()
                    ->visible(fn (User $record): bool => static::canDelete($record)),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->visible(fn (): bool => static::canDeleteAny()),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->with('roles'));
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->can('users.view') ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->can('users.create') ?? false;
    }

    public static function canEdit($record): bool
    {
        return Auth::user()?->can('users.update') ?? false;
    }

    public static function canDelete($record): bool
    {
        if ((int) $record->id === (int) Auth::id()) {
            return false;
        }

        return Auth::user()?->can('users.delete') ?? false;
    }

    public static function canDeleteAny(): bool
    {
        return Auth::user()?->can('users.deleteAny') ?? false;
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
