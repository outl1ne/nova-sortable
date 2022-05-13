<?php

namespace Outl1ne\NovaSortable\Traits;

trait HasSortableManyToManyRows
{
    use HasSortableRows;

    public $disableSortOnIndex = true;
}
