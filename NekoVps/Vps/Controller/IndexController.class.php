<?php
namespace Vps\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
      $this->display('Home-index');
    }
    public function login(){
      $this->display('Home-login');
    }
    public function register(){
      $registerForm=array(
        'email'=>array('email','email',' 邮箱:','envelope'),
        'username'=>array('text','username',' 昵称:','user'),
        'password'=>array('password','password',' 密码:','asterisk'),
        'repassword'=>array('password','repassword',' 重复密码:','asterisk'),
        'qq'=>array('text','qq','QQ:','qq'),
        'friendcode'=>array('text','friendcode',' 邀请码:','gift'),
      );
      $this->form=$registerForm;
      $this->display('Home-register');
    }
    public function VerifyCode(){
      $Verify = new \Think\Verify();
      $Verify->useCurve = false;
      $Verify->useImgBg = true;
      $Verify->codeSet = '0123456789';
      $Verify->entry();
    } 
    public function buy(){
      $db = M('deployplan');
      $data = $db->find(1);
      $vultr = new \Org\Net\Vultr(C('Vultr_Api_Key'));
      $this->assign('regionsList',$vultr->RegionsToGroup(json_decode($data['regions'],true)));
      $this->assign('osList',$vultr->OsToGroup(json_decode($data['os'],true)));
      $this->assign('planList',json_decode($data['plans'],true));
      $this->display('Home-buy');
    }
    public function gd(){
      //$db = M('deployplan');
      //$data = $db->find(1);
      echo '<pre>';
      $vultr = new \Org\Net\Vultr(C('Vultr_Api_Key'));
      var_dump($vultr->GetMyIso());
      //var_dump(json_decode($data['regions'],true));
      echo '</pre>';
    }
    public function ud(){
      $db = M('deployplan');
      $vultr = new \Org\Net\Vultr(C('Vultr_Api_Key'));
      $os = $vultr->GetOs();
      $plans = $vultr->GetPlans();
      $regions = $vultr->GetRegions();
      $data['os'] = json_encode($os);
      $data['plans'] = json_encode($plans);
      $data['regions'] = json_encode($regions);
      $data['update_time'] = time();
      $db->where('id=1')->save($data);
      echo 'Update Complete!';
    }
}