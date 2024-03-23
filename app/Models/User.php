<?php

namespace App\Models;

use App\Constants\AppConstant;
use DateTime;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 
        'gender', 
        'name', 
        'location', 
        'age'
    ];

    public static function getAverageByGender(DateTime $before, DateTime $now) {
        $avgAge = self::whereBetween('created_at', [$before, $now])
            ->groupBy('gender')
            ->orderBy('gender')
            ->take(AppConstant::GET_BASE_LIMIT())
            ->selectRaw('gender, COALESCE(AVG(age), 0) as average_age')
            ->get();

        if(count($avgAge) < 1) {
            // There's no data between that interval days, empty data.
            throw new Exception('User data is empty');
        }        
        return $avgAge;
    }
}
