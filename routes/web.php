<?php

use Illuminate\Support\Facades\Route;
use Helaplus\Laravelhelaplus\Http\B2BPaymentController;

Route::post('/helaplusB2B/c2bReceiver', [B2BPaymentController::class, 'c2bReceiver'])->name('helaplusB2B.c2bReceiver');
Route::post('/helaplusb2b/revenueSettlementResponse', [B2BPaymentController::class, 'revenueSettlementResponse'])->name('helaplusB2B.revenueSettlementResponse');
Route::post('/helaplusb2b/b2bTransferResponse', [B2BPaymentController::class, 'b2bTransferResponse'])->name('helaplusB2B.b2bTransferResponse');
Route::post('helaplusb2b/b2bMmfToUtlityTransferResponse', [B2BPaymentController::class, 'b2bMmfToUtlityTransferResponse'])->name('helaplusB2B.b2bMmfToUtlityTransferResponse');