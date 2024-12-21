<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'pelanggan';
    
    /**
     * primaryKey
     *
     * @var string
     */
    protected $primaryKey = 'id_pelanggan';
    
    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = ['nama', 'no_telp', 'id_pengguna'];
    
    /**
     * payments
     *
     * @return void
     */
    public function payments() {
        return $this->hasMany(Payment::class, 'id_pelanggan');
    }
    
    /**
     * scopeIsPaidForCurrentMonth
     *
     * @return void
     */
    public function scopeIsPaidForCurrentMonth($query, $month) {
        $query->whereHas('payments', function ($query) use ($month) {
            $startAt = now()->setMonth($month)->startOfMonth();
            $endAt = now()->setMonth($month)->endOfMonth();

            $query
                ->where('status', 'success')
                ->whereBetween('created_at', [$startAt, $endAt]);
        });
    }
    
    /**
     * scopeIsNotPaidForCurrentMonth
     *
     * @return void
     */
    public function scopeIsNotPaidForCurrentMonth($query, $month) {
        $query->whereDoesntHave('payments', function ($query) use ($month) {
            $startAt = now()->setMonth($month)->startOfMonth();
            $endAt = now()->setMonth($month)->endOfMonth();

            $query
                ->where('status', 'success')
                ->whereBetween('created_at', [$startAt, $endAt]);
        });
    }
    
    /**
     * user
     *
     * @return void
     */
    public function user() {
        return $this->belongsTo(User::class, 'id_pengguna');
    }
}
