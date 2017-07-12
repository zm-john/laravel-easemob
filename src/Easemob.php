<?php

namespace Quhang\LaravelEasemob;

class Easemob
{
    protected $message;
    protected $chatMessage;
    protected $chatFile;
    protected $service;
    protected $token;

    public function user($user = '')
    {
        return app(Resources\User::class)->setUser($user);
    }

    public function message()
    {
        return $this->message ?: $this->message = app(Resources\Message::class);
    }

    public function ChatRoom($room = '')
    {
        return app(Resources\ChatRoom::class)->setRoom($room);
    }

    public function chatMessage()
    {
        return $this->chatMessage ?: $this->chatMessage = app(Resources\ChatMessage::class);
    }

    public function chatGroup($group = '')
    {
        return app(Resources\ChatGroup::class)->setGroup($group);
    }

    public function chatFile()
    {
        return $this->chatFile ?: $this->chatFile = app(Resources\ChatFile::class);
    }

    public function token()
    {
        return $this->token ?: $this->token = app(Resources\Token::class);
    }

    public function service()
    {
        return $this->service ?: $this->service = app(Service::class);
    }
}
