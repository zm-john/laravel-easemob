<?php

namespace Quhang\LaravelEasemob\Resources;

class ChatMessage extends Resource
{
    /**
     * 下载聊天记录
     * @param  string $time
     * @return [type]       [description]
     */
    public function download($time)
    {
        return $this->request('get', "/chatmessages/{$time}");
    }
}
