<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    use HasFactory;

    protected $fillable =['name', 'slug', 'store_code', 'location', 'description', 'discount', 'status'];
    
    //inisalisasi nama tabel
    protected $table = 'stores';
    
    //mengisi timestaps (created _at dan update_at) di data base
    public $timestamps = true;

    //inisialisasi primarykey pada tabel
    protected $primaryKey = 'id';
}
