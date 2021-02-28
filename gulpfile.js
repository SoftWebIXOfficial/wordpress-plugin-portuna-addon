'use strict';

const gulp       = require( 'gulp' ),
    {
        series,
        watch
    }            = require( 'gulp' ),
    gulpif       = require( 'gulp-if' ),
    util         = require( 'gulp-util' ),
    strip        = require( 'gulp-strip-comments' ),
    babel        = require( 'gulp-babel' ),
    minify       = require( 'gulp-minify' ),
    browserSync  = require( 'browser-sync' ).create(),
    reload       = browserSync.reload,
    ignore       = require( 'gulp-ignore' ), // Helps with ignoring files and directories in our run tasks
    runSequence  = require( 'gulp-run-sequence' ),
    newer        = require( 'gulp-newer' ),
    imagemin     = require( 'gulp-imagemin' ),
    rimraf       = require( 'gulp-rimraf' ), // Helps with removing files and directories in our run tasks
    sourcemaps   = require( 'gulp-sourcemaps' ),
    concat       = require( 'gulp-concat' ),
    uglify       = require( 'gulp-uglify-es' ).default,
    cache        = require( 'gulp-cache' ),
    sass         = require( 'gulp-sass' ),
    autoprefixer = require( 'gulp-autoprefixer' ),
    notify       = require( 'gulp-notify' ),
    rename       = require( 'gulp-rename' ),
    cleanCSS     = require( 'gulp-clean-css' ),
    wpPot        = require( 'gulp-wp-pot' ),
    plumber      = require( 'gulp-plumber' );

const path = {
    output: {
        assetsJS:     './assets/js/',
        assetsStyle:  './assets/css/',
        modulesJS:    './includes/modules/',
        modulesStyle: './includes/modules/',
        widgetsJS:    './includes/widgets/',
        widgetsStyle: './includes/widgets/',
        vendorsJS:    './assets/vendors/',
        vendorsStyle: './assets/vendors/',
        img:          './assets/img/'
    },
    src: {
        html:         '**/*.php',
        assetsJS:     [ './assets/js/**/*.js', '!./assets/js/**/*.min.js' ],
        assetsStyle:  './assets/css/**/*.scss',
        modulesJS:    [ './includes/modules/**/*.js', '!./includes/modules/**/*.min.js' ],
        modulesStyle: './includes/modules/**/*.scss',
        widgetsJS:    [ './includes/widgets/**/*.js', '!./includes/widgets/**/*.min.js' ],
        widgetsStyle: './includes/widgets/**/*.scss',
        vendorsJS:    [ './assets/vendors/*.js', '!./assets/vendors/*.min.js' ],
        vendorsStyle: './assets/vendors/*.scss',
        img:          './assets/img/**/*.{png,jpg,jpeg,gif}'
    },
    helpers: {
        clean:        [ '**/.sass-cache', '**/.DS_Store' ]
    }
};

// gulp.task( 'browser-sync', function() {
//     const files = [
//         '**/*.php',
//         '**/*.{png,jpg,gif}'
//     ];
//
//     browserSync.init(files, {
//         // Read here http://www.browsersync.io/docs/options/
//         proxy: url,
//
//         // Inject CSS changes
//         injectChanges: true
//     });
// });

