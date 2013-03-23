<?php if ( ! defined('IN_DILICMS')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="zh-cn" class="fuelux">
  <head>
    <meta charset="utf-8">
    <title>DiliCMS 安装程序</title>
    <base href="<?php echo base_url(); ?>/" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="DiliCMS 安装程序">
    <meta name="author" content="chekun">
    <link href="static/js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="static/js/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link href="static/js/fuelux/css/fuelux.min.css" rel="stylesheet">
    <link href="static/js/fuelux/css/fuelux-responsive.min.css" rel="stylesheet">
    <link href="static/css/install.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="static/js/html5shiv.js"></script>
    <![endif]-->
  </head>
  <body>
    <div class="container">
        <div class="row">
              <div class="span12 columns">
                <div class="well">
                <div class="wizard" id="installWizard">
                  <ul class="steps">
                    <li data-target="#step-license" class="active">
                      <span class="badge badge-info">1</span>关于DiliCMS<span class="chevron"></span>
                    </li>
                    <li data-target="#step-platform" class="">
                      <span class="badge">2</span>平台选择<span class="chevron"></span>
                    </li>
                    <li data-target="#step-environment" class="">
                      <span class="badge">3</span>环境检测<span class="chevron"></span>
                    </li>
                    <li data-target="#step-database" class="">
                      <span class="badge">4</span>数据库<span class="chevron"></span>
                    </li>
                    <li data-target="#step-admin" class="">
                      <span class="badge">5</span>初始账号<span class="chevron"></span>
                    </li>
                    <li data-target="#step-complete" class="">
                      <span class="badge">6</span>完成<span class="chevron"></span>
                    </li>
                  </ul>
                </div>
                <div class="step-content">
                  <div class="step-pane active" id="step-license">
                    
                  </div>
                  <div class="step-pane" id="step-platform">This is step 2</div>
                  <div class="step-pane" id="step-environment">This is step 3</div>
                  <div class="step-pane" id="step-database">This is step 4</div>
                  <div class="step-pane" id="step-admin">This is step 5</div>
                  <div class="step-pane" id="step-complete">This is step 5</div>
                </div>
              </div>
          </div>
        </div>
        <footer>
          <p align="center">
            <a target="_blank" href="http://www.dilicms.com/">DiliCMS</a> <code><?php echo DILICMS_VERSION; ?></code> Installer <br />
            ©<?php echo date('Y'); ?> <a target="_blank" href="http://www.dilicms.com/">DiliCMS</a>
          </p>
        </footer>
    </div>
    <script src="static/js/require.js"></script>
    <script>
        requirejs.config({baseUrl: 'static/js'});
        require(['jquery', 
                 'fuelux/all', 
                 'install/license', 
                 'install/platform',
                 'install/environment'], function($, fuelux, license, platform) {
            $(function() {
                window.wizard = wizard = $('#installWizard');
                wizard.on('change', function(e, data) {
                    switch (data.step)
                    {
                      case 1:
                              license.change(e);
                              break;
                      case 2: 
                              platform.change(e);
                              break;
                    }
                });
                wizard.on('changed', function(e, data) {
                    switch ($(this).wizard('selectedItem').step)
                    {
                      case 1:
                              license.show();
                              break;
                      case 2:
                              platform.show();
                              break;
                    }
                });
                wizard.on('stepclick', function(e) {
                    e.preventDefault();
                });
            });
        });
    </script>
  </body>
</html>
