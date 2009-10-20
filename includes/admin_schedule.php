<?php
require_once(USCES_PLUGIN_DIR . '/classes/calendar.class.php');

//当月
list($todayyy, $todaymm, $todaydd) = getToday();	// 今日
$cal1 = new calendarData();
$cal1->setToday($todayyy, $todaymm, $todaydd);
$cal1->setCalendarData();
//翌月
list($nextyy, $nextmm, $nextdd) = getAfterMonth($todayyy, $todaymm, $todaydd, 1);
$cal2 = new calendarData();
$cal2->setToday($nextyy, $nextmm, $nextdd);
$cal2->setCalendarData();
//翌々月
list($lateryy, $latermm, $laterdd) = getAfterMonth($todayyy, $todaymm, $todaydd, 2);
$cal3 = new calendarData();
$cal3->setToday($lateryy, $latermm, $laterdd);
$cal3->setCalendarData();



$status = $this->action_status;
$message = $this->action_message;
$this->action_status = 'none';
$this->action_message = '';
?>
<script type="text/javascript">
jQuery(function($){
<?php if($status == 'success'){ ?>
			$("#anibox").animate({ backgroundColor: "#ECFFFF" }, 2000);
<?php }else if($status == 'caution'){ ?>
			$("#anibox").animate({ backgroundColor: "#FFF5CE" }, 2000);
<?php }else if($status == 'error'){ ?>
			$("#anibox").animate({ backgroundColor: "#FFE6E6" }, 2000);
<?php } ?>

	$("#aAdditionalURLs").click(function () {
		$("#AdditionalURLs").toggle();
	});
});

function toggleVisibility(id) {
   var e = document.getElementById(id);
   if(e.style.display == 'block')
	  e.style.display = 'none';
   else
	  e.style.display = 'block';
}

function cangeBus(id, r, c) {
	var e = document.getElementById(id+'_'+r+'_'+c);
	var v = document.getElementById(id).rows[r].cells[c];
	if (e.value == '0') {
		e.value = '1';
		v.style.backgroundColor = '#DFFFDD';
	} else {
		e.value = '0';
		v.style.backgroundColor = '#FFECCE';
	}
}

function cangeWday1(id, c) {
<?php for ($i = 0; $i < $cal1->getRow(); $i++) : ?>
	if (document.getElementById(id+'_'+<?php echo ($i+1); ?>+'_'+c)) {
		var e = document.getElementById(id+'_'+<?php echo ($i+1); ?>+'_'+c);
		var v = document.getElementById(id).rows[<?php echo ($i+1); ?>].cells[c];
		if (e.value == '0') {
			e.value = '1';
			v.style.backgroundColor = '#DFFFDD';
		} else {
			e.value = '0';
			v.style.backgroundColor = '#FFECCE';
		}
	}
<?php endfor; ?>
}

function cangeWday2(id, c) {
<?php for ($i = 0; $i < $cal2->getRow(); $i++) : ?>
	if (document.getElementById(id+'_'+<?php echo ($i+1); ?>+'_'+c)) {
		var e = document.getElementById(id+'_'+<?php echo ($i+1); ?>+'_'+c);
		var v = document.getElementById(id).rows[<?php echo ($i+1); ?>].cells[c];
		if (e.value == '0') {
			e.value = '1';
			v.style.backgroundColor = '#DFFFDD';
		} else {
			e.value = '0';
			v.style.backgroundColor = '#FFECCE';
		}
	}
<?php endfor; ?>
}

function cangeWday3(id, c) {
<?php for ($i = 0; $i < $cal3->getRow(); $i++) : ?>
	if (document.getElementById(id+'_'+<?php echo ($i+1); ?>+'_'+c)) {
		var e = document.getElementById(id+'_'+<?php echo ($i+1); ?>+'_'+c);
		var v = document.getElementById(id).rows[<?php echo ($i+1); ?>].cells[c];
		if (e.value == '0') {
			e.value = '1';
			v.style.backgroundColor = '#DFFFDD';
		} else {
			e.value = '0';
			v.style.backgroundColor = '#FFECCE';
		}
	}
<?php endfor; ?>
}

