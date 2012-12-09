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
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
		 <tr>
          <td><?php echo $entry_last_min_total; ?></td>
          <td><b><?php echo $minorder_min_str; ?></b></td>
         </tr>
          <tr>
          <td><?php echo $entry_type; ?></td>
          <td><select name="minorder_type">
              <?php if ($minorder_type) { ?>
              <option value="1" selected="selected"><?php echo $text_item; ?></option>
              <option value="0"><?php echo $text_price; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_item; ?></option>
              <option value="0" selected="selected"><?php echo $text_price; ?></option>
              <?php } ?>
            </select></td>
        </tr>
		 <tr>
          <td><?php echo $entry_min_total; ?></td>
          <td><input type="text" name="minorder_min" value="<?php echo $minorder_min; ?>" size="10" />
           <?php if ($minorder_type==0) { ?>
         	 <?php echo $entry_min_total_dec; ?>
          <?php } ?>
          </td>
         </tr>

		  <tr>
            <td><?php echo $entry_customer_group; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($customer_groups as $customerGroup) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($customerGroup['customer_group_id'], $minorder_customer_group)) { ?>
                  <input type="checkbox" name="minorder_customer_groups[]" value="<?php echo $customerGroup['customer_group_id']; ?>" checked="checked" />
                  <?php echo $customerGroup['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="minorder_customer_groups[]" value="<?php echo $customerGroup['customer_group_id']; ?>" />
                  <?php echo $customerGroup['name']; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div></td>
          </tr>
		 <tr>
            <td><?php echo $entry_category; ?></td>
            <td><div class="scrollbox">
                <?php $class = 'odd'; ?>
                <?php foreach ($categories as $category) { ?>
                <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
                <div class="<?php echo $class; ?>">
                  <?php if (in_array($category['category_id'], $minorder_category)) { ?>
                  <input type="checkbox" name="minorder_categories[]" value="<?php echo $category['category_id']; ?>" checked="checked" />
                  <?php echo $category['name']; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="minorder_categories[]" value="<?php echo $category['category_id']; ?>" />
                  <?php echo $category['name']; ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div></td>
          </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="minorder_status">
              <?php if ($minorder_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>

        <tr>
          <td><?php echo $entry_sort_order; ?></td>
          <td><input type="text" name="minorder_sort_order" value="<?php echo $minorder_sort_order; ?>" size="1" /></td>
        </tr>
      </table>
          <input type="hidden" name="minorder_count" value="<?php echo $minorder_count; ?>" />
    </form>
  </div>
</div>
<?php echo $footer; ?>