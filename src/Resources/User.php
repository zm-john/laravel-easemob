<?php

namespace Quhang\LaravelEasemob\Resources;

use Quhang\LaravelEasemob\Exception\LaravelEasemobException;

class User extends Resource
{
    protected $user;

    public function setUser($user)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser($strict = false)
    {
        if (!$this->user && $strict) {
            throw new LaravelEasemobException('请设置用户');
        }
        return $this->user;
    }

    /**
     * 批量删除用户
     * @param  integer $limit 删除数量
     * @return array
     */
    public function batchDelete($limit = 100)
    {
        return $this->request('delete', '/users', [
            'query' => compact('limit')
        ]);
    }

    /**
     * 获取用户
     * @param  integer $limit
     * @param  string  $cursor 分页游标
     * @return array
     */
    public function get($limit = 20, $cursor = '')
    {
        return $this->request('get', '/users', [
            'query' => compact('limit', 'cursor')
        ]);
    }

    /**
     * 注册用户
     * 
     * @param  [type] $users 
     * [
     *     [
     *         'username' => 'xx',
     *         'password' => 'xx',
     *         'nickname' => ''
     *     ]
     * ]
     * nickname 可选
     * @return [type]        [description]
     */
    public function batchRegister(array $users)
    {
        return $this->request('post', '/users', [
            'body' => json_encode($users)
        ]);
    }

    /**
     * 单个用户注册
     * @param  string $username
     * @param  string $password
     * @param  string $nickname=''
     * @param  boolean $authorization=false 
     * @return array
     */
    public function register($username, $password, $nickname = '', $authorization = false)
    {
        return $this->request('post', '/users', [
            'body' => json_encode(compact('username', 'password', 'nickname'))
        ], $authorization);
    }

    /**
     * 授权注册
     * @param  string $username
     * @param  string $password
     * @param  string $nickname
     * @return array
     */
    public function authorizeRegister($username, $password, $nickname = '')
    {
        return $this->register($username, $password, $nickname, true);
    }

    /**
     * 获取指定用户黑名单
     * @return [type]           [description]
     */
    public function blocks()
    {
        return $this->request('get', "/users/{$this->getUser(true)}/blocks/users");
    }

    /**
     * 用户添加黑名单
     * @param array  $users
     * @return  array
     */
    public function addBlocks(array $users)
    {
        return $this->request('post', "/users/{$this->getUser(true)}/blocks/users", [
            'body' => json_encode([
                'usernames' => $users
            ])
        ]);
    }

    /**
     * 一个用户将另一个用户移除黑名单
     * @param  string $blockuser
     * @return array
     */
    public function removeBlocks($blockuser)
    {
        return $this->request('delete', "/users/{$this->getUser(true)}/blocks/users/{$blockuser}");
    }

    /**
     * 好友列表
     * @return array
     */
    public function friends()
    {
        return $this->request('get', "/users/{$this->getUser(true)}/contacts/users");
    }

    /**
     * 删除好友
     * @param  string $contactUser
     * @return array
     */
    public function removeFriend($friendName)
    {
        return $this->request('delete', "/users/{$this->getUser(true)}/contacts/users/{$friendName}");
    }

    /**
     * 给 IM 用户添加好友
     * @param string $contactUser
     *
     * @return  array
     */
    public function addFriend($friendName)
    {
        return $this->request('post', "/users/{$this->getUser(true)}/contacts/users/{$friendName}");
    }

    /**
     * 获取离线消息
     * @return array
     */
    public function countOfflineMsg()
    {
        return $this->request('get', "/users/{$this->getUser(true)}/offline_msg_count");
    }

    /**
     * 删除用户
     * @return array
     */
    public function delete()
    {
        return $this->request('delete', "/users/{$this->getUser(true)}");
    }

    /**
     * 用户详情
     * @return string
     */
    public function profile()
    {
        return $this->request('get', "/users/{$this->getUser(true)}");
    }

    /**
     * 设置用户昵称
     * @param  string $nickname
     * @return array
     */
    public function setNickname($nickname)
    {
        return $this->request('put', "/users/{$this->getUser(true)}", [
            'body' => json_encode(compact('nickname'))
        ]);
    }

    /**
     * 恢复账号使用
     * @return array
     */
    public function activate()
    {
        return $this->request('post', "/users/{$this->getUser(true)}/activate");
    }

    /**
     * 禁用账号
     * @return array
     */
    public function deactivate()
    {
        return $this->request('post', "/users/{$this->getUser(true)}/deactivate");
    }

    /**
     * 强制用户下线
     * @return array
     */
    public function disconnect()
    {
        return $this->request('post', "/users/{$this->getUser(true)}/disconnect");
    }

    /**
     * 获取用户参与的所有群组
     * @return array
     */
    public function chatGroups()
    {
        return $this->request('get', "/users/{$this->getUser(true)}/joined_chatgroups");
    }

    /**
     * 获取用户加入的聊天室
     * @return array
     */
    public function chatRooms()
    {
        return $this->request('get', "/users/{$this->getUser(true)}/joined_chatrooms");
    }

    /**
     * 获取离线消息状态
     * @param  string $msgID
     * @return array
     */
    public function offlineMsgStatus($msgID)
    {
        return $this->request('get', "/users/{$this->getUser(true)}/offline_msg_status/{$msgID}");
    }

    /**
     * 设置新密码
     * @param  string $newpassword
     * @return array
     */
    public function setPassword($newpassword)
    {
        return $this->request('put', "/users/{$this->getUser(true)}/password", [
            'body' => json_encode(compact('newpassword'))
        ]);
    }

    /**
     * 获取用户在线状态
     * @return array
     */
    public function status()
    {
        return $this->request('get', "/users/{$this->getUser(true)}/status");
    }
}
