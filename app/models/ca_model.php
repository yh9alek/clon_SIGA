<?php

namespace app\models;

use app\config\SQL;
use app\config\ModelConstructor;

class ca_model {
    public SQL $db;
    use ModelConstructor;
}