<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = ['path'];

    protected $appends = ['url'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
