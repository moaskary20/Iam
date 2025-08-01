<?php

namespace App\Filament\Resources\UserProfileResource\RelationManagers;

use App\Models\Wallet;
use App\Models\WalletTransaction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components;

class WalletTransactionsRelationManager extends RelationManager
{
    protected static string $relationship = 'walletTransactions';
    protected static ?string $recordTitleAttribute = 'id';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('type')->label('النوع')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('amount')->label('المبلغ')->sortable(),
                Tables\Columns\TextColumn::make('fee')->label('العمولة'),
                Tables\Columns\TextColumn::make('description')->label('الوصف')->searchable(),
                Tables\Columns\TextColumn::make('created_at')->label('التاريخ')->dateTime('Y-m-d H:i')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->label('نوع العملية')
                    ->options([
                        'deposit' => 'إيداع',
                        'withdraw' => 'سحب',
                    ]),
                Tables\Filters\Filter::make('amount')
                    ->form([
                        Components\TextInput::make('amount_from')->label('من مبلغ'),
                        Components\TextInput::make('amount_to')->label('إلى مبلغ'),
                    ])
                    ->query(function ($query, $data) {
                        if ($data['amount_from']) {
                            $query->where('amount', '>=', $data['amount_from']);
                        }
                        if ($data['amount_to']) {
                            $query->where('amount', '<=', $data['amount_to']);
                        }
                    }),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Components\DatePicker::make('date_from')->label('من تاريخ'),
                        Components\DatePicker::make('date_to')->label('إلى تاريخ'),
                    ])
                    ->query(function ($query, $data) {
                        if ($data['date_from']) {
                            $query->whereDate('created_at', '>=', $data['date_from']);
                        }
                        if ($data['date_to']) {
                            $query->whereDate('created_at', '<=', $data['date_to']);
                        }
                    }),
            ]);
    }
}
