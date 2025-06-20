const Encore = require('@symfony/webpack-encore');

// const { SymfonyUxPlugin } = require('@symfony/webpack-encore/lib/plugins/symfony-ux');


// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    // .setPublicPath('/build')
    .setPublicPath('/susar_eu_v2/public/build')
    // only needed for CDN's or subdirectory deploy
    //.setManifestKeyPrefix('build/')

    /*
     * ENTRY CONFIG
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
     */
    .addEntry('app', './assets/app.js')
    
    .addEntry('listeEvalSusar', './assets/js/listeEvalSusar.js')
    .addEntry('intervenanSubstanceDetail', './assets/js/intervenantSubstanceDetail.js')
    .addEntry('SusarEuDetail', './assets/js/SusarEuDetail.js')
    .addEntry('SusarEuDetailAutreFU', './assets/js/SusarEuDetailAutreFU.js')
    .addEntry('popUpMultiEval', './assets/js/popUpMultiEval.js')
    .addEntry('importExcelCtll', './assets/js/importExcelCtll.js')
    .addEntry('susarEuListeSpinner', './assets/js/susarEuListeSpinner.js')

    // .addEntry('popUpAWA', './assets/js/popUpAWA.js')

    .addStyleEntry('type_eu_css', './assets/styles/type_eu.scss')
    .addStyleEntry('type_eu_detail_css', './assets/styles/type_eu_detail.scss')
    .addStyleEntry('eval_susar_css', './assets/styles/eval_susar.scss')
    .addStyleEntry('int_sub_detail', './assets/styles/int_sub_detail.scss')
    .addStyleEntry('int_sub_liste', './assets/styles/int_sub_liste.scss')
    .addStyleEntry('import_excel_css', './assets/styles/import_excel.scss')
    .addStyleEntry('rech_susar_css', './assets/styles/rech_susar.scss')
    
    // .addStyleEntry('intervenant_substance_css', './assets/styles/intervenant_substance.scss')

    // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
    .enableStimulusBridge('./assets/controllers.json')
    // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
    .splitEntryChunks()

    // will require an extra script tag for runtime.js
    // but, you probably want this, unless you're building a single-page app
    .enableSingleRuntimeChunk()

    /*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    // enables hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // configure Babel
    // .configureBabel((config) => {
    //     config.plugins.push('@babel/a-babel-plugin');
    // })

    // enables and configure @babel/preset-env polyfills
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    })

    // enables Sass/SCSS support
    .enableSassLoader()

    // uncomment if you use TypeScript
    //.enableTypeScriptLoader()

    // uncomment if you use React
    //.enableReactPreset()

    // uncomment to get integrity="..." attributes on your script & link tags
    // requires WebpackEncoreBundle 1.4 or higher
    //.enableIntegrityHashes(Encore.isProduction())

    // uncomment if you're having problems with a jQuery plugin
    //.autoProvidejQuery()
    // .enableStimulusPlugin()
    // .addPlugin(new SymfonyUxPlugin())
;

module.exports = Encore.getWebpackConfig();
