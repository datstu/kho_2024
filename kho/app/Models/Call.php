<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SaleCare;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\belongsTo;

class Call extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'list_call';

    // /**
    //  * Get the author of the post.
    //  */
    // public function call(): BelongsTo
    // {
    //     return $this->belongsTo(SaleCare::class,'result_call');
    // }

    /**
     * Get the shippingOrder for the Orders.
     */
    public function ifCall(): belongsTo
    {
        return $this->belongsTo(CategoryCall::class, 'if_call');
    }

    public function thenCall(): belongsTo
    {
        return $this->belongsTo(CategoryCall::class, 'then_call');
    }
}
