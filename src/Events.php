<?php

namespace App;


final class Events
{
    const PRODUCT_ADD = 'product.saved';
    const PRODUCT_MODIFIED = 'product.modified';
    const PRODUCT_DELETED =  'product.deleted';
    const SAVE_DB_ERROR = 'exception.save.db';
    const ENTITY_DOES_NOT_EXIST = 'entity.does.not.exist';
    const USER_REGISTERED = 'user.registered';
    const EMAIL_CONFIRMED = 'email.confirmed';
    const PASSWORD_FORGOT_REQUEST = 'password.forgot.request';
    const PASSWORD_CHANGE_SUCCESS = 'password.change.success';
    const PASSWORD_ENCODE = 'PASSWORD_ENCODE';
    const SET_TOKEN = 'SET_TOKEN';
    const USER_DOES_NOT_EXIST = 'user.does.not.exist';
}