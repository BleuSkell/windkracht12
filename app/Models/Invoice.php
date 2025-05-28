<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    /** @use HasFactory<\Database\Factories\InvoiceFactory> */
    use HasFactory;

    protected $table = 'invoices';

    protected $fillable = [
        'reservationId',
        'invoiceNumber',
        'amount',
        'status',
        'dueDate'
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class, 'reservationId');
    }
}
