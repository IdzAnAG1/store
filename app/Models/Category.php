<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @method static whereNull(string $string)
 */
class Category extends Model
{
    protected $table = 'categories';
    protected $primaryKey = 'category_id';
    public $timestamps = false; // если в таблице нет created_at/updated_at

    protected $fillable = [
        'category_name', 'parent_id'
    ];

    // Дети (самоссылка)
    public function children()
    {
        return $this->hasMany(self::class, 'parent_category_id', 'category_id')
            ->with('children'); // для рекурсии в глубину по необходимости
    }

    // Родитель (на всякий случай)
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_category_id', 'category_id');
    }

}
