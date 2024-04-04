<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShippingOrder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Orders extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'orders';

     /**
     * Get the shippingOrder for the Orders.
     */
    public function shippingOrder(): HasMany
    {
        return $this->HasMany(ShippingOrder::class, 'order_id');
    }
}
