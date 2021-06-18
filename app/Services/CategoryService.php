<?php


namespace App\Services;


class CategoryService
{
    public function getCategories()
    {
        return (new FacturascriptService())->get('familias');
    }
}
