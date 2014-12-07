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
    <form enctype="multipart/form-data" id="reg-form" method="post" action='index.php?r=activitySep/reg'>
      <table>
      <tr><td>手&nbsp;&nbsp;&nbsp;机</td><td><input type="text" name="uname" id="uname" maxlength="32" value="<?php echo $user->uname ?>"></td></tr>
      <tr><td>密&nbsp;&nbsp;&nbsp;码 </td><td><input type="password" name="passwd" id="passwd" maxlength="32" value="<?php echo $user->passwd ?>"></td></tr>
      <tr><td>昵&nbsp;&nbsp;&nbsp;称</td><td><input type="text" name="nick" id="nick" maxlength="32" value="<?php echo $user->nick ?>"></td></tr>
      <tr><td>&nbsp;</td>
        <td>
          <input type="submit" class="btn" value="提 交">
        </td>
      </tr>
      </table>
    </form>
  </div>
</div>