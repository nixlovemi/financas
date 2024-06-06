<!DOCTYPE html>
<html lang="en">
    <head>
        <script>
        var HOME_URL = '<?php echo base_url(); ?>';
        </script>

        <title>Controle - Login</title>
        <meta charset="UTF-8" />
        <link rel="icon" type="image/x-icon" href="<?php echo base_url(); ?>img/favicon.ico" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="<?php echo base_url(); ?>css/matrix-login.css" />
        <link href="<?php echo base_url(); ?>font-awesome/css/font-awesome.css" rel="stylesheet" />
        <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,700,800' rel='stylesheet' type='text/css'>
    </head>
    <body>
        <div id="loginbox">
            <?php
            $attributes = array("class" => "form-vertical", "id" => "loginform");
            echo form_open('Login/postLogin', $attributes);
            ?>
                <div class="control-group normal_text">
                    <h3><img src="<?php echo base_url(); ?>img/logo.png" alt="Logo" /></h3>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <?php
                            $data = array(
                              'name'        => 'user',
                              'id'          => 'user',
                              'value'       => '',
                              'maxlength'   => '50',
                              'placeholder' => 'Usuário',
                            );

                            echo '<span class="add-on bg_lg"><i class="icon-user"> </i></span>' . form_input($data);
                            ?>
                        </div>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="main_input_box">
                            <?php
                            $data2 = array(
                              'name'        => 'password',
                              'id'          => 'password',
                              'value'       => '',
                              'maxlength'   => '50',
                              'placeholder' => 'Senha',
                            );

                            echo '<span class="add-on bg_ly"><i class="icon-lock"></i></span>' . form_password($data2);
                            ?>
                        </div>
                    </div>
                </div>

                <?php
                if(isset($warningMsg) && $warningMsg != ""){
                  echo $warningMsg;
                }
                ?>

                <div class="form-actions">
                    <span class="pull-right">
                      <input type="submit" class="btn btn-success" value="Entrar" />
                    </span>
                </div>
            <?php
            echo form_close();
            ?>
        </div>

        <script src="<?php echo base_url(); ?>js/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>js/matrix.login.js"></script>
        <script src="<?php echo base_url(); ?>js/bootstrap.min.js"></script>
    </body>
</html>
