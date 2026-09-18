<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;

abstract class SearchableController extends Controller
{
    const int MAX_ITEMS = 5;

    abstract protected function getQuery(): Builder;

    function prepareCriteria(array $criteria): array
    {
        return [
            'term' => null,
            ...$criteria,
        ];
    }

    function getFilterOptions(): array
    {
        return [
            'term' => [
                'code' =>
                static fn(Builder $query, string $word)
                => $query->where('code', 'LIKE', "%{$word}%"),
                'name' =>
                static fn(Builder $query, string $word)
                => $query->where('name', 'LIKE', "%{$word}%"),
            ],
        ];
    }

    final function filterByTerm(
        Builder|Relation $query,
        string $term,
        array $termOptions,
    ): void {
        foreach (
            \preg_split(
                '/\s+/',
                \trim($term),
                flags: \PREG_SPLIT_NO_EMPTY,
            ) as $word
        ) {
            $query->where(

                static function (Builder $wordQuery) use ($word, $termOptions): void {

                    foreach ($termOptions as $fieldFn) {

                        $wordQuery->orWhere(

                            static fn(Builder $fieldQuery) => $fieldFn($fieldQuery, $word),
                        );
                    }
                },
            );
        }
    }

    function filter(
        Builder|Relation $query,
        array $criteria,
        array $filterOptions,
    ): void {
        if ($criteria['term'] !== null) {
            $this->filterByTerm(
                $query,
                $criteria['term'],
                $filterOptions['term'],
            );
        }
    }

    final function search(array $criteria, ?array $filterOptions = null): Builder
    {
        $filterOptions ??= $this->getFilterOptions();
        $query = $this->getQuery();
        $this->filter($query, $criteria, $filterOptions);
        return $query;
    }

    // For easily searching by code.
    final function find(string $code): Model
    {
        return $this->getQuery()->where('code', $code)->firstOrFail();
    }
}
