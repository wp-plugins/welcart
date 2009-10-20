<?php
require_once( USCES_PLUGIN_DIR . "/classes/itemList.class.php" );
global $wpdb;

$tableName = $wpdb->posts;
if( USCES_MYSQL_VERSION >= 5 ){
	$arr_column = array(
				'商品コード' => 'item_code', 
				'商品名' => 'item_name', 
				'SKUコード' => 'sku_key', 
				'売価' => 'price', 
				'在庫数' => 'zaiko_num', 
				'在庫状態' => 'zaiko', 
				'カテゴリー' => 'category', 
				'表示状態' => 'display_status');
} else {
	$arr_column = array(
				'商品コード' => 'item_code', 
				'ページタイトル' => 'post_title', 
				'SKUコード' => 'sku_key', 
				'売価' => 'price', 
				'在庫数' => 'zaiko_num', 
				'在庫状態' => 'zaiko', 
				'カテゴリー' => 'category', 
				'表示状態' => 'display_status');
}

$DT = new dataList($tableName, $arr_column);
$res = $DT->MakeTable();
$arr_search = $DT->GetSearchs();
$arr_status = get_option('usces_management_status');
$arr_header = $DT->GetListheaders();
$dataTableNavigation = $DT->GetDataTableNavigation();
$rows = $DT->rows;
$zaiko_status = get_option('usces_zaiko_status');
$status = $DT->get_action_status();
$message = $DT->get_action_message();
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

	$("#aAdditionalURLs").click(function () {
		$("#AdditionalURLs").toggle();
	});
	
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
			alert("商品が選択されていません。");
			$("#itemlistaction").val('');
			return false;
		}
		var coll = $("#changeselect").val();
		var mes = '';
		if( coll == 'zaiko' ){
			mes = 'チェックされた商品の在庫状況を「' + $("select[name='change\[word\]\[zaiko\]'] option:selected").html() + '」に変更します。'+"\nＳＫＵごとの在庫状態がすべて「" + $("select[name='change\[word\]\[zaiko\]'] option:selected").html() + "」となります。\n\nよろしいですか？";
		}else if( coll == 'display_status' ){
			mes = 'チェックされた商品の表示状況を、すべて「' + $("select[name='change\[word\]\[display_status\]'] option:selected").html() + '」に変更します。'+"\n\nよろしいですか？";
		}else if(coll == 'delete'){
			mes = "チェックされた商品を一括削除します。\n\nよろしいですか？";
		}else{
			$("#itemlistaction").val('');
			return false;
		}
		if( !confirm(mes) ){
			$("#itemlistaction").val('');
			return false;
		}
		$("#itemlistaction").val('collective');
		return true;
	});

	operation = {
		change_search_field :function (){
		
			var label = '';
			var html = '';
			var column = $("#searchselect").val();
			
			if( column == 'item_name' ) {
				label = 'キーワード';
				html = '<input name="search[word][item_name]" type="text" value="<?php echo wp_specialchars($arr_search['word']['item_name']); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'item_code' ) {
				label = 'キーワード';
				html = '<input name="search[word][item_code]" type="text" value="<?php echo wp_specialchars($arr_search['word']['item_code']); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'post_title' ) {
				label = 'キーワード';
				html = '<input name="search[word][post_title]" type="text" value="<?php echo wp_specialchars($arr_search['word']['post_title']); ?>" class="searchword" maxlength="50" />';
			}else if( column == 'zaiko_num' ) {
				label = '';
				html = '';
			}else if( column == 'zaiko' ) {
				label = '';
				html = '<select name="search[word][zaiko]" class="searchselect">';
		<?php foreach($zaiko_status as $zkey => $zvalue){ 
				if($zkey == $arr_search['word']['zaiko']){
					$zselected = ' selected="selected"';
				}else{
					$zselected = '';
				}
		?>
				html += '<option value="<?php echo $zkey; ?>"<?php echo $zselected ?>><?php echo wp_specialchars($zvalue); ?></option>';
		<?php } ?>
				html += '</select>';
			}else if( column == 'category' ) {
				label = '';
				html = '<select name="search[word][category]" class="searchselect">';
		<?php 
			$categories = get_categories(array('child_of' => USCES_ITEM_CAT_PARENT_ID));
			foreach($categories as $ckey => $cvalue){ 
				if($cvalue->name == $arr_search['word']['category']){
					$cselected = ' selected="selected"';
				}else{
					$zselected = '';
				}
		?>
				html += '<option value="<?php echo wp_specialchars($cvalue->name); ?>"<?php echo $cselected ?>><?php echo wp_specialchars($cvalue->name); ?></option>';
		<?php } ?>
				html += '</select>';
			}else if( column == 'display_status' ) {
				label = '';
				html = '<select name="search[word][display_status]" class="searchselect">';
				html += '<option value="公開済み"<?php if("公開済み" == $arr_search['word']['display_status']) echo ' selected="selected"'; ?>>公開済み</option>';
				html += '<option value="非公開"<?php if("非公開" == $arr_search['word']['display_status']) echo ' selected="selected"'; ?>>非公開</option>';
				html += '</select>';
			} 
			
			$("#searchlabel").html( label );
			$("#searchfield").html( html );
		
		}, 
		
		change_collective_field :function (){
		
			var label = '';
			var html = '';
			var column = $("#changeselect").val();
			
			if( column == 'zaiko' ) {
				label = '';
				html = '<select name="change[word][zaiko]" class="searchselect">';
		<?php foreach($zaiko_status as $zkey => $zvalue){ ?>
				html += '<option value="<?php echo $zkey; ?>"><?php echo wp_specialchars($zvalue); ?></option>';
		<?php } ?>
				html += '</select>';
			}else if( column == 'display_status' ) {
				label = '';
				html = '<select name="change[word][display_status]" class="searchselect">';
				html += '<option value="publish">公開済み</option>';
				html += '<option value="private">非公開</option>';
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

function deleteconfirm(item_id){
	if(confirm('商品コード '+item_id+' の商品を削除します。よろしいですか？')){
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
<form action="<?php echo USCES_ADMIN_URL.'?page=usces_itemedit'; ?>" method="post" name="tablesearch">
<h2>Welcart Shop 商品リスト<?php //echo __('USC e-Shop Options','usces'); ?></h2>
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
<?php foreach ($arr_column as $key => $value):
		if($value == $arr_search['column']){
			$selected = ' selected="selected"';
		}else{
			$selected = '';
		}
		if( $value != 'sku_key' && $value != 'price' ) :
?>
	<?php if( $value == 'zaiko_num' ){ ?>
		    <option value="<?php echo $value ?>"<?php echo $selected ?>>在庫数０の商品</option>
	<?php }else if( USCES_MYSQL_VERSION < 5 && $value == 'item_code' ){ continue; ?>
	<?php }else{ ?>
		    <option value="<?php echo $value ?>"<?php echo $selected ?>><?php echo $key ?></option>
	<?php } ?>
<?php endif; endforeach; ?>
    	</select></td>
		<td id="searchlabel"></td>
		<td id="searchfield"></td>
		<td><input name="searchIn" type="submit" class="searchbutton" value="検索" />
		<input name="searchOut" type="submit" class="searchbutton" value="解除" />
		<input name="searchSwitchStatus" id="searchSwitchStatus" type="hidden" value="<?php echo $DT->searchSwitchStatus; ?>" />
		</td>
		</tr>
		</table>
		<table id="change_table">
		<tr>
		<td>一括操作</td>
		<td><select name="allchange[column]" class="searchselect" id="changeselect">
		    <option value="none"> </option>
		    <option value="zaiko">在庫状態の変更</option>
		    <option value="display_status">表示状態の変更</option>
		    <option value="delete">一括削除</option>
    	</select></td>
		<td id="changelabel"></td>
		<td id="changefield"></td>
		<td><input name="collective" type="submit" class="searchbutton" id="collective_change" value="開始" />
		</td>
		</tr>
		</table>
		<input name="action" id="itemlistaction" type="hidden" />
</div>
</div>

<table id="mainDataTable" cellspacing="1">
	<tr>
		<th scope="col"><input name="allcheck" type="checkbox" value="" /></th>
		<th scope="col">&nbsp;</th>
<?php foreach ( (array)$arr_header as $key => $value ) : ?>
	<?php if ( $key == 'item_code' ) : ?>
			<th scope="col"><?php echo $value ?>&nbsp;/&nbsp;
	<?php elseif ( $key == 'item_name' || $key == 'post_title' ) : ?>
			<?php echo $value ?></th>
	<?php else : ?>
			<th scope="col"><?php echo $value ?></th>
	<?php endif; ?>
<?php endforeach; ?>
	</tr>
<?php foreach ( (array)$rows as $array ) :
		$pctid = $this->get_pictids($array['item_code']); 
		$sku_values = unserialize($array['sku_value']);
?>
	<tr>
	<td width="20px"><input name="listcheck[]" type="checkbox" value="<?php echo (int)$array['ID']; ?>" /></td>
	<td width="50px"><a href="<?php echo USCES_ADMIN_URL.'?page=usces_itemedit&action=edit&post='.$array['ID'].'&usces_referer='.$curent_url; ?>" title="<?php echo wp_specialchars($array['item_name']); ?>"><?php echo wp_get_attachment_image( $pctid[0], array(50, 50), true ); ?></a></td>
	<?php foreach ( (array)$array as $key => $value ) : 
			$skus = $this->get_skus( $array['ID'], 'ARRAY_A' );
	?>
		<?php if( $key == 'item_code') : ?>
			<?php if( USCES_MYSQL_VERSION < 5 ){ $usceskey_values = get_post_custom_values('itemCode', $array['ID']); $value = $usceskey_values[0]; $array['item_code'] = $usceskey_values[0]; } ?>
			<td class="item">
			<?php if( $value != '' ) : ?> 
				<strong><?php echo wp_specialchars($value); ?></strong>
			<?php else : ?> 
				&nbsp;
			<?php endif; ?>
			<br />
		<?php elseif( $key == 'item_name' ) : ?>
			<?php if( $value != '' ) : ?> 
				<strong><?php echo wp_specialchars($value); ?></strong>
			<?php else : ?> 
				&nbsp;
			<?php endif; ?>
			<ul class="item_list_navi">
				<li><a href="<?php echo USCES_ADMIN_URL.'?page=usces_itemedit&action=edit&post='.$array['ID'].'&usces_referer='.$curent_url; ?>">編集</a></li>
				<li>&nbsp;|&nbsp;</li>
				<li><a href="<?php echo wp_nonce_url("post.php?action=delete&amp;post=".$array['ID'], 'delete-post_' . $array['ID']); ?>" onclick="return deleteconfirm('<?php echo wp_specialchars($array['item_code']); ?>');">削除</a></li>
			</ul>
			</td>
			
		<?php elseif( $key == 'post_title' ) : ?>
			<?php if( $value != '' ) : ?> 
				<strong><?php echo wp_specialchars($value); ?></strong>
			<?php else : ?> 
				&nbsp;
			<?php endif; ?>
			<ul class="item_list_navi">
				<li><a href="<?php echo USCES_ADMIN_URL.'?page=usces_itemedit&action=edit&post='.$array['ID'].'&usces_referer='.$curent_url; ?>">編集</a></li>
				<li>&nbsp;|&nbsp;</li>
				<li><a href="<?php echo wp_nonce_url("post.php?action=delete&amp;post=".$array['ID'], 'delete-post_' . $array['ID']); ?>" onclick="return deleteconfirm('<?php echo wp_specialchars($array['item_code']); ?>');">削除</a></li>
			</ul>
			</td>
			
		<?php elseif( $key == 'sku_key' ): ?>
		
			<td class="sku">
			<?php $i=0; foreach((array)$skus as $key => $sv) { $bgc = ($i%2 == 1) ? ' bgc1' : ' bgc2'; $i++; ?>
				<div class="skuline<?php echo $bgc; ?>"><?php echo $key; ?></div>
			<?php } if(count($skus) === 0) echo "&nbsp;"; ?>
			</td>

		<?php elseif( $key == 'sku_value' ): ?>
			<td class="price">
			<?php $i=0; foreach((array)$skus as $key => $sv) { $bgc = ($i%2 == 1) ? ' bgc1' : ' bgc2'; $i++; ?>
				<div class="priceline<?php echo $bgc; ?>"><?php echo number_format($sv['price']); ?></div>
			<?php } if(count($skus) === 0) echo "&nbsp;"; ?>
			</td>
			<td class="zaikonum">
			<?php $i=0; foreach((array)$skus as $key => $sv) { $bgc = ($i%2 == 1) ? ' bgc1' : ' bgc2'; $i++; ?>
				<div class="priceline<?php echo $bgc; ?>"><?php echo $sv['zaikonum']; ?></div>
			<?php } if(count($skus) === 0) echo "&nbsp;"; ?>
			</td>
			<td class="zaiko">
			<?php $i=0; foreach((array)$skus as $key => $sv) { $zaikokey = $sv['zaiko']; $bgc = ($i%2 == 1) ? ' bgc1' : ' bgc2'; $i++; ?>
				<div class="zaikoline<?php echo $bgc; ?>"><?php echo $zaiko_status[$zaikokey]; ?></div>
			<?php } if(count($skus) === 0) echo "&nbsp;"; ?>
			</td>
		<?php elseif( $key == 'category' ) : ?>
			<td class="listcat">
			<?php
				$cat_ids = wp_get_post_categories($array['ID']);
				if ( !empty( $cat_ids ) ) {
					$out = array();
					foreach ( $cat_ids as $id )
						$out[] = get_cat_name($id);
						echo join( ', ', $out );
				} else {
					_e('Uncategorized');
				}
			?>
			</td>
		<?php elseif( $key == 'display_status' ): ?>
			<td><?php echo $value; ?></td>
		<?php endif; ?>
<?php endforeach; ?>
	</tr>
<?php endforeach; ?>
</table>

</div>
</form>
</div><!--usces_admin-->
</div><!--wrap-->
