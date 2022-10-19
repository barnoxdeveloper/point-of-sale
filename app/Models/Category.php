<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable =['name', 'slug', 'store_id', 'photo', 'status'];
    
    //inisalisasi nama tabel
    protected $table = 'categories';
    
    //mengisi timestaps (created _at dan update_at) di data base
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
}
