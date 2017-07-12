<?php

namespace Quhang\LaravelEasemob\Resources;

class ChatRoom extends Resource
{
    protected $room;

    public function setRoom($room)
    {
        $this->room = $room;
        return $this;
    }

    public function getRoom($strict = false)
    {
        if (!$this->room && $strict) {
            throw new LaravelEasemobException('请设置房间号');
        }
        return $this->room;
    }

    /**
     * 获取聊天室
     * @return array
     */
    public function get($pagesize = 10, $pagenum = 1)
    {
        return $this->request('get', "/chatrooms", [
            'query' => compact('pagesize', 'pagenum')
        ]);
    }

    /**
     * 创建聊天室
     * @param  array $attibutes
     * @return array
     */
    public function create(array $attibutes)
    {
        return $this->request('post', "/chatrooms", [
            'body' => json_encode($attibutes)
        ]);
    }

    /**
     * 删除聊天室
     * @return array
     */
    public function delete()
    {
        return $this->request('delete', "/chatrooms/{$this->getRoom(true)}");
    }

    /**
     * 聊天室详情
     * @return array
     */
    public function profile()
    {
        return $this->request('get', "/chatrooms/{$this->getRoom(true)}");
    }

    /**
     * 更新聊天室
     * @param  array  $attibutes
     * @return array
     */
    public function update(array $attibutes)
    {
        return $this->request('put', "/chatrooms/{$this->getRoom(true)}", [
            'body' => json_encode($attibutes)
        ]);
    }

    /**
     * 批量删除用户
     * @param  array  $users
     * @return array
     */
    public function batchRemoveUsers(array $users)
    {
        $usernames = implode(',', $users);
        return $this->request('delete', "/chatrooms/{$this->getRoom(true)}/users/{$usernames}");
    }

    /**
     * 删除单个用户
     * @param  string $user
     * @return array
     */
    public function removeUser($user)
    {
        return $this->request('delete', "/chatrooms/{$this->getRoom(true)}/users/{$user}");
    }

    /**
     * 聊天室添加用户
     * @param array $users
     * @return  array
     */
    public function batchAddUsers(array $users)
    {
        return $this->request('post', "/chatrooms/{$this->getRoom(true)}/users", [
            'body' => json_encode(['usernames' => $users])
        ]);
    }

    /**
     * 添加单个用户
     * @param string $user
     */
    public function addUser($user)
    {
        return $this->request('post', "/chatrooms/{$this->getRoom(true)}/users/{$user}");
    }

    /**
     * 用户列表
     * @param  integer $pagesize
     * @param  integer $pagenum
     * @return array
     */
    public function users($pagesize = 10, $pagenum = 1)
    {
        return $this->request('get', "/chatrooms/{$this->getRoom(true)}/users", [
            'query' => compact('pagesize', 'pagenum')
        ]);
    }

    /**
     * 获取管理员
     * @return array
     */
    public function admin()
    {
        return $this->request('get', "/chatrooms/{$this->getRoom(true)}/admin");
    }

    /**
     * 添加管理员
     * @param string $user
     * @return  array
     */
    public function addAdmin($user)
    {
        return $this->request('post', "/chatrooms/{$this->getRoom(true)}/admin", [
            'body' => json_encode([
                'newadmin' => $user
            ])
        ]);
    }

    /**
     * 移除管理员
     * @param  string $user
     * @return array
     */
    public function removeAdmin($user)
    {
        return $this->request('delete', "/chatrooms/{$this->getRoom(true)}/admin/{$user}");
    }

    /**
     * 获取被禁言用户
     * @return array
     */
    public function muteUsers()
    {
        return $this->request('get', "/chatrooms/{$this->getRoom(true)}/mute");
    }

    /**
     * 添加禁言用户
     * @param array $users
     * @param int $time 单位分钟
     * @return  array
     */
    public function addMuteUsers(array $users, $time = 60)
    {
        return $this->request('post', "/chatrooms/{$this->getRoom(true)}/mute", [
            'body' => json_encode([
                'usernames' => $users,
                'mute_duration' => $time * 60 * 1000
            ])
        ]);
    }

    /**
     * 移除被禁言用户
     * @param  array  $users
     * @return array
     */
    public function removeMuteUsers(array $users)
    {
        $usernames = implode(',', $users);
        return $this->request('delete', "/chatrooms/{$this->getRoom(true)}/mute/{$usernames}");
    }
}
