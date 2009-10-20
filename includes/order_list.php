<?php
require_once( USCES_PLUGIN_DIR . "/classes/orderList.class.php" );
global $wpdb;

$tableName = $wpdb->prefix . "usces_order";
$arr_column = array(
			'注文No' => 'ID', 
			'日付' => 'date', 
			'会員No' => 'mem_id', 
			'氏名' => 'name', 
			'地区' => 'pref', 
			'配送方法' => 'delivery_method', 
			'金額' => 'total_price', 
			'支払方法' => 'payment_name', 
			'入金状況' => 'receipt_status', 
			'処理状態' => 'order_status', 
			'発送日' => 'order_modified');

$DT = new dataList($tableName, $arr_column);
$res = $DT->MakeTable();
$arr_search = $DT->GetSearchs();
$arr_status = get_option('usces_management_status');
$arr_header = $DT->GetListheaders();
$dataTableNavigation = $DT->GetDataTableNavigation();
$rows = $DT->rows;
$status = $DT->get_action_status();
$message = $DT->get_action_message();
$pref = get_option('usces_pref');
foreach ( (array)$this->options['payment_method'] as $id => $array ) {
	$payment_name[$id] = $this->options['payment_method'][$id]['name'];
}
$ums = get_option('usces_management_status');
foreach((array)$ums as $key => $value){
	if($key == 'noreceipt' || $key == 'receipted'){
		$receipt_status[$key] = $value;
	}else{
		$order_status[$key] = $value;
	}
}
$order_status['new'] = '新規受付';
$curent_url = urlencode(USCES_ADMIN_URL . '?' . $_SERVER['QUERY_STRING']);
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

