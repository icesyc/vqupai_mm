<?php
$this->pageTitle = '出错了！'; ?>


<h3><?php echo $message;?></h3>
<p>file <?php echo $file;?></p>
<p>line <?php echo $line;?></p>
<pre>
<?php print_r($trace);?>
</pre>
<p>有任何问题请联系开发人员 icesyc, weizhao.</p>
