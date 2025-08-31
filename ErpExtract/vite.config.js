import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import html from '@rollup/plugin-html';
import { glob } from 'glob';

/**
 * Get Files from a directory
 * @param {string} query
 * @returns array
 */
function GetFilesArray(query) {
  return glob.sync(query);
}

/** JS Files */
// Page JS Files
const pageJsFiles = GetFilesArray('resources/assets/js/*.js');
// Vendor JS Files
const vendorJsFiles = GetFilesArray('resources/assets/vendor/js/*.js');
// Libs JS Files
const LibsJsFiles = GetFilesArray('resources/assets/vendor/libs/**/*.js');

/** SCSS & CSS Files */
// Core, Themes & Pages Scss Files
const CoreScssFiles = GetFilesArray('resources/assets/vendor/scss/**/!(_)*.scss');
// Libs Scss & CSS
const LibsScssFiles = GetFilesArray('resources/assets/vendor/libs/**/!(_)*.scss');
const LibsCssFiles = GetFilesArray('resources/assets/vendor/libs/**/*.css');
// Fonts Scss
const FontsScssFiles = GetFilesArray('resources/assets/vendor/fonts/*.scss');

export default defineConfig({
  plugins: [
    laravel({
      input: [
        // Cambié esto a SCSS
        'resources/css/app.scss',
        'resources/assets/css/demo.css',
        'resources/js/app.js',
        ...pageJsFiles,
        ...vendorJsFiles,
        ...LibsJsFiles,
        ...CoreScssFiles,
        ...LibsScssFiles,
        ...LibsCssFiles,
        ...FontsScssFiles
      ],
      refresh: true
    }),
    html()
  ],
  assetsInclude: ['**/*.woff', '**/*.woff2', '**/*.ttf', '**/*.eot', '**/*.svg']
});
