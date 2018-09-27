<?php
/**
 * @copyright makies <makies@gmail.com>
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Task
 *
 * @package App\Models
 */
class Task extends Model
{
    use SoftDeletes;
}
