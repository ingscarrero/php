<?php echo $this->tag->javascriptInclude('js/purchases/signup.js'); ?>
<h1><?php echo $title; ?></h1>
<?php echo $this->tag->form(array('signup/register', 'method' => 'post')); ?>
  <div class="form-field">
    <label for="name">Name</label>
    <?php echo $this->tag->textField(array('name', 'size' => 32)); ?>
  </div>
  <div class="form-field">
    <label for="email">E-mail</label>
    <?php echo $this->tag->textField(array('email', 'size' => 32)); ?>
  </div>
  <?php echo $this->tag->submitButton(array('Send')); ?>
<?php echo $this->tag->endForm(); ?>