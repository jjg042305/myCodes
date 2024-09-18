<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

 /**
 * App\ReferralProgram
 *
 * @property int $id
 * @property string|null $program_name
 * @property string|null $program_title
 * @property int|null $partner_id
 * @property bool|null $is_active
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Partner|null $partner
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ReferralProgram query()
 * @mixin \Eloquent
 */
class ReferralProgram extends Model
{
    protected $table = 'referral_program'; 
    protected $fillable = [
        'program_name',
        'program_title',
        'partner_id',
        'is_active',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    

    public function partner () : BelongsTo {
        return $this->belongsTo(Partner::class, 'partner_id', 'id');
    }
}
