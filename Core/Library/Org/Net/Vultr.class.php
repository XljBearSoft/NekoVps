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
    //得到返回的Json源码
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    //发送请求并对返回的结果进行Json解码
    $response =json_decode(curl_exec($ch),1);
    //得到Http返回状态
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    //释放Curl
    curl_close($ch);
    //返回资源数据与Http状态
    return array($http_status,$response);
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
  function GetServerPlanList($subid){
    if(trim($subid)=='')return null;
    $data['SUBID'] = trim($subid);
    $r = $this->SendCommand("server/upgrade_plan_list",$data,null);
    return $r[0]==200?$r[1]:null;
  }
  //得到可选Os系统列表
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
  //得到可选套餐列表
  function GetPlans(){
    $r = $this->SendCommand("plans/list",null,null);
    return $r[0]==200?$r[1]:null;
  }
  //得到可选地区列表
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
  //得到可安装的一键应用程序包
  function GetApplication(){
    $r = $this->SendCommand("app/list",null,null);
    return $r[0]==200?$r[1]:null;
  }
  function GetMyIso(){
    $r = $this->SendCommand("iso/list",null,null);
    return $r[0]==200?$r[1]:null;
  }
}