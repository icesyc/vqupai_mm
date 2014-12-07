<style type="text/css">
div {
width: 640px;
margin: 0 auto;
}
</style>

<div>
<h3>九宫格抽奖统计</h3>
</div>
<div>
<h4>奖品消耗: <?php echo $cost_all;?> 元</h4>
</div>
<div>
<table class='items table table-hover table-striped table-condensed'>
<tr><th>名称</th><th>单价</th><th>总量</th><th>中奖量</th><th>消耗</th></tr>
<?php
foreach($stock as $id=>$a){
	echo '<tr>';
	echo '<td>';
	echo $a['name'];
	echo '</td>';
	echo '<td>';
	echo $a['price'];
	echo '</td>';
	echo '<td>';
	echo $a['num'];
	echo '</td>';
	echo '<td>';
	echo $a['use_num'];
	echo '</td>';
	echo '<td>';
	echo $a['cost'];
	echo '</td>';
	echo '</tr>';
}
?>
</table>
</div>
