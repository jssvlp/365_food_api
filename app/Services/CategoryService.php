<?php


namespace App\Services;


class CategoryService extends FacturascriptService
{
    public function getCategories()
    {
        $categories = collect($this->get('familias'));

        $categories = $categories->filter( function($value, $key){
            return $value->codfamilia != 11;
        });

        return $categories;
    }
}
