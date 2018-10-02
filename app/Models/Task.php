<?php
/**
 * @copyright maki fujiwara <makies@gmail.com>
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

	/**
	 * @var string
	 */
	protected $primaryKey = 'task_id';

	/**
	 * 書き込み可能なカラム名
	 *
	 * @var array
	 */
	protected $fillable = [
		'title',
		'body',
	];
}
