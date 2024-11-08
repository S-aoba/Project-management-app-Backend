<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class InviteCode extends Model
{
    use HasFactory;

    protected $primaryKey = 'code';
    protected $keyType = 'string';
    public $incrementing = false;

    // テーブルカラムにupdated_atはないので、
    // Elonquent上でのupdated_atのデータの挿入をなしにする設定
    public const UPDATED_AT = null;

    protected $fillable = [
        'code',
        'project_id'
    ];

    public static function generateInviteCode()
    {
        $inviteCode = Str::uuid();
        return $inviteCode;
    }
}
