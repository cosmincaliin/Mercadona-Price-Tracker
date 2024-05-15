<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['_id', 'slug', 'published', 'thumbnail', 'display_name', 'historic_price'];
    
    

    public function updatePriceHistory($newPrice)
    {
        $historic = $this->historic_price ? json_decode($this->historic_price, true) : [];
        $historic[] = ['date' => now()->toDateString(), 'price' => $newPrice];
        $this->historic_price = json_encode($historic);
    }
}