//	$("#aAdditionalURLs").click(function () {
//		$("#AdditionalURLs").toggle();
//	});

	$("input[name='allcheck']").click(function () {
		if( $(this).attr("checked") ){
			$("input[name*='listcheck']").attr({checked: true});
		}else{
			$("input[name*='listcheck']").attr({checked: false});
		}
	});
	
	$("#searchselect").change(function () {
		operation.change_search_field();
	});

	$("#changeselect").change(function () {
		operation.change_collective_field();
	});

	$("#collective_change").click(function () {
		if( $("input[name*='listcheck']:checked").length == 0 ) {
			alert("データが選択されていません。");
			$("#oederlistaction").val('');
			return false;
		}
		var coll = $("#changeselect").val();
		var mes = '';
		if( coll == 'order_reciept' ){
			mes = 'チェックされたデータの入金状況を「' + $("select[name='change\[word\]\[order_reciept\]'] option:selected").html() + '」に変更します。\n\nよろしいですか？';
		}else if( coll == 'order_status' ){
			mes = 'チェックされたデータの処理状態を、すべて「' + $("select[name='change\[word\]\[order_status\]'] option:selected").html() + '」に変更します。'+"\n\nよろしいですか？";
		}else if(coll == 'delete'){
			mes = "チェックされたデータを一括削除します。\n\nよろしいですか？";
		}else{
			$("#oederlistaction").val('');
			return false;
		}
		if( !confirm(mes) ){
			$("#oederlistaction").val('');
			return false;
		}
		$("#oederlistaction").val('collective');
		return true;
	});

	operation = {
		change_search_field :function (){
		
			var label = '';
			var html = '';
			var column = $("#searchselect").val();
			
			if( column == 'ID' ) {
				label = 'キーワード';
				html = '<input name="search[word][ID]" type="text" value="<?php echo $arr_search['word']['ID'] ?>" class="searchword" maxlength="50" />';
			}else if( column == 'date' ) {
				label = 'キーワード';
				html = '<input name="search[word][date]" type="text" value="<?php echo $arr_search['word']['date'] ?>" class="searchword" maxlength="50" />';
			}else if( column == 'mem_id' ) {
				label = 'キーワード';
				html = '<input name="search[word][mem_id]" type="text" value="<?php echo $arr_search['word']['mem_id'] ?>" class="searchword" maxlength="50" />';
			}else if( column == 'name' ) {
				label = 'キーワード';
				html = '<input name="search[word][name]" type="text" value="<?php echo $arr_search['word']['name'] ?>" class="searchword" maxlength="50" />';
			}else if( column == 'order_modified' ) {
				label = 'キーワード';
				html = '<input name="search[word][order_modified]" type="text" value="<?php echo $arr_search['word']['order_modified'] ?>" class="searchword" maxlength="50" />';
			}else if( column == 'pref' ) {
				label = '';
				html = '<select name="search[word][pref]" class="searchselect">';
		<?php foreach((array)$pref as $pkey => $pvalue){ 
				if($pvalue == $arr_search['word']['pref']){
					$pselected = ' selected="selected"';
				}else{
					$pselected = '';
				}
		?>
				html += '<option value="<?php echo $pvalue; ?>"<?php echo $pselected ?>><?php echo $pvalue ?></option>';
		<?php } ?>
				html += '</select>';
			}else if( column == 'delivery_method' ) {
				label = '';
				html = '<select name="search[word][delivery_method]" class="searchselect">';
		<?php foreach((array)$this->options['delivery_method'] as $dkey => $dvalue){ 
				if($dvalue['id'] == $arr_search['word']['delivery_method']){
					$dselected = ' selected="selected"';
				}else{
					$dselected = '';
				}
		?>
				html += '<option value="<?php echo $dvalue['id']; ?>"<?php echo $dselected ?>><?php echo $dvalue['name'] ?></option>';
		<?php } ?>
				html += '</select>';
			}else if( column == 'payment_name' ) {
				label = '';
				html = '<select name="search[word][payment_name]" class="searchselect">';
		<?php foreach((array)$payment_name as $pnkey => $pnvalue){ 
				if($pnvalue == $arr_search['word']['payment_name']){
					$pnselected = ' selected="selected"';
				}else{
					$pnselected = '';
				}
		?>
				html += '<option value="<?php echo $pnvalue; ?>"<?php echo $pnselected ?>><?php echo $pnvalue ?></option>';
		<?php } ?>
				html += '</select>';
			}else if( column == 'receipt_status' ) {
				label = '';
				html = '<select name="search[word][receipt_status]" class="searchselect">';
		<?php foreach((array)$receipt_status as $rkey => $rvalue){ 
				if($rvalue == $arr_search['word']['receipt_status']){
					$rselected = ' selected="selected"';
				}else{
					$rselected = '';
				}
		?>
				html += '<option value="<?php echo $rvalue; ?>"<?php echo $rselected ?>><?php echo $rvalue ?></option>';
		<?php } ?>
				html += '</select>';
			}else if( column == 'order_status' ) {
				label = '';
				html = '<select name="search[word][order_status]" class="searchselect">';
		<?php foreach((array)$order_status as $okey => $ovalue){ 
				if($ovalue == $arr_search['word']['order_status']){
					$oselected = ' selected="selected"';
				}else{
					$oselected = '';
				}
		?>
				html += '<option value="<?php echo $ovalue; ?>"<?php echo $oselected ?>><?php echo $ovalue ?></option>';
		<?php } ?>
				html += '</select>';
			}
			
			$("#searchlabel").html( label );
			$("#searchfield").html( html );
		
		}, 
		
		change_collective_field :function (){
		
			var label = '';
			var html = '';
			var column = $("#changeselect").val();
			
			if( column == 'order_reciept' ) {
				label = '';
				html = '<select name="change[word][order_reciept]" class="searchselect">';
		<?php foreach((array)$receipt_status as $orkey => $orvalue){ ?>
				html += '<option value="<?php echo $orkey; ?>"><?php echo $orvalue ?></option>';
		<?php } ?>
				html += '</select>';
			}else if( column == 'order_status' ) {
				label = '';
				html = '<select name="change[word][order_status]" class="searchselect">';
		<?php foreach((array)$order_status as $oskey => $osvalue){ ?>
				html += '<option value="<?php echo $oskey; ?>"><?php echo $osvalue ?></option>';
		<?php } ?>
				html += '</select>';
			}else if( column == 'delete' ) {
				label = '';
				html = '';
			} 
			
			$("#changelabel").html( label );
			$("#changefield").html( html );
		
		}
	};

});

