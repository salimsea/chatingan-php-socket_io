<!DOCTYPE html>
<html lang="en">
    <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Chatingan</title>
    <link href="<?php echo base_url('assets/css/bootstrap.min.css');?>" rel="stylesheet">
    </head>
    <style>
    body { padding-top: 70px; }
    
    #load { height: 100%; width: 100%; }
    #load {
        position    : fixed;
        z-index     : 99999; /* or higher if necessary */
        top         : 0;
        left        : 0;
        overflow    : hidden;
        text-indent : 100%;
        font-size   : 0;
        opacity     : 0.6;
        background  : #E0E0E0  url(<?php echo base_url('assets/images/load.gif');?>) center no-repeat;
    }
    
    .RbtnMargin { margin-left: 5px; }
    
    
    </style>
<body>
<nav class="navbar navbar-default navbar-fixed-top " role="navigation">
  <div class="container">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
      <span class="sr-only">Toggle navigation</span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="<?php echo base_url();?>">Simple Realtime Message</a>
  </div>
  </div>
</nav>
    
<div class="container">
<?php echo $this->session->flashdata('message'); ?>
  <div class="row">
    <div id="notif"></div>
      <div class="col-md-6 col-md-offset-3">
        <div class="well well-sm">
          <form method="POST" class="form-horizontal" autocomplete="off" action="<?php echo base_url(); ?>auth/login">
          <fieldset>
            <legend class="text-center">Login</legend>
            <div class="form-group">
              <label class="col-md-3 control-label" for="email">Email</label>
              <div class="col-md-9">
                <input id="email" name="email" type="email" placeholder="Email Anda" class="form-control" autofocus>
              </div>
            </div>
            <div class="form-group">
              <label class="col-md-3 control-label" for="password">Password</label>
              <div class="col-md-9">
                <input id="password" name="password" type="text" placeholder="Password Anda" class="form-control">
              </div>
            </div>
            <div class="form-group">
              <div class="col-md-12 text-right">
                <button type="submit" id="submit" class="btn btn-primary">Login</button>
              </div>
            </div>
          </fieldset>
          </form>
        </div>
      </div>
  </div>
</div>

<hr>
<footer class="text-center">Simple Realtime Message &copy 2015</footer>
<hr>

<script src="<?php echo base_url('assets/js/jquery-1.11.2.min.js');?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.min.js');?>"></script>
<script src="<?php echo base_url('node_modules/socket.io/node_modules/socket.io-client/socket.io.js');?>"></script>

  </body>
</html>