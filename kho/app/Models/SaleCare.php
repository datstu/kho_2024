<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Call;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Orders;

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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assign_user', 'id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Orders::class, 'id_order', 'id');
    }

    public function orderNew(): BelongsTo
    {
        return $this->belongsTo(Orders::class, 'id_order_new', 'id');
    }
}
