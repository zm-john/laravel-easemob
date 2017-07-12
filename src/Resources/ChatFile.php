<?php

namespace Quhang\LaravelEasemob\Resources;

class ChatFile extends Resource
{
    /**
     * 上传文件
     * @param  string  $filepath
     * @param  boolean $restrictAccess
     * @return array
     */
    public function upload($filepath, $restrictAccess = false)
    {
        if (!file_exists($filepath)) {
            throw new Exception\LaravelEasemobException('文件不存在');
        }
        $accessStr = $restrictAccess ? 'true' : 'false';
        return $this->request('post', '/chatfiles', [
            'headers' => [
                'restrict-access' => $accessStr,
            ],
            'multipart' => [
                [
                    'name' => 'file',
                    'contents' => fopen($filepath, 'r'),
                    'filename' => basename($filepath),
                    'headers' => [
                        'Content-Type' => 'multipart/form-data'
                    ]
                ]
            ]
        ]);
    }

    /**
     * 文件下载
     * @param  string $fileuuid
     * @param  string $shareSecret
     * @param  string $savePath
     * @param  boolean $thumbnail
     * @return array
     */
    public function download($fileuuid, $shareSecret, $thumbnail = true)
    {
        $thumbnailStr = $thumbnail ? 'true' : 'false';
        return $this->file('get', "/chatfiles/{$fileuuid}", [
            'headers' => [
                'accept' => 'application/octet-stream',
                'share-secret' => $shareSecret,
                'thumbnail' => $thumbnailStr
            ]
        ]);
    }
}
