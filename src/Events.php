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
    const PASSWORD_CHANGED_SUCCESS = 'password.change.success';
    const PASSWORD_ENCODE = 'PASSWORD_ENCODE';
    const SET_TOKEN = 'SET_TOKEN';
    const PROFILE_UPDATED = 'profile.update';
    const USER_DOES_NOT_EXIST = 'user.does.not.exist';
    const ADD_IMAGE = 'ADD_USER_IMAGE';
    const DELETE_IMAGE = 'DELETE_IMAGE';
    const CATEGORY_UPDATE = 'admin.category.update';
    const CATEGORY_DELETE = 'admin.category.delete';
}