</script>
<div class="wrap">
<div class="usces_admin">
<h2>Welcart Shop 営業日設定<?php //echo __('USC e-Shop Options','usces'); ?></h2>
<div id="aniboxStatus" class="<?php echo $status; ?>">
	<div id="anibox" class="clearfix">
		<img src="<?php echo USCES_PLUGIN_URL; ?>/images/list_message_<?php echo $status; ?>.gif" />
		<div class="mes" id="info_massage"><?php echo $message; ?></div>
	</div>
</div>
<form action="" method="post" name="option_form" id="option_form">
<input name="usces_option_update" type="submit" class="button" value="設定を更新" />
<div id="poststuff" class="metabox-holder">

<div class="postbox">
<h3 class="hndle"><span>キャンペーン･スケジュール</span><a style="cursor:pointer;" onclick="toggleVisibility('ex_campaign_schedule');">（説明）</a></h3>
<div class="inside">
<table class="form_table">
	<tr>
	    <th>開始日時</th>
	    <td><select name="campaign_schedule[start][year]">
	    		<option value="0"<?php if($this->options['campaign_schedule']['start']['year'] == 0) echo ' selected="selected"'; ?>></option>
	    		<option value="<?php echo date('Y'); ?>"<?php if($this->options['campaign_schedule']['start']['year'] == date('Y')) echo ' selected="selected"'; ?>><?php echo date('Y'); ?></option>
	    		<option value="<?php echo date('Y')+1; ?>"<?php if($this->options['campaign_schedule']['start']['year'] == (date('Y')+1)) echo ' selected="selected"'; ?>><?php echo date('Y')+1; ?></option>
		</select></td>
		<td>年</td>
	    <td><select name="campaign_schedule[start][month]">
	    		<option value="0"<?php if($this->options['campaign_schedule']['start']['month'] == 0) echo ' selected="selected"'; ?>></option>
