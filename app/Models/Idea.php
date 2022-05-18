<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


use Illuminate\Database\Eloquent\Relations\{
  BelongsTo,
  HasMany,
};

class Idea extends Model
{
  use HasFactory;
  use SoftDeletes;

  protected $table = 'idea';

  const CUSTOM_DATE_FORMAT = 'Y-m-d H:i:s';

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'rownum',
    'author_id',
    'title',
  ];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'created_at' => 'date:' . self::CUSTOM_DATE_FORMAT,
    'updated_at' => 'date:' . self::CUSTOM_DATE_FORMAT,
    'deleted_at' => 'date:' . self::CUSTOM_DATE_FORMAT,
  ];

  /**
   * Get paginator
   *
   * @param int $current_page
   * @param int $per_page
   * @return object
   */
  public static function getPaginator($current_page = 1, $per_page = 50)
  {
    $total_count = Idea::query()->max('rownum');
    $last_page   = floor($total_count / $per_page);
    $prev_page   = $current_page - 1;
    $next_page   = $current_page + 1;

    // paginate
    $pagination = [
      'per_page'     => $per_page,
      'current_page' => $current_page,
      'last_page'    => $last_page,
      'prev_page'    => ($prev_page >= 1) ? $prev_page : null,
      'next_page'    => ($next_page <= $last_page) ? $next_page : null,
    ];
    return (object) $pagination;
  }

  /**
   * Idea author
   */
  public function author(): BelongsTo
  {
    return $this->belongsTo(User::class, 'author_id', 'id');
  }
}
