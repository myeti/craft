<?php

namespace Craft\Orm\Mapper\SQLite;

use Craft\Orm\Entity as NativeEntity;

class Entity extends NativeEntity
{

    /**
     * Clear all data
     * DANGEROUS
     * @return bool
     */
    public function clear()
    {
        $query = new Query\Truncate($this->name);
        $query->bind($this->pdo);

        return $query->apply();
    }

} 