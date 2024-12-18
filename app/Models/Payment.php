<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pembayaran';

    protected $primaryKey  = 'id_pembayaran';
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['id_pelanggan', 'kode_pembayaran', 'status', 'snap_url'];
    
    /**
     * pelanggan
     *
     * @return void
     */
    public function pelanggan() : BelongsTo {
        return $this->belongsTo(Customer::class, 'id_pelanggan');
    }
}
