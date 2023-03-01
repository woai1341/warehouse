<?php

namespace app\controller;

use app\BaseController;
use think\cache\driver\Redis;
use think\db\exception\DbException;
use think\facade\Db;
use think\facade\Log;
use think\facade\Session;


class LoginController extends BaseController
{
    public function home()
    {
        return view("./home/index");
    }
    
    public function login()
    {
        //9）登录后再次访问登录页根据session值重定向到后台首页
        if (session('adminSessionData')) {
            return redirect('./admin/');
        }
        
        if (request()->isPost()) {
            $data = input('post.');
            // //验证码校验
            // if (!captcha_check($data['verifycode'])) {
            //     return alert('验证码错误', 'index', 5);
            // };
            //验证码用户名
            $adminData = Db::name('user')->where('user_name', $data['username'])->find();
            
            if (empty($adminData)) {
                successRep("用户不存在", [], 100);
            }
            //如果管理员有状态，status=1合法  0禁止
            if ($adminData['status'] = 0) {
                successRep("您的账号被禁止登录", [], 100);
            }
            
            //密码校验
            $salt = 'warehouse@';
            if ($adminData['password'] != md5($salt . 123)) {
                successRep("密码错误");
            }
            
            try {
                Db::name('user')->where('id', $adminData['id'])->update(['last_login_time' => time()]);
            } catch (DbException $e) {
                Log::error("数据库错误---" . $e->getMessage());
                errorRep("登录失败，系统错误~");
            }
            // 保存信息进redis
            $user_data = [
                'id' => $adminData['id'],
                "login_time" => date("Y-m-d H:i:s")
            ];
            
            (new Redis)->set("user-login-".$adminData['id'], json_encode($user_data), env("JWT.EXP"));
            
            session('adminSessionData', $data['username']);
            
            successRep("登录成功");
            // 登录跳转
            // return view("./home/index");
        }
    }
    
    public function logout()
    {
        Session::destroy();
        (new Redis)->delete("user-login");
        return redirect('https://warehouse.12520.top/');
    }
}