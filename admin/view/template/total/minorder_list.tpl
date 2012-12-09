<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
    <h1><img src="view/image/total.png" alt="" /><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_insert; ?></span></a></div>
  </div>
  <div class="content">

        <table class="list">
          <thead>
            <tr>
            	<td class="left"><?php echo $text_customer_group;?></td>
            	<td class="left"><?php echo $text_amount;?></td>
            	<td class="left"><?php echo $text_type;?></td>
            	<td class="left"><?php echo $text_status;?></td>
            	<td class="left"><?php echo $text_action;?></td>
            </tr>
          </thead>
        <tbody>
            <?php if ($minorders) { ?>
            <?php foreach ($minorders as $min) { ?>
            <tr>
			  <td class="left">
			  <?php foreach ($customer_groups as $customerGroup) { ?>
					<?php if (in_array($customerGroup['customer_group_id'], $min['customer_groups'])) { ?>
						 <?php echo $customerGroup['name'].'  '; ?>
                   <?php }  ?>
				 <?php } ?>
			 </td>
              <td class="left"><?php echo $min['amount']; ?></td>
              <td class="left"><?php echo $min['type']==1 ? $text_item : $text_price; ?></td>
              <td class="left"><?php echo $min['status']==1 ? $text_enabled : $text_disabled; ?></td>
              <td class="left">
                [ <a href="<?php echo $min['edit']; ?>"><?php echo $text_edit;?></a> ]&nbsp;&nbsp;
                [ <a href="<?php echo $min['delete']; ?>"><?php echo $text_delete;?></a> ]
               </td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
  </div>
</div>
<?php echo $footer; ?>