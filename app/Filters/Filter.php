<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

abstract class Filter
{
    protected Builder $builder;
    protected array $request;

    public function __construct(Builder $builder, array $request)
    {
        $this->builder = $builder;
        $this->request = $request;
    }

    public function apply(): Builder
    {
        foreach ($this->request as $function_name => $values) {
            if (is_null($values) || $values === '') {
                continue;
            }

            $function_name = Str::camel($function_name);

            if (method_exists($this, $function_name)) {
                $this->$function_name($values);
            }
        }

        return $this->builder;
    }
}
