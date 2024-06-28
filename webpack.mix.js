let mix = require('laravel-mix')

require('./nova.mix')

mix
    .setPublicPath('dist')
    .js('resources/js/heartbeat.js', 'js')
    .sass('resources/scss/heartbeat.scss', 'css')
    .webpackConfig({
        externals: {
            vue: 'Vue',
        },
        output: {
            uniqueName: 'mateusz-peczkowski/nova-heartbeat-resource-field',
        },
    })
    .vue({version: 3})
    .nova('mateusz-peczkowski/nova-heartbeat-resource-field');