<?php

namespace App\Filters;

class PostFilter extends Filter
{
    public function title(string $value): void
    {
        $this->builder->where('title', 'like', '%' . $value . '%');
    }

    public function content(string $value): void
    {
        $this->builder->where('content', 'like', '%' . $value . '%');
    }

    public function userId(int $value): void
    {
        $this->builder->where('user_id', $value);
    }


}
