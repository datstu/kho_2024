<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SaleCare;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\DetailUserGroup;
use App\Models\SrcPage;

class GroupSale extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'group_sale';

    public function sales()
    {
        return $this->hasMany(DetailUserGroupSale::class, 'id_group_sale');
    }

    // public function srcs()
    // {
    //     return $this->hasMany(SrcPage::class, 'id_group');
    // }

    // public function products()
    // {
    //     return $this->hasMany(DetailProductGroup::class, 'id_group');
    // }

    // public function leadSale(): BelongsTo
    // {
    //     return $this->belongsTo(User::class, 'lead_sale', 'id');
    // }
}
