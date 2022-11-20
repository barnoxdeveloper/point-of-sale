<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =['store_id', 'user_id', 'order_id', 'total', 'description', 'date', 'discount', 'total_bayar', 'kembalian'];
    
    // inisalisasi nama tabel
    protected $table = 'orders';
    
    // mengisi timestaps (created _at dan update_at) di data base
    public $timestamps = true;

    //inisialisasi primarykey pada tabel
    protected $primaryKey = 'id';

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function orderDetail()
    {
        return $this->hasMany(OrderDetail::class, 'order_id', 'order_id');
    }
}
