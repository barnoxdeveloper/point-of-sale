<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable =['user_id', 'order_id', 'total', 'description', 'date'];
    
    // inisalisasi nama tabel
    protected $table = 'orders';
    
    // mengisi timestaps (created _at dan update_at) di data base
    public $timestamps = true;

    //inisialisasi primarykey pada tabel
    protected $primaryKey = 'id';

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
