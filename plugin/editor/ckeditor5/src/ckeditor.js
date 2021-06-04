/**
 * @license Copyright (c) 2014-2021, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
 */
import ClassicEditor from '@ckeditor/ckeditor5-editor-classic/src/classiceditor.js';
import Bold from '@ckeditor/ckeditor5-basic-styles/src/bold.js';
import Essentials from '@ckeditor/ckeditor5-essentials/src/essentials.js';
import FontBackgroundColor from '@ckeditor/ckeditor5-font/src/fontbackgroundcolor.js';
import FontColor from '@ckeditor/ckeditor5-font/src/fontcolor.js';
import Image from '@ckeditor/ckeditor5-image/src/image.js';
import ImageUpload from '@ckeditor/ckeditor5-image/src/imageupload.js';
import Italic from '@ckeditor/ckeditor5-basic-styles/src/italic.js';
import Link from '@ckeditor/ckeditor5-link/src/link.js';
import List from '@ckeditor/ckeditor5-list/src/list.js';
import ListStyle from '@ckeditor/ckeditor5-list/src/liststyle.js';
import MediaEmbed from '@ckeditor/ckeditor5-media-embed/src/mediaembed.js';
import Paragraph from '@ckeditor/ckeditor5-paragraph/src/paragraph.js';
import Strikethrough from '@ckeditor/ckeditor5-basic-styles/src/strikethrough.js';
import Underline from '@ckeditor/ckeditor5-basic-styles/src/underline.js';

// 사용자화
import Font from '@ckeditor/ckeditor5-font/src/font';
import FontFamily from '@ckeditor/ckeditor5-font/src/fontfamily';
import Alignment from '@ckeditor/ckeditor5-alignment/src/alignment';
import TodoList from '@ckeditor/ckeditor5-list/src/todolist';
//사용자화 끝

class Editor extends ClassicEditor {}

// Plugins to include in the build.
Editor.builtinPlugins = [
	Bold,
	Essentials,
	FontBackgroundColor,
	FontColor,
	Image,
	ImageUpload,
	Italic,
	Link,
	List,
	ListStyle,
	MediaEmbed,
	Paragraph,
	Strikethrough,
	Underline,
  Fontfamily, //사용자화
  Font,
  Alignment,
  TodoList
];

export default Editor;

// 사용자화
ClassicEditor
    .create( document.querySelector( '#editor' ), {
        plugins: [ Font, ... ],
        toolbar: [ 'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor', ... ]
    } )
    .then( ... )
    .catch( ... );

ClassicEditor
    .create( document.querySelector( '#editor' ), {
        plugins: [ FontFamily, ... ],
        toolbar: [ 'fontFamily', ... ]
    } )
    .then( ... )
    .catch( ... );
editor.execute( 'fontFamily', { value: 'Noto Sans KR' } );
fontFamily.options = [
    'default',
    'Arial, Helvetica, sans-serif',
    'Courier New, Courier, monospace',
    'Georgia, serif',
    'Lucida Sans Unicode, Lucida Grande, sans-serif',
    'Tahoma, Geneva, sans-serif',
    'Times New Roman, Times, serif',
    'Trebuchet MS, Helvetica, sans-serif',
    'Verdana, Geneva, sans-serif'
    'Noto Sans KR, sans-serif',
    'HSBombaram3_Regular, serif',
    'HeirofLightRegular, HeirofLightBold, serif',
    'iropke batang, serif',

]
