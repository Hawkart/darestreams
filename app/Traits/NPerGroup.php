<?php

namespace App\Traits;

use DB;

trait NPerGroup {

    /**
     * A query scope for Eloquent models that enables side-loading a relation with n records per parent.
     *
     * @param  Builder $query
     * @param  string $group  Name of the field on the related table to group by (usually the column with the foreign key)
     * @param  int $n         Number of results to pick per group
     * @param  array  $scopes Scopes to apply on the related table ['nameOfScope' => ['argument1', 'argument2', …]]
     *
     * @return void
     */
    public function scopeNPerGroupWithScopes($query, $group, $n, $scopes = [])
    {
        $table = $this->getTable();
        $pk = $this->getKeyName();

        // Query the same model in a join using `over`, to assign row numbers starting at 1 for each group
        $partitioned_query = $this->newQuery()
            ->addSelect($pk)
            ->addSelect(DB::raw("row_number() over (partition by {$group} order by {$this->primaryKey}) as rn"));

        foreach ($scopes as $scope => $args) {
            $partitioned_query->$scope(...$args);
        }

        $partitioned_sql = $partitioned_query->toSql();
        $partitioned_bindings = $partitioned_query->getBindings();

        $query
            ->join(DB::raw("( $partitioned_sql ) AS partitioned"), "$table.$pk", '=', "partitioned.$pk")
            ->where("partitioned.rn", '<=', $n);

        $query->setBindings(array_merge_recursive($partitioned_bindings, $query->getBindings()));
    }
}