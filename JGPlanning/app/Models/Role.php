<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    //  TODO: Dit verbeteren naar iets meer dynamisch
    public static $roles = [
        'maintainer' => 1,
        'admin' => 2,
        'employee' => 3
    ];
//    public function getID(string $name)
//    {
//        $role_id = DB::table('roles')->select('id')->where('name', $name)->get();
//        return $role_id;
//    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id');
    }
}
