<?php

namespace App;


final class Events
{
    const PRODUCT_ADD = 'product.saved';
    const PRODUCT_MODIFIED = 'product.modified';
    const PRODUCT_DELETED =  'product.deleted';
    const SAVE_DB_ERROR = 'exception.save.db';
    const ENTITY_DOES_NOT_EXIST = 'entity.does.not.exist';
}