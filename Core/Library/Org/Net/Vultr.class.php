<?php
namespace Org\Net;
class Vultr{
  private $ApiKey = null;
  function __construct ($ApiKey){
    $this->ApiKey = $ApiKey;
  }
  //发送指令
  private function SendCommand($ApiName,$addressParams = null,$postParams = null){
    //初始化Curl
    $ch=curl_init();
    //设置Curl不认证SSL证书可靠性
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    //合并Get请求数据
    $Var = 'api_key='.$this->ApiKey;
    if(is_array($addressParams)){
      foreach ($addressParams as $key => $value) {
        $Var.=$Var!=''?'&':'';
        $Var.=$key . '=' . urlencode($value);
      }
    }
    //设置API访问域及Get参数传递
    curl_setopt( $ch, CURLOPT_URL, "https://api.vultr.com/v1/".$ApiName."?". $Var);
    //合并Post表单数据
    if(is_array($postParams))curl_setopt( $ch, CURLOPT_POST, 1 );
    $Var = '';
    if(is_array($postParams)){
      foreach ($postParams as $key => $value) {
        $Var.=$Var!=''?'&':'';
        $Var.=$key . '=' . urlencode($value);
      }
    }
    //Curl添加Post表单
    if($Var!='')curl_setopt( $ch, CURLOPT_POSTFIELDS,$Var );
    //设置超时时间
    curl_setopt( $ch, CURLOPT_TIMEOUT, 30 );
    //获取返回的Json源码
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    //发送请求并对返回的结果进行Json解码
    $response =json_decode(curl_exec($ch),1);
    //获取Http返回状态
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //释放Curl
    curl_close($ch);
    //返回资源数据与Http状态
    return array($http_status,$response);
  }
  //获取主账号资金信息
  function GetAccoutInfo(){
    $r = $this->SendCommand("account/info");
    return $r[0]==200?$r[1]:null;
  }
  //得到所有Vps信息
  function GetAllServerInfo(){
    $r = $this->SendCommand("server/list");
    return $r[0]==200?$r[1]:null;
  }
  //得到指定Vps信息
  function GetServerInfo($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/list",$data);
    return $r[0]==200?$r[1]:null;
  }
  //Vps重启
  function RebootServer($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/reboot",null,$data);
    return $r[0]==200?true:false;
  }
  //Vps关机
  function PowerOffServer($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/halt",null,$data);
    return $r[0]==200?true:false;
  }
  //Vps开机
  function StartServer($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/start",null,$data);
    return $r[0]==200?true:false;
  }
  //设置Vps标签
  function SetServerLabel($subid,$label){
    if(trim($subid)==''||trim($label)=='')return false;
    $data['SUBID']=trim($subid);
    $data['label']=trim($label);
    $r = $this->SendCommand("server/label_set",null,$data);
    return $r[0]==200?true:false;
  }
  //获取Vps可升级套餐
  function GetServerUpgradePlan($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/upgrade_plan_list",$data,null);
    return $r[0]==200?$r[1]:null;
  }
  //Vps套餐升级
  function ServerUpgradePlan($subid,$vpsplanid){
    if(trim($subid)==''||trim($vpsplanid)=='')return false;
    $data['SUBID'] = trim($subid);
    $data['VPSPLANID'] = trim($vpsplanid);
    $r = $this->SendCommand("server/upgrade_plan_list",null,$data);
    return $r[0]==200?true:null;
  }
  //获取可选OS系统列表
  function GetOs(){
    $r = $this->SendCommand("os/list",null,null);
    return $r[0]==200?$this->OptimizeOsList($r[1]):null;
  }
  //可选OS数据分组
  function OsToGroup($Os){
    if(!is_array($Os)) return null;
    $group=array();
    foreach ($Os as $key => $value) {
        $group[$value['family']][]=$value;
    }
    return $group;
  }
  //优化OS分组删除Backup等
  private function OptimizeOsList($Os){
    unset($Os['124']);
    unset($Os['159']);
    unset($Os['164']);
    unset($Os['180']);
    unset($Os['186']);
    return $Os;
  }
  //获取可选套餐列表
  function GetPlans(){
    $r = $this->SendCommand("plans/list",null,null);
    return $r[0]==200?$r[1]:null;
  }
  //获取可选地区列表
  function GetRegions(){
    $r = $this->SendCommand("regions/list",null,null);
    return $r[0]==200?$r[1]:null;
  }
  //可选地区数据分组
  function RegionsToGroup($regions,$ToChinese = true){
    if(!is_array($regions)) return null;
    if($ToChinese)$regions = $this->RegionsNameToChinese($regions);
    $group=array();
    foreach ($regions as $key => $value) {
        $group[$value['continent']][]=$value;
    }
    return $group;
  }
  //根据语言配置将地区数据翻译中文
  private function RegionsNameToChinese($regions){
    $ch = C("ChineseLanguage.Regions");
    if($ch==null)return $regions;
    foreach ($regions as &$value) {
        foreach ($value as &$val) {
          if($ch[$val]!=null){
            $val=$ch[$val];
          }
        }
    }
    return $regions;
  }
  //获取可安装的一键应用程序包
  function GetApplication(){
    $r = $this->SendCommand("app/list",null,null);
    return $r[0]==200?$r[1]:null;
  }
  //获取上传的ISO镜像文件
  function GetMyIso(){
    $r = $this->SendCommand("iso/list",null,null);
    return $r[0]==200?$r[1]:null;
  }
  //获取一台Vps的流量信息
  function GetBandWidth($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/bandwidth",$data,null);
    return $r[0]==200?$r[1]:null;
  }
  //删除一台Vps(数据丢失不可撤销!慎用)
  function DestroyServer($subid){
    if(trim($subid)=='')return false;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/bandwidth",null,$data);
    return $r[0]==200?true:false;
  }
  //获取一台Vps的所有Ipv4地址
  function GetServerIpv4($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/list_ipv4",$data,null);
    return $r[0]==200?$r[1]:null;
  }
  //获取一台Vps可更换的OS系统列表
  function GetServerOsChangeList($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/os_change_list",$data,null);
    return $r[0]==200?$r[1]:null;
  }
  //Vps更换OS系统(数据丢失不可撤销!慎用)
  function ServerOsChange($subid,$osid){
    if(trim($subid)==''||trim($osid)=='')return false;
    $data['SUBID'] = trim($subid);
    $data['OSID'] = trim($osid);
    $r = $this->SendCommand("server/os_change",null,$data);
    return $r[0]==200?true:false;
  }
  //获取一台Vps可更换的App列表
  function GetServerAppChangeList($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/app_change_list",$data,null);
    return $r[0]==200?$r[1]:null;
  }
  //Vps更换APP(数据丢失不可撤销!慎用)
  function ServerAppChange($subid,$appid){
    if(trim($subid)==''||trim($appid)=='')return false;
    $data['SUBID'] = trim($subid);
    $data['APPID'] = trim($appid);
    $r = $this->SendCommand("server/app_change",null,$data);
    return $r[0]==200?true:false;
  }
  //Vps系统恢复初始状态(数据丢失不可撤销!慎用)
  function ServerReinstall($subid){
    if(trim($subid)=='')return false;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/reinstall",null,$data);
    return $r[0]==200?true:false;
  }
  //创建Vps SSH登陆密钥
  function CreateSshKey($name,$ssh_key){
    if(trim($name)==''||trim($ssh_key)=='')return false;
    $data['name'] = trim($name);
    $data['ssh_key'] = trim($ssh_key);
    $r = $this->SendCommand("sshkey/create",null,$data);
    return $r[0]==200?$r[1]['SSHKEYID']:false;
  }
  //删除SSH密钥
  function DestroySshKey($sshkeyid){
    if(trim($sshkeyid)=='')return false;
    $data['SSHKEYID'] = trim($sshkeyid);
    $r = $this->SendCommand("sshkey/destroy",null,$data);
    return $r[0]==200?true:false;
  }
  //列出所有SSH密钥
  function ListSshKey(){
    $r = $this->SendCommand("sshkey/list");
    return $r[0]==200?$r[1]:null;
  }
  //更新指定SSH密钥
  function UpdateSshKey($sshkeyid,$name,$ssh_key){
    if(trim($sshkeyid)==''||trim($name)==''||trim($ssh_key)=='')return false;
    $data['SSHKEYID'] = trim($sshkeyid);
    $data['name'] = trim($name);
    $data['ssh_key'] = trim($ssh_key);
    $r = $this->SendCommand("sshkey/update",null,$data);
    return $r[0]==200?true:false;
  }
  //创建Vps Startup Script启动脚本 (TYPE boot|pxe)
  function CreateScript($name,$script,$type = 'boot'){
    if(trim($name)==''||$script=='')return false;
    $data['name'] = trim($name);
    $data['script'] = $script;
    $data['type'] = trim($type);
    $r = $this->SendCommand("startupscript/create",null,$data);
    return $r[0]==200?$r[1]['SCRIPTID']:false;
  }
  //删除Startup Script启动脚本
  function DestroyScript($scriptid){
    if(trim($scriptid)=='')return false;
    $data['SCRIPTID'] = trim($scriptid);
    $r = $this->SendCommand("startupscript/destroy",null,$data);
    return $r[0]==200?true:false;
  }
  //列出所有Startup Script启动脚本
  function ListScript(){
    $r = $this->SendCommand("startupscript/list");
    return $r[0]==200?$r[1]:null;
  }
  //更新指定Startup Script启动脚本
  function UpdateScript($scriptid,$name,$script){
    if(trim($scriptid)==''||trim($name)==''||$script=='')return false;
    $data['SCRIPTID'] = trim($scriptid);
    $data['name'] = trim($name);
    $data['script'] = $script;
    $r = $this->SendCommand("startupscript/update",null,$data);
    return $r[0]==200?true:false;
  }
}