function toggleVisibility(id) {
	var e = document.getElementById(id);
	if(e.style.display == 'block') {
		e.style.display = 'none';
		document.getElementById("searchSwitchStatus").value = 'OFF';
	} else {
		e.style.display = 'block';
		document.getElementById("searchSwitchStatus").value = 'ON';
		document.getElementById("searchVisiLink").style.display = 'none';
	}
};

function deleteconfirm(order_id){
	if(confirm('注文No '+order_id+' を削除します。よろしいですか？')){
		return true;
	}else{
		return false;
	}
}

jQuery(document).ready(function($){
	$("table#mainDataTable tr:even").addClass("rowSelection_even");
	$("table#mainDataTable tr").hover(function() {
		$(this).addClass("rowSelection_hilight");
	},
	function() {
		$(this).removeClass("rowSelection_hilight");
	});
	if(	$("#searchSwitchStatus").val() == 'OFF'){
		$("#searchBox").css("display", "none");
		$("#searchVisiLink").html('操作フィールド表示');
	} else {
		$("#searchBox").css("display", "block");
		$("#searchVisiLink").css("display", "none");
	}
		
	operation.change_search_field();
});
</script>

<div class="wrap">
<div class="usces_admin">
<form action="<?php echo USCES_ADMIN_URL.'?page=usces_orderlist'; ?>" method="post" name="tablesearch">

<h2>Welcart Management 受注リスト<?php //echo __('USC e-Shop Options','usces'); ?></h2>
<p class="version_info">Version <?php echo USCES_VERSION; ?></p>
<div id="aniboxStatus" class="<?php echo $status; ?>">
	<div id="anibox" class="clearfix">
		<img src="<?php echo USCES_PLUGIN_URL; ?>/images/list_message_<?php echo $status; ?>.gif" />
		<div class="mes" id="info_massage"><?php echo $message; ?></div>
	</div>
</div>

<div id="datatable">
<div id="tablenavi"><?php echo $dataTableNavigation ?></div>

<div id="tablesearch">
<div id="searchBox">
		<table id="search_table">
		<tr>
		<td>検索項目</td>
		<td><select name="search[column]" class="searchselect" id="searchselect">
		    <option value="none"> </option>
<?php foreach ((array)$arr_column as $key => $value):
		if($value == $arr_search['column']){
			$selected = ' selected="selected"';
		}else{
			$selected = '';
		}
		if($value == 'total_price') continue;
?>
		    <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $key ?></option>
<?php endforeach; ?>
    	</select></td>
		<td id="searchlabel"></td>
		<td id="searchfield"></td>
		<td><input name="searchIn" type="submit" class="searchbutton" value="検索" />
		<input name="searchOut" type="submit" class="searchbutton" value="解除" />
		<input name="searchSwitchStatus" id="searchSwitchStatus" type="hidden" value="<?php echo $DT->searchSwitchStatus; ?>" />
		</td>
		</tr>
		</table>
		<table id="period_table">
		<tr>
		<td>期間</td>
		<td><select name="search[period]" class="searchselect">
<?php foreach ((array)$DT->arr_period as $key => $value):
		if($key == $arr_search['period']){
			$selected = ' selected="selected"';
		}else{
			$selected = '';
		}
?>
		    <option value="<?php echo $key ?>"<?php echo $selected ?>><?php echo $value ?></option>
