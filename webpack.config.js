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

    .addStyleEntry('css/product', './assets/scss/product.sass')
    .addStyleEntry('css/file-upload', './assets/scss/file-upload.sass')
    .addStyleEntry('css/index', './assets/scss/index.sass')
    .addStyleEntry('css/exceptions', './assets/scss/exceptions.sass')
    .addStyleEntry('css/import-csv', './assets/scss/import-csv.sass')
    .addStyleEntry('css/app', ['./assets/scss/app.sass'])
    .addStyleEntry('css/menu', ['./assets/scss/menu.sass'])
    .addStyleEntry('css/footer', ['./assets/scss/footer.sass'])
    .addStyleEntry('css/forms', ['./assets/scss/forms.sass'])

    .addEntry('js/preview', './assets/js/preview.js')
    .addEntry('js/multi-preview', './assets/js/multi-preview.js')
    .addEntry('js/import', './assets/js/import.js')
;

module.exports = Encore.getWebpackConfig();
