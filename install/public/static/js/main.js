requirejs.config({baseUrl: 'static/js'});
require(['jquery', 
         'fuelux/all', 
         'install/license', 
         'install/platform',
         'install/environment',
         'install/database',
         'install/account',
         'install/complete'], function(
            $, 
            fuelux, 
            license, 
            platform, 
            environment,
            database,
            account,
            complete
  ) {
    $(function() {
        window.wizard = wizard = $('#installWizard');
        var controllers = Array(license, platform, environment, database, account, complete);
        wizard.on('change', function(e, data) {
          controllers[data.step-1].change(e);
        });
        wizard.on('changed', function(e, data) {
          controllers[$(this).wizard('selectedItem').step-1].show();
        });
        wizard.on('stepclick', function(e) {
            e.preventDefault();
        });
    });
});