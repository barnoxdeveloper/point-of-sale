<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;
    
    protected $fillable =['order_id', 'product_name', 'product_code', 'price', 'quantity', 'sub_total', 'description'];
    
    // inisalisasi nama tabel
    protected $table = 'order_details';
    
    // mengisi timestaps (created _at dan update_at) di data base
    public $timestamps = true;

    //inisialisasi primarykey pada tabel
    protected $primaryKey = 'id';

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }
}
