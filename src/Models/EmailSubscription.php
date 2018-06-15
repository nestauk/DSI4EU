<?php

namespace Models;

use Illuminate\Database\Eloquent\Model;

class EmailSubscription extends Model
{
    const TABLE = 'email_subscriptions';
    protected $table = self::TABLE;

    const Id = 'id';
    const UserID = 'user_id';
    const Subscribed = 'subscribed';
    const CreatedAt = 'created_at';
    const UpdatedAt = 'updated_at';
}