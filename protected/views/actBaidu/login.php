<?php
Yii::app()->clientScript->registerCss('css', '
  body,table{
    font-size:14px;
  }
  .btn {
    margin-left:0;
    -webkit-appearance:none;
  }
  .success{
    padding-top:12em;
  }
  table{
    margin:0 auto;
  }
  .error{
    color:#fff;
    text-align:center;
    padding:18px 0 5px 0;
  }
');
?>
<div class="success">
    <div class="error">
      <?php echo $errorInfo;?>
    </div>
    <form enctype="multipart/form-data" id="reg-form" method="post" action='index.php?r=activitySep/login'>
      <table>
      <tr><td>用户名 </td><td><input type="text" name="uname" id="uname" maxlength="32"  value="<?php echo $user->uname ?>"></td></tr>
      <tr><td>密　码 </td><td><input type="password" name="passwd" id="Login_passwd" maxlength="32" value="<?php echo $user->passwd ?>"></td></tr>
      <tr><td>&nbsp;</td>
        <td>
          <input type="submit" class="btn" value="登 录">
          <input type="button" class="btn" value="注 册" onclick="location.href='index.php?r=activitySep/reg'">
        </td>
      </tr>
      </table>
    </form>
  </div>
</div>