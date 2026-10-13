<?php

namespace Azuriom\Plugin\Birthday\Models;

use Azuriom\Models\Traits\HasTablePrefix;
use Azuriom\Models\User;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $year
 * @property \Carbon\Carbon $created_at
 * @property \Azuriom\Models\User $user
 */
class Claim extends Model
{
    use HasTablePrefix;

    protected string $prefix = 'birthday_';

    protected $fillable = ['user_id', 'year'];

    protected $casts = [
        'year' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
