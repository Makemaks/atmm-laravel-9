<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $fillable = ['product_name','product_price','sales_tax','nmi_api_plan_id'];

    public function nmiPayments()
    {
        return $this->hasMany(Nmipayments::class, 'plan_id', 'nmi_api_plan_id');
    }

}
