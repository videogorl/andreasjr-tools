const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
const path = require( 'path' );
const { sync: glob } = require( 'fast-glob' );

// Change path for blocks
const defaultBlockEntries = defaultConfig.entry();
// let newBlockEntries = {};
// Object.keys(defaultBlockEntries).forEach((e) => {
//     newBlockEntries['blocks/' + e] = defaultBlockEntries[e];
// });

// Editor scripts
let editorScripts = {};
const editorScriptsSrcDir = './editor/src/';
const editorScriptsBuildDir = '../../editor/build/';
// glob( './editor/src/**/index.js' ).forEach( e => {

//     // Get directory name
//     let dirName = e.substring( editorScriptsSrcDir.length );
//     let dirLength = dirName.length - "/index.js".length;
//     dirName = dirName.substring(0, dirLength)

//     // Add directory to entry points
//     if (!dirName) return; 
//     editorScripts[editorScriptsBuildDir + dirName + '/index'] = path.resolve(e);

// });


// Exports
module.exports = {
    ...defaultConfig,
    entry: {
        '../../editor/build/index':path.resolve('./editor/src/index.js'),
        ...defaultBlockEntries,
    }
};