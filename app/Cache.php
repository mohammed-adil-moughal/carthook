<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cache extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'caches';

    /**
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * @var bool
     */
    public $incrementing = true;

    /**
     * @var string
     */
    protected $table_name ;

    /**
     * @var string
     */
    protected $time_to_live;

}
