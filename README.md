# Laravel-easemob

laravel-easemob 将环信 v3.0 服务端的操作进行了封装, [查看环信官方文档](http://docs.easemob.com/im/100serverintegration/10intro)

## Install

* composer 安装
	* `composer require quhang/laravel-easemob:1.0.0`

* 在 `config/app.php`  中添加下面两项

```
[
	'providers' => [
	
		...
		
		Quhang\LaravelEasemob\EasemobServiceProvider::class,
	]
	
	'aliases' => [
	
		...
		
		'Easemob' => Quhang\LaravelEasemob\Facade\Easemob::class,
	]
]

```
* 发布配置文件

`php artisan vendor:publish --provider="Quhang\LaravelEasemob\EasemobServiceProvider"`

* 配置 `config/easemob.php`
	* `host` 环信服务地址, http://a1.easemob.com
	* `org_name`
	* `app_name`
	* `client_id`
	* `client_secret`
	* `download_file_path` 下载环信资源保存目录
	

## Usage
 
### Resource
 

[`Token`](#token)

[`User`](#user)

[`ChatMessage`](#chatmessage)

[`ChatFile`](#chatFile)

[`Message`](#message)

[`ChatGroup`](#chatgroup)

[`ChatRoom`](#chatroom)


### Result
Resource 方法返回数据格式  

```
[  
    'code' => -1,  
    'exception' => false,  
    'message' => '',  
    'data' => []  
]  
```
  
`exception = true` 时, 说明此次操作存在异常, 此时应查看 `message` 和 `data`  
`message` 保存的是程序异常信息, `data` 返回的是环信的错误信息, 当无响应时 `data` 为空数组
`code = -1` 表示无响应, `code != -1` 表示 `http` 状态码

### Service
`Service` 是个单例对象存放在容器中, `Resource` 就是通过它进行 `http` 通信:

* 修改 host

```
setHost($host)

Easemob::service()->setHost('http://a1.easemob.com')
```

* 修改 org_name

```
setOrgName($org)

Easemob::service()->setOrgName('11221xxx8276')
```

* 修改 app_name

```
setAppName($appName)

Easemob::service()->setAppName('testapp')
```

* 修改 client_id

```
setClientId($clientID)

Easemob::service()->setClientId('YXA6Irz_oI-xxx-FFvbfaMbQ')
```

* 修改 client_secret

```
setClientSecret($clientSecret)

Easemob::service()->setClientSecret('YXA6VsR5Jxxx....xxxYklmho0Vw')
```

* 修改默认配置

```
setConfig(array $config)

Easemob::service()->setConfig([
	'host' => 'http://xx.com',
	'org_name' => 'xxx',
	'app_name' => 'xxx',
	'client_id' => 'xxx	,
	'client_secret' => 'xxx',
	'download_file_path' => 'xxx'
])
```

### Token

* 获取 APP 管理员 Token(不会缓存, 仅仅是一次接口调用)

```
get()

Easemob::token()->get()
```

### User


* 注册 IM 用户[单个]  

```
registerregister($username, $password, $nickname = '', $authorization = false)


// 开放注册
Easemob::user()->register('username1', '123456')

// 授权注册
Easemob::user()->register('username1', '123456', '', false)
或
Easemob::user()->authorizeRegister('username1', '123456')
```

* 注册 IM 用户[批量] 

```
batchRegister(array $users)

Easemob::user()->batchRegister([
    ['username' => 'username2', 'password' => '123456', 'nickname' => 'xxx'],
    ['username' => 'username3', 'password' => '123456']
])
```

* 获取 IM 用户[单个]

```
profile()

Easemob::user('username')->profile()
```

* 获取 IM 用户[批量]

```
get($limit = 20, $cursor = '')

Easemob::user()->get()
```

* 删除 IM 用户[单个]

```
delete()

Easemob::user('username')->delete()
```

* 删除 IM 用户[批量]

```
batchDelete($limit = 100)

Easemob::user()->batchDelete(1)
```

* 重置 IM 用户密码
 
```
setPassword($password)

Easemob::user('username')->setPassword('123456')
```

* 修改用户推送显示昵称 

```
setNickname($nickname)
Easemob::user('username')->setNickname('xxx')
```

* 给 IM 用户添加好友

```
addFriend($friendName)

Easemob::user('username')->addFriend('friend_name')
```

* 解除 IM 用户的好友关系
 
```
removeFriend($friendName)

Easemob::user('username')->removeFriend('friend_name')
```

* 获取 IM 用户的好友列表

```
friends()

Easemob::user('username')->friends()
```

* 获取 IM 用户的黑名单

```
blocks($username)

Easemob::user('username')->blocks()
```

* 往 IM 用户的黑名单中加人

```
addBlocks($username, array $users)

Easemob::user()->addBlocks('usernameX', ['username1', 'username2', ...])
```

* 从 IM 用户的黑名单中减人
 
```
removeBlocks($blockuser)

Easemob::user('username')->removeBlocks('blockuser')
```

* 查看用户在线状态

```
status($username)

Easemob::user('username')->status()
```

* 查询离线消息数

```
offlineMsgCount()

Easemob::user('username')->countOfflineMsg()
```

* 查询某条离线消息状态(!! 未测试)

```
offlineMsgStatus($msgID)

Easemob::user('username')->offlineMsgStatus('msg_id')
```

* 用户账号禁用

```
deactivate()

Easemob::user('username')->deactivate()
```

* 用户账号解禁

```
activate()

Easemob::user('username')->activate()
```

[查看easemob官方文档](http://docs.easemob.com/im/100serverintegration/20users)


### ChatMessage

* 根据时间条件下载历史消息文件

```
download($time)

Easemob::chatMessage()->download('2017071210')
```

[查看easemob官方文档](http://docs.easemob.com/im/100serverintegration/30chatlog)


### ChatFile

* 上传语音/图片文件
 
```
upload($path, $restrictAccess = false)

Easemob::chatFile()->upload('/xx/xx.xx')
```

* 下载语音/图片文件

```
download($fileuuid, $shareSecret, $thumbnail = true)

Easemob::chatFile()->download('uuid', 'share_secret')
```

[查看easemob官方文档](http://docs.easemob.com/im/100serverintegration/40fileoperation)

### Message

* 发送消息

```
send(array $attributes = [])

// 发送文本消息
Easemob::message()->send([
    'target_type' => 'users',
    'target' => ['username3'],
    'msg' => [
        'type' => 'txt',
        'msg' => 'hello'
    ],
    'from' => 'username2'
])

// 发送图片消息
Easemob::message()->send([
    'target_type' => 'users',
    'target' => ['username3'],
    'msg' => [
        'type' => 'img',
        'url' => 'https://a1.easemob.com/11221xxxx8276/texxxapp/chatfiles/82de0980-66b6-11e7-9e82-4db84d77ebaf',
        'filename' => 'default.jpg',
        'secret' => 'gt4Jima2EeeFaDWOASFbGgiYkUq_YIXAYyPUNT8DxW7GxJSv',
        'size' => [
            'width' => 36,
            'height' => 36
        ]
    ],
    'from' => 'username2'
])

// 发送语音消息
Easemob::message()->send([
    'target_type' => 'users',
    'target' => ['username3'],
    'msg' => [
        'type' => 'audio',
        'url' => 'https://a1.easemob.com/11221xxxx8276/texxxapp/chatfiles/82de0980-66b6-11e7-9e82-4db84d77ebaf',
        'filename' => 'default.jpg',
        'secret' => 'gt4Jima2EeeFaDWOASFbGgiYkUq_YIXAYyPUNT8DxW7GxJSv',
        'length' => 10
    ],
    'from' => 'username2'
])

// 发送视频消息
Easemob::message()->send([
    'target_type' => 'users',
    'target' => ['username3'],
    'msg' => [
        'type' => 'video',
        'url' => 'https://a1.easemob.com/easemob-demo/chatdemoui/chatfiles/671dfe30-7f69-11e4-ba67-8fef0d502f46',
        'filename' => '1418105136313.mp4',
        'secret' => 'VfEpSmSvEeS7yU8dwa9rAQc-DIL2HhmpujTNfSTsrDt6eNb_',
        'thumb_secret' => 'ZyebKn9pEeSSfY03ROk7ND24zUf74s7HpPN1oMV-1JxN2O2I'
        'file_length' => 58103,
        'thumb'=> 'https://a1.easemob.com/easemob-demo/chatdemoui/chatfiles/67279b20-7f69-11e4-8eee-21d3334b3a97'
    ],
    'from' => 'username2'
])

// 发送透传消息
Easemob::message()->send([
    'target_type' => 'users',
    'target' => ['username3'],
    'msg' => [
        'type' => 'cmd',
        'action' => 'action1'
    ],
    'from' => 'username2'
])

// 发送扩展消息
Easemob::message()->send([
    'target_type' => 'users',
    'target' => ['username3'],
    'msg' => [
        'type' => 'txt',
        'msg' => ''
    ],
    'from' => 'username2',
    'ext' => [
        'key' => 'val'
    ]
])
```
[查看easemob官方文档](http://docs.easemob.com/im/100serverintegration/50messages)

### ChatGroup

* 分页获取 APP 下的群组

```
get($limit = 20, $cursor = '')

// 不带游标
Easemob::chatGroup()->get(2)

// 带游标
Easemob::chatGroup()->get(2, 'ZGNiMjRmNGY1YjczYjlhYTNkYjk1MDY2YmEyNzFmODQ6aW06Z3JvdXA6MTEyMjE2MTAxMTE3ODI3NiN0ZXN0YXBwOjI')
```

* 获取一个用户参与的所有群组
 
```
chatGroups()
Easemob::user('username2')->chatGroups()
```

* 获取群组详情

```
profile()

Easemob::chatGroup('21437472636929')->profile()
```

* 创建一个群组

```
create(array $attributes)

Easemob::chatGroup()->create([
    'groupname' => 'usenames_group',
    'desc' => 'just soso',
    'public' => true,
    'maxusers' => 200,
    'members_only' => true,
    'allowinvites' => true,
    'owner' => 'username3',
    'members' => ['username2']
])
```

* 修改群组信息
 
```
update(array $attributes)

Easemob::chatgroup('21437472636929')->update([
    'groupname' => 'jooo in us',
    'description' => 'nothing',
    'maxusers' => 300
])
```

* 删除群组

```
delete()

Easemob::chatgroup('21437472636929')->delete()
```

* 分页获取群组成员

```
users($pagenum = 10, $pagesize = 1)

Easemob::chatgroup('21437472636929')->users()
```

* 添加群组成员[单个]

```
addUser($user)

Easemob::chatGroup('21437472636929')->addUser('username3')
```

* 添加群组成员[批量]

```
batchAddUsers(array $users)

Easemob::chatGroup('21437472636929')->batchAddUsers(['username5', 'username6'])
```

* 移除群组成员[单个]

```
removeUser($user)

Easemob::chatGroup('21437472636929')->removeUser('username6')
```

* 移除群组成员[批量]

```
batchRemoveUsers(array $users)

Easemob::chatGroup('21437472636929')->batchRemoveUsers(['username5', 'username4'])
```

* 获取群管理员列表
 
```
admin()

Easemob::chatGroup('21437472636929')->admin()
```

* 添加群管理员

```
addAdmin($user)

Easemob::chatgroup('21437472636929')->addAdmin('username2')
```

* 移除群管理员

```
removeAdmin($user)

Easemob::chatGroup('21437472636929')->removeAdmin('username2')
```

* 转让群组
 
```
updateOwner($user)

Easemob::chatGroup('21437472636929')->updateOwner('username3')
```

* 查询群组黑名单
 
```
blockUsers()

Easemob::chatGroup('21437472636929')->blockUsers()
```

* 添加用户至群组黑名单[单个]

```
addBlockUser($user)

Easemob::chatGroup('21437472636929')->addBlockUser('username5')
```

* 添加用户至群组黑名单[批量]
 
```
batchAddBlockUsers(array $users)

Easemob::chatGroup('21437472636929')->batchAddBlockUsers(['username6'])
```

* 从群组黑名单移除用户[单个]

```
removeBlockUser($user)

Easemob::chatGroup('21437472636929')->removeBlockUser('username5')
```

* 从群组黑名单移除用户[批量]

```
batchRemoveBlockUsers(array $users)

Easemob::chatGroup('21437472636929')->batchRemoveBlockUsers(['username6']);
```

* 添加禁言

```
addMuteUsers(array $users, $time = 60) // $time 单位分钟
Easemob::chatGroup('21437472636929')->addMuteUsers(['username6'])
```

* 移除禁言
 
```
removeMuteUsers(array $users)

Easemob::chatGroup('21437472636929')->removeMuteUsers(['username6'])
```

* 获取禁言列表

```
muteUsers()
Easemob::chatGtoup('21437472636929')->muteUsers()
```

[查看easemob官方文档](http://docs.easemob.com/im/100serverintegration/60groupmgmt)

### ChatRoom

* 获取 APP 中所有的聊天室

```
get($pagesize = 10, $pagenum = 10)

Easemob::chatRoom()->get()
```

* 获取用户加入的聊天室

```
chatRooms()

Easemob::user('username3')->chatRooms()
```

* 获取聊天室详情

```
profile()

Easemob::chatRoom('stringa')->profile()
```

* 创建一个聊天室

```
create($attributes)

Easemob::chatRoom()->create([
    'name' => 'username6_chatroom',
    'description' => 'communicate',
    'maxusers' => 50,
    'owner' => 'username6',
    'members' => ['username3', 'username4']
])
```

* 修改聊天室信息

```
update(array $attibutes)

Easemob::chatRoom('21442546696195')->update([
    'name' => 'username_chatroom',
    'description' => 'xxxx',
    'maxusers' => 100
])
```

* 删除聊天室

```
delete()

Easemob::chatRoom('21442546696195')->delete()
```

* 分页获取聊天室成员

```
users($pagesize = 10, $pagenum = 1)

Easemob::chatRoom('21442546696195')->users()
```

* 添加聊天室成员[单个]

```
addUser($user)

Easemob::chatRoom('21442546696195')->addUser('username5')
```

* 添加聊天室成员[批量]

```
batchAddUsers(array $users)

Easemob::chatRoom('21442546696195')->batchAddUsers(['username2'])
```

* 删除聊天室成员[单个]
 
```
removeUser($user)

Easemob::chatRoom('21442546696195')->removeUser('username2')
```

* 删除聊天室成员[批量]

```
batchRemoveUsers(array $users)

Easemob::chatRoom('21442546696195')->batchRemoveUsers(['username3', 'username4'])
```

* 获取聊天室管理员列表

```
admin()

Easemob::chatRoom('21442546696195')->admin()
```

* 添加聊天室管理员

```
addAdmin($user)

Easemob::chatRoom('21442546696195')->addAdmin('username5')
```

* 移除聊天室管理员

```
removeAdmin($user)

Easemob::chatRoom('21442546696195')->removeAdmin('username5')
```

* 获取禁言列表

```
muteUsers()

Easemob::chatRoom('21442546696195')->muteUsers()
```

* 添加禁言

```
addMuteUsers(array $users, $time = 60)

Easemob::chatRoom('21442546696195')->addMuteUsers(['username5'])
```

* 移除禁言
 
```
removeMuteUsers(array $users)

Easemob::chatRoom('21442546696195')->removeMuteUsers(['username5', 'username4'])
```

[查看easemob官方文档](http://docs.easemob.com/im/100serverintegration/70chatroommgmt)