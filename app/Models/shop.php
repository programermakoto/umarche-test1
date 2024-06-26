<?php

namespace App\Models;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Owner;

class shop extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'name',
        'information',
        'filename',
        'is_selling',
    ];
    // ownerとshopをつなぐもの
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function product()
    {

        return $this->hasMany(Product::class);

    }
}
