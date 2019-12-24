
<?php echo $template['partials']['sidebar']; ?>
    
<div id="content-wrapper" class="span9 box-3d">
    <div id="content">
        <div class="header">
            <h2><?php echo $page_title; ?></h2>
            <p class="subheader"><?php echo $page_subtitle; ?></p>
        </div>
        <div class="content-data">
            <?php echo form_open($this->uri->uri_string(), array('class' => 'form-horizontal')); ?>
            <fieldset>                    
                <div class="control-group">
                    <?php echo form_label( lang('email_address'), 'email', array('class' => 'control-label')); ?>
                    <div class="controls"><?php echo form_input($form_email); ?></div>
                </div>
                                                
                <hr>
                        
                <div class="span4 offset2">
                    <div class="span2" style="text-align: center;">
                        <?php echo form_submit(array('name'  => 'submit', 'value' => lang('save'), 'class' => 'btn',)); ?>
                    </div>
                </div>
            </fieldset>
            <?php echo form_close(); ?>
        </div>
    </div><!-- /#content -->
</div><!-- /#content-wrapper -->