<?php endforeach; ?>
		</select></td>
		</tr>
		</table>
		<table id="change_table">
		<tr>
		<td>一括操作</td>
		<td><select name="allchange[column]" class="searchselect" id="changeselect">
		    <option value="none"> </option>
		    <option value="order_reciept">入金状態の変更</option>
		    <option value="order_status">処理状態の変更</option>
		    <option value="delete">一括削除</option>
    	</select></td>
		<td id="changelabel"></td>
		<td id="changefield"></td>
		<td><input name="collective" type="submit" class="searchbutton" id="collective_change" value="開始" />
		</td>
		</tr>
		</table>
		<input name="action" id="oederlistaction" type="hidden" />
</div>
</div>

<table id="mainDataTable" cellspacing="1">
	<tr>
		<th scope="col"><input name="allcheck" type="checkbox" value="" /></th>
<?php foreach ( (array)$arr_header as $value ) : ?>
		<th scope="col"><?php echo $value ?></th>
<?php endforeach; ?>
		<th scope="col">&nbsp;</th>
	</tr>
<?php foreach ( (array)$rows as $array ) : ?>
	<tr>
	<td><input name="listcheck[]" type="checkbox" value="<?php echo $array['ID']; ?>" /></td>
	<?php foreach ( (array)$array as $key => $value ) : ?>
		<?php if( $value == '' || $value == ' ' ) $value = '&nbsp;'; ?>
		<?php if( $key == 'ID' ): ?>
		<td><a href="<?php echo USCES_ADMIN_URL.'?page=usces_orderlist&order_action=edit&order_id=' . $value.'&usces_referer='.$curent_url; ?>"><?php echo $value; ?></a></td>
		<?php elseif( $key == 'total_price' ): ?>
		<td class="price">&yen;<?php echo number_format($value); ?></td>
		<?php elseif( $key == 'receipt_status' && $value == '未入金'): ?>
		<td class="red"><?php echo $value; ?></td>
		<?php elseif( $key == 'receipt_status' && $value == '入金済み'): ?>
		<td class="green"><?php echo $value; ?></td>
		<?php elseif( $key == 'order_status' && $value == '発送済み'): ?>
		<td class="green"><?php echo $value; ?></td>
		<?php elseif( $key == 'delivery_method'): ?>
		<td class="green"><?php echo $this->options['delivery_method'][$value]['name']; ?></td>
		<?php elseif( $key == 'payment_name' && $value == '#none#'): ?>
		<td>&nbsp;</td>
		<?php else: ?>
		<td><?php echo $value; ?></td>
		<?php endif; ?>
<?php endforeach; ?>
	<td><a href="<?php echo USCES_ADMIN_URL.'?page=usces_orderlist&order_action=delete&order_id=' . $array['ID']; ?>" onclick="return deleteconfirm('<?php echo $array['ID']; ?>');"><span style="color:#FF0000; font-size:9px;">削除</span></a></td>
	</tr>
<?php endforeach; ?>
</table>

</div>
<!--<div class="chui">
<h3>受注詳細画面（作成中）について</h3>
<p>各行の受注番号をクリックすると受注詳細画面が表示されます。受注詳細画面では注文商品の追加、修正、削除など受注に関する全ての情報を編集することができま、問い合わせや電話での変更依頼に対応します。</p>
<p>「見積り」ステイタスを利用することで見積りをメール送信できます。見積書印刷でFAX対応も可能です。注文をいただいた場合は「受注」ステイタスに変更することで、見積りの内容がそのまま受注データとなります。</p>
<p>その他のステイタスには銀行振り込みの場合の「入金」ステイタス、発送完了した場合の「完了」、注文の「キャンセル」などがあり、各業務の終了後にステイタスを変更することを習慣付ければ、複数の担当者での業務もスムーズに行うことができます。</p>
</div>
-->
</form>
</div><!--usces_admin-->
</div><!--wrap-->
<script type="text/javascript">
//	rowSelection("mainDataTable");
</script>
