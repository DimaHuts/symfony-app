var Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()
    .autoProvideVariables({
        "window.jQuery": "jquery",
        "window.Bloodhound": require.resolve('bloodhound-js'),
        "jQuery.tagsinput": "bootstrap-tagsinput"
    })
    .enableSassLoader()
    .enableVersioning(false)
    .addStyleEntry('css/app', ['./assets/scss/app.sass'])
    .addStyleEntry('css/admin', ['./assets/scss/admin.scss'])
    .addStyleEntry('css/menu', ['./assets/scss/menu.sass'])
    .addStyleEntry('css/footer', ['./assets/scss/footer.sass'])
    .addStyleEntry('css/forms', ['./assets/scss/forms.sass'])
;

module.exports = Encore.getWebpackConfig();
