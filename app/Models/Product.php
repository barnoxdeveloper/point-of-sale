<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable =['product_code', 'name', 'slug', 'category_id', 'store_id', 'old_price', 'new_price', 'limit_stock', 'stock', 'type', 'description', 'photo', 'status'];
    
    // inisalisasi nama tabel
    protected $table = 'products';
    
    // mengisi timestaps (created _at dan update_at) di data base
    public $timestamps = true;

    //inisialisasi primarykey pada tabel
    protected $primaryKey = 'id';

    // accessor untuk mengganti url photo di API
	public function getPhotoAttribute($value)
	{
		return url('storage/' . $value);
	}

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }
}
