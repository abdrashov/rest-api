<?php

namespace App\Filters;

class UserFilter extends Filter
{
    public function name(string $value): void
    {
        $this->builder->where('name', 'like', '%' . $value . '%');
    }

    public function email(string $value): void
    {
        $this->builder->where('email', 'like', '%' . $value . '%');
    }
}
