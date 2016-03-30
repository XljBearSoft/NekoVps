<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
  <meta charset="UTF-8">
  <title>Neko Vps | Moe Vps</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport"
        content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no">
  <meta name="renderer" content="webkit">
  <meta http-equiv="Cache-Control" content="no-siteapp"/>
  <link rel="alternate icon" type="image/png" href="/nekovps/Public/assets/images/cat.png">
  <link rel="stylesheet" href="/nekovps/Public/assets/css/amazeui.min.css"/>
  <script src="/nekovps/Public/assets/js/jquery.min.js"></script>
  <script src="/nekovps/Public/assets/js/amazeui.min.js"></script>
  <style type="text/css">
    
  </style>
</head>
<body>
服务器地区：
<select data-am-selected="{btnSize: 'sm', btnStyle: 'secondary',maxHeight:300,searchBox: 1}">
<?php if(is_array($regionsList)): foreach($regionsList as $k=>$regionsGroup): ?><optgroup label="<?php echo ($k); ?>">
  <?php if(is_array($regionsGroup)): $i = 0; $__LIST__ = $regionsGroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$regions): $mod = ($i % 2 );++$i;?><option value="<?php echo ($regions["DCID"]); ?>"><?php echo ($regions["country"]); ?> - <?php echo ($regions["name"]); ?>机房</option><?php endforeach; endif; else: echo "" ;endif; ?>
  </optgroup><?php endforeach; endif; ?>
</select>
<br>
<br>
操作系统：
<select data-am-selected="{btnSize: 'sm', btnStyle: 'secondary',maxHeight:300,searchBox: 1}">
<?php if(is_array($osList)): foreach($osList as $k=>$osGroup): ?><optgroup label="<?php echo ($k); ?>">
  <?php if(is_array($osGroup)): $i = 0; $__LIST__ = $osGroup;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$os): $mod = ($i % 2 );++$i;?><option value="<?php echo ($os["OSID"]); ?>"><?php echo ($os["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
  </optgroup><?php endforeach; endif; ?>
</select>
<br>
<br>
<?php if(is_array($planList)): $i = 0; $__LIST__ = $planList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$plan): $mod = ($i % 2 );++$i;?><button class="am-btn am-btn-secondary am-btn-xl"><?php echo ($plan["name"]); ?></button><?php endforeach; endif; else: echo "" ;endif; ?>
</body>
</html>