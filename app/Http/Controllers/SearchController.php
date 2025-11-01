<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function suggest(Request $request)
    {
        $q = trim((string)$request->query('q', ''));
        if ($q === '') {
            return response()->json([]);
        }

        // Разбор на термы и префиксный boolean-запрос для автодополнения
        $terms = preg_split('/\s+/u', mb_strtolower($q), -1, PREG_SPLIT_NO_EMPTY);
        $bool  = implode(' ', array_map(fn($t) => '+' . $this->escapeBoolean($t) . '*', $terms));
        $prefix = mb_strtolower($terms[0] ?? $q) . '%';

        // Основной запрос: FULLTEXT по products + FULLTEXT по categories, буст точных/префиксных совпадений
        $items = DB::table('products as p')
            ->leftJoin('categories as c', 'c.category_id', '=', 'p.category_id')
            // Соберём атрибуты для fallback LIKE (в ранжировании не используем — нет индекса)
            ->leftJoin(DB::raw('
                (SELECT pa.product_id, GROUP_CONCAT(CONCAT(pa.attribute_name, " ", pa.value) SEPARATOR " ") AS attrs
                   FROM product_attributes pa
                 GROUP BY pa.product_id) as pa
            '), 'pa.product_id', '=', 'p.product_id')
            ->selectRaw("
                p.product_id as id,
                p.name,
                p.price,
                -- FULLTEXT score по товарам
                IFNULL(MATCH(p.name, p.description) AGAINST (? IN NATURAL LANGUAGE MODE), 0) AS ft_score,
                -- FULLTEXT score по категории
                IFNULL(MATCH(c.category_name) AGAINST (? IN NATURAL LANGUAGE MODE), 0) AS cat_score,
                -- Бусты: точное имя / префикс по первому слову
                (CASE
                    WHEN LOWER(p.name) = ? THEN 20
                    WHEN LOWER(p.name) LIKE ? THEN 8
                    ELSE 0
                 END) AS exact_boost,
                -- Маленький буст за наличие на складе
                (p.stock_quantity > 0) AS in_stock_boost
            ", [
                $q,        // NATURAL LANGUAGE по products
                $q,        // NATURAL LANGUAGE по categories
                mb_strtolower($q),  // точное имя
                $prefix,            // префикс
            ])
            ->where(function ($w) use ($bool) {
                // Префиксный FULLTEXT: хотя бы где-то совпало
                $w->whereRaw("MATCH(p.name, p.description) AGAINST (? IN BOOLEAN MODE)", [$bool])
                    ->orWhereRaw("MATCH(c.category_name) AGAINST (? IN BOOLEAN MODE)", [$bool]);
            })
            ->orderByRaw("(ft_score*2 + cat_score*1.2 + exact_boost + in_stock_boost*0.5) DESC")
            ->orderBy('p.name')
            ->limit(12)
            ->get(['id','name','price']);

        // Fallback: если FULLTEXT ничего не дал — мягкий LIKE по всем полям + атрибутам
        if ($items->isEmpty()) {
            $items = DB::table('products as p')
                ->leftJoin('categories as c', 'c.category_id', '=', 'p.category_id')
                ->leftJoin(DB::raw('
                    (SELECT pa.product_id, GROUP_CONCAT(CONCAT(pa.attribute_name, " ", pa.value) SEPARATOR " ") AS attrs
                       FROM product_attributes pa
                     GROUP BY pa.product_id) as pa
                '), 'pa.product_id', '=', 'p.product_id')
                ->where(function ($w) use ($terms) {
                    foreach ($terms as $t) {
                        $like = '%' . $t . '%';
                        $w->where(function ($q2) use ($like) {
                            $q2->orWhere('p.name', 'like', $like)
                                ->orWhere('p.description', 'like', $like)
                                ->orWhere('c.category_name', 'like', $like)
                                ->orWhere('pa.attrs', 'like', $like);
                        });
                    }
                })
                ->orderByRaw('(p.stock_quantity > 0) DESC, p.name')
                ->limit(12)
                ->get(['p.product_id as id','p.name','p.price']);
        }

        return response()->json($items);
    }

    private function escapeBoolean(string $t): string
    {
        // Уберём символы boolean-режима: + - @ ( ) ~ * " < >
        return preg_replace('/[+\-@()<~*\"<>]/', ' ', $t);
    }
}
