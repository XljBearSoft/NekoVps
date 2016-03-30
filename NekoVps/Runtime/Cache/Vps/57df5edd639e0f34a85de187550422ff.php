<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>用户登录 Neko Vps | Moe Vps</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp" />
  <link rel="alternate icon" type="image/png" href="/nekovps/Public/assets/images/cat.png">
  <link rel="stylesheet" href="/nekovps/Public/assets/css/amazeui.min.css"/>
  <script src="/nekovps/Public/assets/js/jquery.min.js"></script>
  <script src="/nekovps/Public/assets/js/amazeui.min.js"></script>
  <style>
    .header {
      text-align: center;
    }
    .header h1 {
      font-size: 200%;
      color: #333;
    }
    .header p {
      font-size: 14px;
    }
    .cat-ico{
      width:32px;
      height:32px;
      background-image:url(/nekovps/Public/assets/images/cat.png);
      background-repeat:no-repeat;
      display:inline-block;
    }
    body{
      background-color:#f8f8f8;
    }
    h1{
      box-shadow:5px 5px 20px #AAA;
      padding:5px 0px;
      background-color:#05A2EC;
      line-height:60px;
      color:#FFFFFF !important;
      text-shadow: 0px 0px 2px #686868, 0px 1px 1px #ddd, 0px 2px 1px #d6d6d6, 0px 3px 1px #ccc, 0px 4px 1px #c5c5c5, 0px 5px 1px #c1c1c1, 0px 6px 1px #bbb, 0px 7px 1px #777, 0px 8px 3px rgba(100, 100, 100, 0.4), 0px 9px 5px rgba(100, 100, 100, 0.1), 0px 10px 7px rgba(100, 100, 100, 0.15), 0px 11px 9px rgba(100, 100, 100, 0.2), 0px 12px 11px rgba(100, 100, 100, 0.25), 0px 13px 15px rgba(100, 100, 100, 0.3);
    }
    input{
      box-shadow:2px 2px 3px #ADADAD;
    }
    button{
      box-shadow:1px 1px 2px #ADADAD;
    }
    a{
      text-shadow:2px 2px 2px #CBE0FD;
    }
  </style>
</head>
<body>
<div class="header">
  <div class="am-g">
  <h1>欢迎回到 Neko Vps</h1>
  </div>
  <hr />
</div>
<div class="am-g">
  <div class="am-u-lg-6 am-u-md-8 am-u-sm-centered">
    <a href="<?php echo U('index');?>"><<回到Neko Vps</a>
    <a class="am-fr" href="<?php echo U('register');?>">还没有Neko账户?</a>
    <br>
    <hr>
    <form method="post" class="am-form">
      <label for="email">邮箱:</label>
      <input type="email" name="email" id="email" value="">
      <br>
      <label for="password">密码:</label>
      <input type="password" name="password" id="password" value="">
      <br>
      登陆有效期:
      <select data-am-selected="{btnStyle: 'secondary'}">
        <option value="0">随浏览器</option>
        <option value="1">1小时</option>
        <option value="2">今天</option>
        <option value="3">一星期</option>
        <option value="4">一个月</option>
        <option value="5">一年</option>
      </select>
      <br>
      <br>
      <div class="am-cf">
      <button id="forget" class="am-btn am-btn-default am-radius am-fr">忘记密码</button>
      <button id="login" class="am-btn am-btn-primary am-radius am-fr">登 录</button>
      </div>
    </form>
    <hr>
    <p>© 2016 <a href="http://www.nekoneko.moe" target="_blank">NekoVps, Inc.</a> . by the XljBearSoft Team.</p>
  </div>
</div>
</body>
</html>