<?php for($i=1; $i<13; $i++) : ?>
	    		<option value="<?php echo $i; ?>"<?php if($this->options['campaign_schedule']['start']['month'] == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
<?php endfor; ?>
		</select></td>
		<td>月</td>
	    <td><select name="campaign_schedule[start][day]">
	    		<option value="0"<?php if($this->options['campaign_schedule']['start']['day'] == 0) echo ' selected="selected"'; ?>></option>
<?php for($i=1; $i<32; $i++) : ?>
	    		<option value="<?php echo $i; ?>"<?php if($this->options['campaign_schedule']['start']['day'] == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
<?php endfor; ?>
		</select></td>
		<td>日</td>
	    <td><select name="campaign_schedule[start][hour]">
<?php for($i=0; $i<24; $i++) : ?>
	    		<option value="<?php echo $i; ?>"<?php if($this->options['campaign_schedule']['start']['hour'] == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
<?php endfor; ?>
		</select></td>
		<td>時</td>
	    <td><select name="campaign_schedule[start][min]">
<?php for($i=0; $i<12; $i++) : ?>
	    		<option value="<?php echo $i*5; ?>"<?php if($this->options['campaign_schedule']['min']['hour'] == ($i*5)) echo ' selected="selected"'; ?>><?php echo $i*5; ?></option>
<?php endfor; ?>
		</select></td>
		<td>分</td>
	</tr>
	<tr>
	    <th>終了日時</th>
	    <td><select name="campaign_schedule[end][year]">
	    		<option value="0"<?php if($this->options['campaign_schedule']['end']['year'] == 0) echo ' selected="selected"'; ?>></option>
	    		<option value="<?php echo date('Y'); ?>"<?php if($this->options['campaign_schedule']['end']['year'] == date('Y')) echo ' selected="selected"'; ?>><?php echo date('Y'); ?></option>
	    		<option value="<?php echo date('Y')+1; ?>"<?php if($this->options['campaign_schedule']['end']['year'] == (date('Y')+1)) echo ' selected="selected"'; ?>><?php echo date('Y')+1; ?></option>
		</select></td>
		<td>年</td>
	    <td><select name="campaign_schedule[end][month]">
	    		<option value="0"<?php if($this->options['campaign_schedule']['end']['month'] == 0) echo ' selected="selected"'; ?>></option>
<?php for($i=1; $i<13; $i++) : ?>
	    		<option value="<?php echo $i; ?>"<?php if($this->options['campaign_schedule']['end']['month'] == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
<?php endfor; ?>
		</select></td>
		<td>月</td>
	    <td><select name="campaign_schedule[end][day]">
	    		<option value="0"<?php if($this->options['campaign_schedule']['end']['day'] == 0) echo ' selected="selected"'; ?>></option>
<?php for($i=1; $i<32; $i++) : ?>
	    		<option value="<?php echo $i; ?>"<?php if($this->options['campaign_schedule']['end']['day'] == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
<?php endfor; ?>
		</select></td>
		<td>日</td>
	    <td><select name="campaign_schedule[end][hour]">
<?php for($i=0; $i<24; $i++) : ?>
	    		<option value="<?php echo $i; ?>"<?php if($this->options['campaign_schedule']['end']['hour'] == $i) echo ' selected="selected"'; ?>><?php echo $i; ?></option>
<?php endfor; ?>
		</select></td>
		<td>時</td>
	    <td><select name="campaign_schedule[end][min]">
<?php for($i=0; $i<12; $i++) : ?>
	    		<option value="<?php echo $i*5; ?>"<?php if($this->options['campaign_schedule']['min']['hour'] == ($i*5)) echo ' selected="selected"'; ?>><?php echo $i*5; ?></option>
<?php endfor; ?>
		</select></td>
		<td>分</td>
	</tr>
</table>
<hr size="1" color="#CCCCCC" />
<div id="ex_campaign_schedule" class="explanation">キャンペーンの開催期間を予約します。</div>
</div>
</div><!--postbox-->

<div class="postbox">
<h3 class="hndle"><span>営業日カレンダー</span><a style="cursor:pointer;" onclick="toggleVisibility('ex_shipping_charge');">（説明）</a></h3>
<div class="inside">
<table class="form_table">
	<tr>
	    <th>今月<br /><?php echo  $todayyy.'年'.$todaymm.'月'; ?></th>
	    <td>
		<table cellspacing="0" id="calendar1" class="calendar">
			<tr>
				<th class="cal"><div onclick="cangeWday1('calendar1', '0');"><font color="#FF3300">日</font></div></th>
				<th class="cal"><div onclick="cangeWday1('calendar1', '1');">月</div></th>
				<th class="cal"><div onclick="cangeWday1('calendar1', '2');">火</div></th>
				<th class="cal"><div onclick="cangeWday1('calendar1', '3');">水</div></th>
				<th class="cal"><div onclick="cangeWday1('calendar1', '4');">木</div></th>
				<th class="cal"><div onclick="cangeWday1('calendar1', '5');">金</div></th>
				<th class="cal"><div onclick="cangeWday1('calendar1', '6');">土</div></th>
			</tr>
<?php for ($i = 0; $i < $cal1->getRow(); $i++) : ?>
			<tr>
	<?php for ($d = 0; $d <= 6; $d++) : 
			$mday = $cal1->getDateText($i, $d);
			if ($mday != "") {
				$business = $this->options['business_days'][$todayyy][$todaymm][$mday];
				$color = ($business == 1) ? "#DFFFDD" : "#FFECCE"; ?>
				<td class="cal" style="background-color:<?php echo $color; ?>"><div onclick="cangeBus('calendar1', <?php echo ($i + 1); ?>, <?php echo $d; ?>);"><?php echo $mday; ?></div>
				<input name="business_days[<?php echo $todayyy; ?>][<?php echo $todaymm; ?>][<?php echo $mday; ?>]" id="calendar1_<?php echo ($i+1); ?>_<?php echo $d; ?>" type="hidden" value="<?php echo $business; ?>"></td>
		<?php } else { ?>
				<td>&nbsp;</td>
		<?php } ?>
	<?php endfor; ?>
			</tr>
<?php endfor; ?>
		</table>
		</td>
		<td><span class="business_days_exp_box" style="background-color:#DFFFDD">　　</span>営業日<br /><span class="business_days_exp_box" style="background-color:#FFECCE">　　</span>発送業務休日</td>
	</tr>
	<tr>
	    <th>翌月<br /><?php echo  $nextyy.'年'.$nextmm.'月'; ?></th>
	    <td>
		<table cellspacing="0" id="calendar2" class="calendar">
			<tr>
				<th class="cal"><div onclick="cangeWday2('calendar2', '0');"><font color="#FF3300">日</font></div></th>
				<th class="cal"><div onclick="cangeWday2('calendar2', '1');">月</div></th>
				<th class="cal"><div onclick="cangeWday2('calendar2', '2');">火</div></th>
				<th class="cal"><div onclick="cangeWday2('calendar2', '3');">水</div></th>
				<th class="cal"><div onclick="cangeWday2('calendar2', '4');">木</div></th>
				<th class="cal"><div onclick="cangeWday2('calendar2', '5');">金</div></th>
				<th class="cal"><div onclick="cangeWday2('calendar2', '6');">土</div></th>
			</tr>
<?php for ($i = 0; $i < $cal2->getRow(); $i++) : ?>
			<tr>
	<?php for ($d = 0; $d <= 6; $d++) : 
			$mday = $cal2->getDateText($i, $d);
			if ($mday != "") {
				$business = $this->options['business_days'][$nextyy][$nextmm][$mday];
				$color = ($business == 1) ? "#DFFFDD" : "#FFECCE"; ?>
				<td class="cal" style="background-color:<?php echo $color; ?>"><div onclick="cangeBus('calendar2', <?php echo ($i + 1); ?>, <?php echo $d; ?>);"><?php echo $mday; ?></div>
				<input name="business_days[<?php echo $nextyy; ?>][<?php echo $nextmm; ?>][<?php echo $mday; ?>]" id="calendar2_<?php echo ($i+1); ?>_<?php echo $d; ?>" type="hidden" value="<?php echo $business; ?>"></td>
		<?php } else { ?>
				<td>&nbsp;</td>
		<?php } ?>
	<?php endfor; ?>
			</tr>
<?php endfor; ?>
		</table>
		</td>
		<td>&nbsp;</td>
	</tr>
	<tr>
	    <th>翌々月<br /><?php echo  $lateryy.'年'.$latermm.'月'; ?></th>
	    <td>
		<table cellspacing="0" id="calendar3" class="calendar">
			<tr>
				<th class="cal"><div onclick="cangeWday3('calendar3', '0');"><font color="#FF3300">日</font></div></th>
				<th class="cal"><div onclick="cangeWday3('calendar3', '1');">月</div></th>
				<th class="cal"><div onclick="cangeWday3('calendar3', '2');">火</div></th>
				<th class="cal"><div onclick="cangeWday3('calendar3', '3');">水</div></th>
				<th class="cal"><div onclick="cangeWday3('calendar3', '4');">木</div></th>
				<th class="cal"><div onclick="cangeWday3('calendar3', '5');">金</div></th>
				<th class="cal"><div onclick="cangeWday3('calendar3', '6');">土</div></th>
			</tr>
<?php for ($i = 0; $i < $cal3->getRow(); $i++) : ?>
			<tr>
	<?php for ($d = 0; $d <= 6; $d++) : 
			$mday = $cal3->getDateText($i, $d);
			if ($mday != "") {
				$business = $this->options['business_days'][$lateryy][$latermm][$mday];
				$color = ($business == 1) ? "#DFFFDD" : "#FFECCE"; ?>
				<td class="cal" style="background-color:<?php echo $color; ?>"><div onclick="cangeBus('calendar3', <?php echo ($i + 1); ?>, <?php echo $d; ?>);"><?php echo $mday; ?></div>
				<input name="business_days[<?php echo $lateryy; ?>][<?php echo $latermm; ?>][<?php echo $mday; ?>]" id="calendar3_<?php echo ($i+1); ?>_<?php echo $d; ?>" type="hidden" value="<?php echo $business; ?>"></td>
		<?php } else { ?>
				<td>&nbsp;</td>
		<?php } ?>
	<?php endfor; ?>
			</tr>
<?php endfor; ?>
		</table>
		</td>
		<td>&nbsp;</td>
	</tr>
</table>
<hr size="1" color="#CCCCCC" />
<div id="ex_shipping_charge" class="explanation">3種類の送料を商品ごとに選ぶことができます。</div>
</div>
</div><!--postbox-->


</div><!--poststuff-->
<input name="usces_option_update" type="submit" class="button" value="設定を更新" />
</form>
</div><!--usces_admin-->
</div><!--wrap-->