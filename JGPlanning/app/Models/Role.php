<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;

    public $maintainer;

    protected $fillable = [
        'name'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->maintainer = 1;
    }
    
    /**
     * Get the id of a role. When an incorrect role name has been given it will, on default, throw an error.
     * If turned off it will give a value of 3, 'Employee' ID, or another value when given
     *
     * @param string $role_name
     * @param bool $throw_error
     * @param int $fallback_id
     * @return int|null
     * @throws Exception
     */
    public static function getRoleID(string $role_name, bool $throw_error = true, int $fallback_id = 3): ?int
    {
        try {
            return DB::table('roles')->select('id')->where('name', $role_name)->get()->first()->id;
        } catch (Exception $exception) {
            if($throw_error) {
                throw new Exception($exception->getMessage());
            } else {
                return $fallback_id;
            }
        }
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
