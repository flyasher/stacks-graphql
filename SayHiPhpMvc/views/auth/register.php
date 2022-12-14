<?php $form=app\core\form\Form::begin('register','post')?>
<div class="form-row">
    <div class="form-group col-md-6">
        <?php echo $form->field($model, 'firstname','text') ?>
    </div>
    <div class="form-group col-md-6">
        <?php echo $form->field($model, 'lastname','text') ?>
    </div>
</div>
<?php echo $form->field($model,'email','text') ?>
<?php echo $form->field($model,'password','text')->passwordField() ?>
<?php echo $form->field($model,'passwordConfirm','text')->passwordField() ?>
<button type="submit" class="btn btn-primary float-right">Register</button>
<?php echo app\core\form\Form::end() ?>
