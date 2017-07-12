<?php

namespace Quhang\LaravelEasemob\Resources;

class ChatGroup extends Resource
{
    protected $group;

    public function setGroup($group)
    {
        $this->group = $group;
        return $this;
    }

    public function getGroup($strict = false)
    {
        if (!$this->group && $strict) {
            throw new LaravelEasemobException('请设置群组编号');
        }
        return $this->group;
    }

    /**
     * 获取所有群组
     * @param  integer $limit=20
     * @param  string  $cursor=’‘
     * @return array
     */
    public function get($limit = 20, $cursor = '')
    {
        return $this->request('get', '/chatgroups', [
            'query' => compact('limit', 'cursor')
        ]);
    }

    /**
     * 创建一个群组
     * @param  array  $attributes
     * @return array
     */
    public function create(array $attributes)
    {
        return $this->request('post', '/chatgroups', [
            'body' => json_encode($attributes)
        ]);
    }

    /**
     * 批量向群组里添加用户
     * @param array $users
     * @return  array
     */
    public function batchAddUsers(array $users)
    {
        return $this->request('post', "/chatgroups/{$this->getGroup(true)}/users", [
            'body' => json_encode([
                'usernames' => $users
            ])
        ]);
    }

    /**
     * 添加单个用户
     * @param string $user
     * @return  array
     */
    public function addUser($user)
    {
        return $this->request('post', "/chatgroups/{$this->getGroup(true)}/users/{$user}");
    }

    /**
     * 获取多个群组详情
     * @param  array  $groupIDs=[]
     * @return array
     */
    public function profile(array $groupIDs = [])
    {
        if (!$groupIDs) {
            $groupIDs = [$this->getGroup(true)];
        }
        $groupIDStr = implode(',', $groupIDs);
        return $this->request('get', "/chatgroups/{$groupIDStr}");
    }

    /**
     * 删除一个群组
     * @return array
     */
    public function delete()
    {
        return $this->request('delete', "/chatgroups/{$this->getGroup(true)}");
    }

    /**
     * 修改群组
     * @param  array  $attributes
     * @return array
     */
    public function update(array $attributes)
    {
        return $this->request('put', "/chatgroups/{$this->getGroup(true)}", [
            'body' => json_encode($attributes)
        ]);
    }

    /**
     * 获取群组黑名单用户
     * @return array
     */
    public function blockUsers()
    {
        return $this->request('get', "/chatgroups/{$this->getGroup(true)}/blocks/users");
    }

    /**
     * 群组批量添加黑名单用户
     * @param array $users
     * @return  array
     */
    public function batchAddBlockUsers(array $users)
    {
        return $this->request('post', "/chatgroups/{$this->getGroup(true)}/blocks/users", [
            'body' => json_encode([
                'usernames' => $users
            ])
        ]);
    }

    /**
     * 群组添加黑名单用户
     * @param string $user
     * @return array
     */
    public function addBlockUser($user)
    {
        return $this->request('post', "/chatgroups/{$this->getGroup(true)}/blocks/users/{$user}");
    }

    /**
     * 将用户从黑名单中删除
     * @param  array $users
     * @return array
     */
    public function batchRemoveBlockUsers(array $users)
    {
        $usernames = implode(',', $users);
        return $this->request('delete', "/chatgroups/{$this->getGroup(true)}/blocks/users/{$usernames}");
    }

    /**
     * 删除黑名单用户
     * @param  string $user
     * @return array
     */
    public function removeBlockUser($user)
    {
        return $this->request('delete', "/chatgroups/{$this->getGroup(true)}/blocks/users/{$user}");
    }

    /**
     * 获取群组用户
     * @param  int $pagenum
     * @param  int $pagesize
     * @return array
     */
    public function users($pagenum = 10, $pagesize = 1)
    {
        return $this->request('get', "/chatgroups/{$this->getGroup(true)}/users", [
            'query' => compact('pagenum', 'pagesize')
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
        return $this->request('delete', "/chatgroups/{$this->getGroup(true)}/users/{$usernames}");
    }

    /**
     * 从群组中删除一个用户
     * @param  string $user
     * @return array
     */
    public function removeUser($user)
    {
        return $this->request('delete', "/chatgroups/{$this->getGroup(true)}/users/{$user}");
    }

    /**
     * 更换群主
     * @param  string $user
     * @return array
     */
    public function updateOwner($user)
    {
        return $this->request('put', "/chatgroups/{$this->getGroup(true)}", [
            'body' => json_encode(['newowner' => $user])
        ]);
    }

    /**
     * 获取管理员
     * @return array
     */
    public function admin()
    {
        return $this->request('get', "/chatgroups/{$this->getGroup(true)}/admin");
    }

    /**
     * 添加管理员
     * @param string $user
     * @return  array
     */
    public function addAdmin($user)
    {
        return $this->request('post', "/chatgroups/{$this->getGroup(true)}/admin", [
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
        return $this->request('delete', "/chatgroups/{$this->getGroup(true)}/admin/{$user}");
    }

    /**
     * 添加禁言用户
     * @param array   $users
     * @param integer $time  单位分钟
     * @return  array
     */
    public function addMuteUsers(array $users, $time = 60)
    {
        return $this->request('post', "/chatgroups/{$this->getGroup(true)}/mute", [
            'body' => json_encode([
                'usernames' => $users,
                'mute_duration' => $time * 60 * 1000
            ])
        ]);
    }

    /**
     * 移除被禁言的用户
     * @param  array  $users
     * @return array
     */
    public function removeMuteUsers(array $users)
    {
        $usernames = implode(',', $users);
        return $this->request('delete', "/chatgroups/{$this->getGroup(true)}/mute/{$usernames}");
    }

    /**
     * 获取被禁言的用户
     * @return array
     */
    public function muteUsers()
    {
        return $this->request('get', "/chatgroups/{$this->getGroup(true)}/mute");
    }
}