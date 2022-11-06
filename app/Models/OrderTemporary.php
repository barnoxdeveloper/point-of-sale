<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderTemporary extends Model
{
    use HasFactory;

    protected $fillable =['user_id', 'product_name', 'product_code', 'price', 'quantity', 'sub_total', 'description'];
    
    // inisalisasi nama tabel
    protected $table = 'order_temporaries';
    
    // mengisi timestaps (created _at dan update_at) di data base
    public $timestamps = true;

    //inisialisasi primarykey pada tabel
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_code', 'product_code');
    }
}
