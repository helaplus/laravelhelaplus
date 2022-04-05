<?php

namespace Helaplus\Laravelhelaplus\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    private $table = 'helaplus_transaction';
    use HasFactory;
}
