<?php

namespace App\Models;

use App\Enum\OperationEnum;
use App\Enum\StatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'status' => StatusEnum::class,
        'operation' => OperationEnum::class,
    ];

    /**
     * Get the cost center that owns the transaction.
     */
    public function costCenter(): BelongsTo
    {
        return $this->belongsTo(CostCenter::class);
    }

    /**
     * Get the account plan that owns the transaction.
     */
    public function accountPlan(): BelongsTo
    {
        return $this->belongsTo(AccountPlan::class);
    }
}
