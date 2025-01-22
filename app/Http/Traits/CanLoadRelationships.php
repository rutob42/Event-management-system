<?php

namespace App\Http\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

trait CanLoadRelationships
{
    public function loadRelationships(
        Model|QueryBuilder|EloquentBuilder|HasMany $for,
        ?array $relations = null
    ): Model|QueryBuilder|EloquentBuilder {
        $relations = $relations ?? $this->relations ?? [];
        foreach ($relations as $relation) {
            $for->when(
                $this->shouldIncludeRelation($relation), // Check if the relation should be included
                fn($q) => $for instanceof Model ? $for->load($relation) : $q->with($relation) // Eager-load the relation
            );
        }
        return $for;
    }

    protected function shouldIncludeRelation(string $relation): bool
    {
        $include = request()->query('include'); // Get the 'include' query parameter

        if (!$include) {
            return false; // If not, do not include the relation
        }

        $relations = array_map('trim', explode(',', $include));
        return in_array($relation, $relations);
    }
}