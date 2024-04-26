<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Call;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleCare extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sale_care';

     /**
     * Get the shippingOrder for the Orders.
     */
    // public function call(): HasMany
    // {
    //     return $this->HasMany(Call::class, 'id','result_call');
    // }
    public function call(): BelongsTo
    {
        return $this->belongsTo(Call::class, 'result_call', 'id');
    }
}