// Minify plugin/admin CSS.
const compressedAssetsCSS = () => {
    return gulp.src( path.src.assetsStyle )
        .pipe( gulpif( ! util.env.production, sourcemaps.init() ) )
        .pipe( plumber( err => {
            return notify().write(err);
        } ) )
        .pipe( sass( {
            outputStyle: 'compressed',
            includePaths: [ 'node_modules' ],
        } ).on( 'error', err => {
            this.emit( 'end' );
            return notify().write( err );
        } ) )
        .pipe( autoprefixer( {
            browsers: [ 'last 2 version', '> 1%', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4' ],
            cascade: true,
        } ) )
        .pipe( cleanCSS( { compatibility: 'ie8' } ) )
        .pipe( rename( { suffix: '.min' } ) )
        .pipe( gulpif( ! util.env.production, sourcemaps.write('.') ) )
        .pipe( gulp.dest( path.output.assetsStyle ) );
}

// Vendors compiled.
const compressedVendorsJS = () => {
    return gulp.src( path.src.vendorsJS )
        .pipe( gulpif( ! util.env.production, sourcemaps.init() ) )
        .pipe( babel( {
            presets: [ '@babel/env' ]
        } ) )
        .pipe( gulpif( util.env.production,
            uglify({
                compress: {
                    drop_debugger: false
                }
            } )
        ) )
        .pipe( rename( { suffix: '.min' } ) )
        .pipe( gulpif( ! util.env.production, sourcemaps.write('.') ) )
        .pipe( strip() )
        .pipe( gulp.dest( path.output.vendorsJS ) );
}
const compressedModulesJS = () => {
    return gulp.src( path.src.modulesJS )
        .pipe( gulpif( ! util.env.production, sourcemaps.init() ) )
        .pipe( babel( {
            presets: [ '@babel/env' ]
        } ) )
        .pipe( gulpif( util.env.production,
            uglify({
                compress: {
                    drop_debugger: false
                }
            } )
        ) )
        .pipe( rename( { suffix: '.min' } ) )
        .pipe( gulpif( ! util.env.production, sourcemaps.write('.') ) )
        .pipe( strip() )
        .pipe( gulp.dest( path.output.modulesJS ) );
}

// Minify plugin/admin JS.
const compressedAssetsJS = () => {
    return gulp.src( path.src.assetsJS )
        .pipe( gulpif( ! util.env.production, sourcemaps.init() ) )
        .pipe( babel( {
            presets: [ '@babel/env' ]
        } ) )
        .pipe( gulpif( util.env.production,
            uglify({
                compress: {
                    drop_debugger: false
                }
            } )
        ) )
        .pipe( rename( { suffix: '.min' } ) )
        .pipe( gulpif( ! util.env.production, sourcemaps.write('.') ) )
        .pipe( strip() )
        .pipe( gulp.dest( path.output.assetsJS ) );
}

// Vendors compiled.
const compressedVendorsCSS = () => {
    return gulp.src( path.src.vendorsStyle )
        .pipe( gulpif( ! util.env.production, sourcemaps.init() ) )
        .pipe( plumber( err => {
            return notify().write(err);
        } ) )
        .pipe( sass( {
            outputStyle: 'compressed',
            includePaths: [ 'node_modules' ],
        } ).on( 'error', err => {
            this.emit( 'end' );
            return notify().write( err );
        } ) )
        .pipe( autoprefixer( {
            browsers: [ 'last 2 version', '> 1%', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4' ],
            cascade: true,
        } ) )
        .pipe( cleanCSS( { compatibility: 'ie8' } ) )
        .pipe( rename( { suffix: '.min' } ) )
        .pipe( gulpif( ! util.env.production, sourcemaps.write('.') ) )
        .pipe( gulp.dest( path.output.vendorsStyle ) );
}

const compressedModulesCSS = () => {
    return gulp.src( path.src.modulesStyle )
        .pipe( gulpif( ! util.env.production, sourcemaps.init() ) )
        .pipe( plumber( err => {
            return notify().write(err);
        } ) )
        .pipe( sass( {
            outputStyle: 'compressed',
            includePaths: [ 'node_modules' ],
        } ).on( 'error', err => {
            this.emit( 'end' );
            return notify().write( err );
        } ) )
        .pipe( autoprefixer( {
            browsers: [ 'last 2 version', '> 1%', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4' ],
            cascade: true,
        } ) )
        .pipe( cleanCSS( { compatibility: 'ie8' } ) )
        .pipe( rename( { suffix: '.min' } ) )
        .pipe( gulpif( ! util.env.production, sourcemaps.write('.') ) )
        .pipe( gulp.dest( path.output.modulesStyle ) );
}

// Minify widgets CSS.
const compressedWidgetsCSS = () => {
    return gulp.src( path.src.widgetsStyle )
        .pipe( gulpif( ! util.env.production, sourcemaps.init() ) )
        .pipe( plumber( err => {
            return notify().write(err);
        } ) )
        .pipe( sass( {
            outputStyle: 'compressed',
            includePaths: [ 'node_modules' ],
        } ).on( 'error', err => {
            this.emit( 'end' );
            return notify().write( err );
        } ) )
        .pipe( autoprefixer( {
            browsers: [ 'last 2 version', '> 1%', 'safari 5', 'ie 8', 'ie 9', 'opera 12.1', 'ios 6', 'android 4' ],
            cascade: true,
        } ) )
        .pipe( cleanCSS( { compatibility: 'ie8' } ) )
        .pipe( rename( { suffix: '.min' } ) )
        .pipe( gulpif( ! util.env.production, sourcemaps.write('.') ) )
        .pipe( gulp.dest( path.output.widgetsStyle ) );
}

// Minify widgets JS.
const compressedWidgetsJS = () => {
    return gulp.src( path.src.widgetsJS )
        .pipe( gulpif( ! util.env.production, sourcemaps.init() ) )
        .pipe( babel( {
            presets: [ '@babel/env' ]
        } ) )
        .pipe( gulpif( util.env.production,
            uglify({
                compress: {
                    drop_debugger: false
                }
            } )
        ) )
        .pipe( rename( { suffix: '.min' } ) )
        .pipe( gulpif( ! util.env.production, sourcemaps.write('.') ) )
        .pipe( strip() )
        .pipe( gulp.dest( path.output.widgetsJS ) );
}

const buildSVG = () => {

}

const buildImages = () => {
    // Add the newer pipe to pass through newer images only.
    return gulp.src( path.src.img )
        .pipe( newer(path.output.img ) )
        .pipe( rimraf( { force: true } ) )
        .pipe( imagemin( { optimizationLevel: 7, progressive: true, interlaced: true } ) )
        .pipe( gulp.dest( path.output.img ) )
        .pipe( notify( { message: 'Images task complete', onLast: true } ) );
}

const cleanUp = () => {
    return gulp.src( path.helpers.clean, { read: false } ) // much faster './bower_components',
        .pipe( ignore( 'node_modules/**' ) ) // Example of a directory to ignore.
        .pipe( rimraf( { force: true } ) );
    // .pipe(notify({ message: 'Clean task complete', onLast: true }));
}

const cleanUpFinal = () => {
    return gulp.src( path.helpers.clean, { read: false } ) // much faster './bower_components',
        .pipe( ignore( 'node_modules/**' ) ) // Example of a directory to ignore.
        .pipe( rimraf( { force: true } ) );
    // .pipe(notify({ message: 'Clean task complete', onLast: true }));
}

/**
 * Generates pot files for WordPress plugins and themes.
 */
const generatePotFile = () => {
    return gulp.src( path.src.html )
        .pipe( wpPot( {
            domain: 'portuna-addon'
        } ) )
        .pipe( gulp.dest( './languages/portuna-addon.pot' ) );
}

/**
 * Clean gulp cache.
 */
gulp.task( 'cleanCache', () =>
    cache.clearAll()
);

// Build Task.
gulp.task( 'build', series(
    compressedAssetsJS,
    compressedAssetsCSS,
    compressedWidgetsJS,
    compressedWidgetsCSS,
    buildImages,
    cleanUp,
    cleanUpFinal,
    generatePotFile,
    [ 'cleanCache' ]
) );

// Developing Task.
gulp.task( 'dev', () => {
    watch( path.src.assetsJS, series( compressedAssetsJS ) );
    watch( path.src.assetsStyle, series( compressedAssetsCSS ) );
    watch( path.src.widgetsJS, series( compressedWidgetsJS ) );
    watch( path.src.widgetsStyle, series( compressedWidgetsCSS ) );
    watch( path.src.vendorsJS, series( compressedVendorsJS ) );
    watch( path.src.vendorsStyle, series( compressedVendorsCSS ) );
    watch( path.src.modulesJS, series( compressedModulesJS ) );
    watch( path.src.modulesStyle, series( compressedModulesCSS ) );
    watch( path.src.img, series( buildImages ) );
} );