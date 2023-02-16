<?php

namespace App\Filament\Resources;

use App\Enums\Role;
use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Form;
use Filament\Resources\Pages\CreateRecord;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Hash;
use Nuhel\FilamentCroppie\Components\Croppie;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()->schema([
                    TextInput::make('name')
                        ->required()
                        ->maxLength(225),
                    TextInput::make('last_name')
                        ->required()
                        ->maxLength(225),
                    TextInput::make('first_name')
                        ->required()
                        ->maxLength(225),
                    TextInput::make('nickname')
                        ->required()
                        ->maxLength(225),
                    TextInput::make('email')
                        ->label('Email Address')
                        ->required()
                        ->maxLength(225)
                        ->unique(),
                    TextInput::make('password')
                        ->password()
                        ->required(fn (Page $liveware): bool => $liveware instanceof CreateRecord)
                        ->minLength(8)
                        ->same('passwordConfirmation')
                        ->dehydrated(fn ($state) => filled($state))
                        ->dehydrateStateUsing(fn ($state) => Hash::make($state)),
                    TextInput::make('passwordConfirmation')
                        ->password()
                        ->label('Password confirmation')
                        ->required(fn (Page $liveware): bool => $liveware instanceof CreateRecord)
                        ->minLength(8)
                        ->dehydrated(false),
                    Select::make('authorId')
                        ->label('Author')
                        ->options(Role::class)
                        ->searchable(),
                    //FileUpload::make('avatar')->image()->nullable(),
                    Croppie::make('avatar')->avatar()
                        ->enableOpen()->enableDownload()
                        ->imageResizeTargetWidth('300')
                        ->imageResizeTargetHeight('300')
                        ->modalSize('xl'),

                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('last_name')->sortable()->searchable(),
                TextColumn::make('first_name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('nickname')->sortable()->searchable(),
                TextColumn::make('role')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
