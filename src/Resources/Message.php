<?php

namespace Quhang\LaravelEasemob\Resources;

class Message extends Resource
{
    /**
     * 发送消息
     * @param  array message
     * @return array
     */
    public function send(array $attributes = [])
    {
        return $this->request('post', '/messages', [
            'body' => json_encode($attributes)
        ]);
    }
}
