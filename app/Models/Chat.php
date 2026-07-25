<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = [
        'customer_id',
        'last_message',
        'unread_by_customer',
        'unread_by_cs',
    ];

    protected $casts = [
        'unread_by_customer' => 'boolean',
        'unread_by_cs'       => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id')->orderBy('created_at', 'asc');
    }
}
