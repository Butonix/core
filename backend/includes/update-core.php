<?php
/**
 * WordPress core upgrade functionality.
 *
 * @package WordPress
 * @subpackage Administration
 * @since 2.7.0
 */

/**
 * Stores files to be deleted.
 *
 * @since 2.7.0
 * @global array $_old_files
 * @var array
 * @name $_old_files
 */
global $_old_files;

$_old_files = array(
	// 2.0
	ADMIN_DIR . '/import-b2.php',
	ADMIN_DIR . '/import-blogger.php',
	ADMIN_DIR . '/import-greymatter.php',
	ADMIN_DIR . '/import-livejournal.php',
	ADMIN_DIR . '/import-mt.php',
	ADMIN_DIR . '/import-rss.php',
	ADMIN_DIR . '/import-textpattern.php',
	ADMIN_DIR . '/quicktags.js',
	'wp-images/fade-butt.png',
	'wp-images/get-firefox.png',
	'wp-images/header-shadow.png',
	'wp-images/smilies',
	'wp-images/wp-small.png',
	'wp-images/wpminilogo.png',
	'wp.php',
	// 2.0.8
	WPINC . '/js/tinymce/plugins/inlinepopups/readme.txt',
	// 2.1
	ADMIN_DIR . '/edit-form-ajax-cat.php',
	ADMIN_DIR . '/execute-pings.php',
	ADMIN_DIR . '/inline-uploading.php',
	ADMIN_DIR . '/link-categories.php',
	ADMIN_DIR . '/list-manipulation.js',
	ADMIN_DIR . '/list-manipulation.php',
	WPINC . '/comment-functions.php',
	WPINC . '/feed-functions.php',
	WPINC . '/functions-compat.php',
	WPINC . '/functions-formatting.php',
	WPINC . '/functions-post.php',
	WPINC . '/js/dbx-key.js',
	WPINC . '/js/tinymce/plugins/autosave/langs/cs.js',
	WPINC . '/js/tinymce/plugins/autosave/langs/sv.js',
	WPINC . '/links.php',
	WPINC . '/pluggable-functions.php',
	WPINC . '/template-functions-author.php',
	WPINC . '/template-functions-category.php',
	WPINC . '/template-functions-options-general.php',
	WPINC . '/template-functions-links.php',
	WPINC . '/template-functions-post.php',
	WPINC . '/wp-l10n.php',
	// 2.2
	ADMIN_DIR . '/cat-js.php',
	ADMIN_DIR . '/import/b2.php',
	WPINC . '/js/autosave-js.php',
	WPINC . '/js/list-manipulation-js.php',
	WPINC . '/js/wp-ajax-js.php',
	// 2.3
	ADMIN_DIR . '/admin-db.php',
	ADMIN_DIR . '/cat.js',
	ADMIN_DIR . '/categories.js',
	ADMIN_DIR . '/custom-fields.js',
	ADMIN_DIR . '/dbx-admin-key.js',
	ADMIN_DIR . '/install-rtl.css',
	ADMIN_DIR . '/install.css',
	ADMIN_DIR . '/upgrade-schema.php',
	ADMIN_DIR . '/upload-functions.php',
	ADMIN_DIR . '/upload-rtl.css',
	ADMIN_DIR . '/upload.css',
	ADMIN_DIR . '/upload.js',
	ADMIN_DIR . '/users.js',
	ADMIN_DIR . '/widgets-rtl.css',
	ADMIN_DIR . '/widgets.css',
	ADMIN_DIR . '/xfn.js',
	WPINC . '/js/tinymce/license.html',
	// 2.5
	ADMIN_DIR . '/css/upload.css',
	ADMIN_DIR . '/images/box-bg-left.gif',
	ADMIN_DIR . '/images/box-bg-right.gif',
	ADMIN_DIR . '/images/box-bg.gif',
	ADMIN_DIR . '/images/box-butt-left.gif',
	ADMIN_DIR . '/images/box-butt-right.gif',
	ADMIN_DIR . '/images/box-butt.gif',
	ADMIN_DIR . '/images/box-head-left.gif',
	ADMIN_DIR . '/images/box-head-right.gif',
	ADMIN_DIR . '/images/box-head.gif',
	ADMIN_DIR . '/images/heading-bg.gif',
	ADMIN_DIR . '/images/login-bkg-bottom.gif',
	ADMIN_DIR . '/images/login-bkg-tile.gif',
	ADMIN_DIR . '/images/notice.gif',
	ADMIN_DIR . '/images/toggle.gif',
	ADMIN_DIR . '/includes/upload.php',
	ADMIN_DIR . '/js/dbx-admin-key.js',
	ADMIN_DIR . '/js/link-cat.js',
	ADMIN_DIR . '/profile-update.php',
	ADMIN_DIR . '/templates.php',
	WPINC . '/images/wlw/WpComments.png',
	WPINC . '/images/wlw/WpIcon.png',
	WPINC . '/images/wlw/WpWatermark.png',
	WPINC . '/js/dbx.js',
	WPINC . '/js/fat.js',
	WPINC . '/js/list-manipulation.js',
	WPINC . '/js/tinymce/langs/en.js',
	WPINC . '/js/tinymce/plugins/autosave/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/autosave/langs',
	WPINC . '/js/tinymce/plugins/directionality/images',
	WPINC . '/js/tinymce/plugins/directionality/langs',
	WPINC . '/js/tinymce/plugins/inlinepopups/css',
	WPINC . '/js/tinymce/plugins/inlinepopups/images',
	WPINC . '/js/tinymce/plugins/inlinepopups/jscripts',
	WPINC . '/js/tinymce/plugins/paste/images',
	WPINC . '/js/tinymce/plugins/paste/jscripts',
	WPINC . '/js/tinymce/plugins/paste/langs',
	WPINC . '/js/tinymce/plugins/spellchecker/classes/HttpClient.class.php',
	WPINC . '/js/tinymce/plugins/spellchecker/classes/TinyGoogleSpell.class.php',
	WPINC . '/js/tinymce/plugins/spellchecker/classes/TinyPspell.class.php',
	WPINC . '/js/tinymce/plugins/spellchecker/classes/TinyPspellShell.class.php',
	WPINC . '/js/tinymce/plugins/spellchecker/css/spellchecker.css',
	WPINC . '/js/tinymce/plugins/spellchecker/images',
	WPINC . '/js/tinymce/plugins/spellchecker/langs',
	WPINC . '/js/tinymce/plugins/spellchecker/tinyspell.php',
	WPINC . '/js/tinymce/plugins/wordpress/images',
	WPINC . '/js/tinymce/plugins/wordpress/langs',
	WPINC . '/js/tinymce/plugins/wordpress/wordpress.css',
	WPINC . '/js/tinymce/plugins/wphelp',
	WPINC . '/js/tinymce/themes/advanced/css',
	WPINC . '/js/tinymce/themes/advanced/images',
	WPINC . '/js/tinymce/themes/advanced/jscripts',
	WPINC . '/js/tinymce/themes/advanced/langs',
	// 2.5.1
	WPINC . '/js/tinymce/tiny_mce_gzip.php',
	// 2.6
	ADMIN_DIR . '/bookmarklet.php',
	WPINC . '/js/jquery/jquery.dimensions.min.js',
	WPINC . '/js/tinymce/plugins/wordpress/popups.css',
	WPINC . '/js/wp-ajax.js',
	// 2.7
	ADMIN_DIR . '/css/press-this-ie-rtl.css',
	ADMIN_DIR . '/css/press-this-ie.css',
	ADMIN_DIR . '/css/upload-rtl.css',
	ADMIN_DIR . '/edit-form.php',
	ADMIN_DIR . '/images/comment-pill.gif',
	ADMIN_DIR . '/images/comment-stalk-classic.gif',
	ADMIN_DIR . '/images/comment-stalk-fresh.gif',
	ADMIN_DIR . '/images/comment-stalk-rtl.gif',
	ADMIN_DIR . '/images/del.png',
	ADMIN_DIR . '/images/gear.png',
	ADMIN_DIR . '/images/media-button-gallery.gif',
	ADMIN_DIR . '/images/media-buttons.gif',
	ADMIN_DIR . '/images/postbox-bg.gif',
	ADMIN_DIR . '/images/tab.png',
	ADMIN_DIR . '/images/tail.gif',
	ADMIN_DIR . '/js/forms.js',
	ADMIN_DIR . '/js/upload.js',
	ADMIN_DIR . '/link-import.php',
	WPINC . '/images/audio.png',
	WPINC . '/images/css.png',
	WPINC . '/images/default.png',
	WPINC . '/images/doc.png',
	WPINC . '/images/exe.png',
	WPINC . '/images/html.png',
	WPINC . '/images/js.png',
	WPINC . '/images/pdf.png',
	WPINC . '/images/swf.png',
	WPINC . '/images/tar.png',
	WPINC . '/images/text.png',
	WPINC . '/images/video.png',
	WPINC . '/images/zip.png',
	WPINC . '/js/tinymce/tiny_mce_config.php',
	WPINC . '/js/tinymce/tiny_mce_ext.js',
	// 2.8
	ADMIN_DIR . '/js/users.js',
	WPINC . '/js/swfupload/plugins/swfupload.documentready.js',
	WPINC . '/js/swfupload/plugins/swfupload.graceful_degradation.js',
	WPINC . '/js/swfupload/swfupload_f9.swf',
	WPINC . '/js/tinymce/plugins/autosave',
	WPINC . '/js/tinymce/plugins/paste/css',
	WPINC . '/js/tinymce/utils/mclayer.js',
	WPINC . '/js/tinymce/wordpress.css',
	// 2.8.5
	ADMIN_DIR . '/import/btt.php',
	ADMIN_DIR . '/import/jkw.php',
	// 2.9
	ADMIN_DIR . '/js/page.dev.js',
	ADMIN_DIR . '/js/page.js',
	ADMIN_DIR . '/js/set-post-thumbnail-handler.dev.js',
	ADMIN_DIR . '/js/set-post-thumbnail-handler.js',
	ADMIN_DIR . '/js/slug.dev.js',
	ADMIN_DIR . '/js/slug.js',
	WPINC . '/gettext.php',
	WPINC . '/js/tinymce/plugins/wordpress/js',
	WPINC . '/streams.php',
	// MU
	'README.txt',
	'htaccess.dist',
	'index-install.php',
	ADMIN_DIR . '/css/mu-rtl.css',
	ADMIN_DIR . '/css/mu.css',
	ADMIN_DIR . '/images/site-admin.png',
	ADMIN_DIR . '/includes/mu.php',
	ADMIN_DIR . '/wpmu-admin.php',
	ADMIN_DIR . '/wpmu-blogs.php',
	ADMIN_DIR . '/wpmu-edit.php',
	ADMIN_DIR . '/wpmu-options.php',
	ADMIN_DIR . '/wpmu-themes.php',
	ADMIN_DIR . '/wpmu-upgrade-site.php',
	ADMIN_DIR . '/wpmu-users.php',
	WPINC . '/images/wordpress-mu.png',
	WPINC . '/wpmu-default-filters.php',
	WPINC . '/wpmu-functions.php',
	'wpmu-settings.php',
	// 3.0
	ADMIN_DIR . '/categories.php',
	ADMIN_DIR . '/edit-category-form.php',
	ADMIN_DIR . '/edit-page-form.php',
	ADMIN_DIR . '/edit-pages.php',
	ADMIN_DIR . '/images/admin-header-footer.png',
	ADMIN_DIR . '/images/browse-happy.gif',
	ADMIN_DIR . '/images/ico-add.png',
	ADMIN_DIR . '/images/ico-close.png',
	ADMIN_DIR . '/images/ico-edit.png',
	ADMIN_DIR . '/images/ico-viewpage.png',
	ADMIN_DIR . '/images/fav-top.png',
	ADMIN_DIR . '/images/screen-options-left.gif',
	ADMIN_DIR . '/images/wp-logo-vs.gif',
	ADMIN_DIR . '/images/wp-logo.gif',
	ADMIN_DIR . '/import',
	ADMIN_DIR . '/js/wp-gears.dev.js',
	ADMIN_DIR . '/js/wp-gears.js',
	ADMIN_DIR . '/options-misc.php',
	ADMIN_DIR . '/page-new.php',
	ADMIN_DIR . '/page.php',
	ADMIN_DIR . '/rtl.css',
	ADMIN_DIR . '/rtl.dev.css',
	ADMIN_DIR . '/update-links.php',
	ADMIN_DIR . '/wp-admin.css',
	ADMIN_DIR . '/wp-admin.dev.css',
	WPINC . '/js/codepress',
	WPINC . '/js/codepress/engines/khtml.js',
	WPINC . '/js/codepress/engines/older.js',
	WPINC . '/js/jquery/autocomplete.dev.js',
	WPINC . '/js/jquery/autocomplete.js',
	WPINC . '/js/jquery/interface.js',
	WPINC . '/js/scriptaculous/prototype.js',
	// Following file added back in 5.1, see #45645.
	//WPINC . '/js/tinymce/wp-tinymce.js',
	// 3.1
	ADMIN_DIR . '/edit-attachment-rows.php',
	ADMIN_DIR . '/edit-link-categories.php',
	ADMIN_DIR . '/edit-link-category-form.php',
	ADMIN_DIR . '/edit-post-rows.php',
	ADMIN_DIR . '/images/button-grad-active-vs.png',
	ADMIN_DIR . '/images/button-grad-vs.png',
	ADMIN_DIR . '/images/fav-arrow-vs-rtl.gif',
	ADMIN_DIR . '/images/fav-arrow-vs.gif',
	ADMIN_DIR . '/images/fav-top-vs.gif',
	ADMIN_DIR . '/images/list-vs.png',
	ADMIN_DIR . '/images/screen-options-right-up.gif',
	ADMIN_DIR . '/images/screen-options-right.gif',
	ADMIN_DIR . '/images/visit-site-button-grad-vs.gif',
	ADMIN_DIR . '/images/visit-site-button-grad.gif',
	ADMIN_DIR . '/link-category.php',
	ADMIN_DIR . '/sidebar.php',
	WPINC . '/classes.php',
	WPINC . '/js/tinymce/blank.htm',
	WPINC . '/js/tinymce/plugins/media/css/content.css',
	WPINC . '/js/tinymce/plugins/media/img',
	WPINC . '/js/tinymce/plugins/safari',
	// 3.2
	ADMIN_DIR . '/images/logo-login.gif',
	ADMIN_DIR . '/images/star.gif',
	ADMIN_DIR . '/js/list-table.dev.js',
	ADMIN_DIR . '/js/list-table.js',
	WPINC . '/default-embeds.php',
	WPINC . '/js/tinymce/plugins/wordpress/img/help.gif',
	WPINC . '/js/tinymce/plugins/wordpress/img/more.gif',
	WPINC . '/js/tinymce/plugins/wordpress/img/toolbars.gif',
	WPINC . '/js/tinymce/themes/advanced/img/fm.gif',
	WPINC . '/js/tinymce/themes/advanced/img/sflogo.png',
	// 3.3
	ADMIN_DIR . '/css/colors-classic-rtl.css',
	ADMIN_DIR . '/css/colors-classic-rtl.dev.css',
	ADMIN_DIR . '/css/colors-fresh-rtl.css',
	ADMIN_DIR . '/css/colors-fresh-rtl.dev.css',
	ADMIN_DIR . '/css/dashboard-rtl.dev.css',
	ADMIN_DIR . '/css/dashboard.dev.css',
	ADMIN_DIR . '/css/global-rtl.css',
	ADMIN_DIR . '/css/global-rtl.dev.css',
	ADMIN_DIR . '/css/global.css',
	ADMIN_DIR . '/css/global.dev.css',
	ADMIN_DIR . '/css/install-rtl.dev.css',
	ADMIN_DIR . '/css/login-rtl.dev.css',
	ADMIN_DIR . '/css/login.dev.css',
	ADMIN_DIR . '/css/ms.css',
	ADMIN_DIR . '/css/ms.dev.css',
	ADMIN_DIR . '/css/nav-menu-rtl.css',
	ADMIN_DIR . '/css/nav-menu-rtl.dev.css',
	ADMIN_DIR . '/css/nav-menu.css',
	ADMIN_DIR . '/css/nav-menu.dev.css',
	ADMIN_DIR . '/css/plugin-install-rtl.css',
	ADMIN_DIR . '/css/plugin-install-rtl.dev.css',
	ADMIN_DIR . '/css/plugin-install.css',
	ADMIN_DIR . '/css/plugin-install.dev.css',
	ADMIN_DIR . '/css/press-this-rtl.dev.css',
	ADMIN_DIR . '/css/press-this.dev.css',
	ADMIN_DIR . '/css/theme-editor-rtl.css',
	ADMIN_DIR . '/css/theme-editor-rtl.dev.css',
	ADMIN_DIR . '/css/theme-editor.css',
	ADMIN_DIR . '/css/theme-editor.dev.css',
	ADMIN_DIR . '/css/theme-install-rtl.css',
	ADMIN_DIR . '/css/theme-install-rtl.dev.css',
	ADMIN_DIR . '/css/theme-install.css',
	ADMIN_DIR . '/css/theme-install.dev.css',
	ADMIN_DIR . '/css/widgets-rtl.dev.css',
	ADMIN_DIR . '/css/widgets.dev.css',
	ADMIN_DIR . '/includes/internal-linking.php',
	WPINC . '/images/admin-bar-sprite-rtl.png',
	WPINC . '/js/jquery/ui.button.js',
	WPINC . '/js/jquery/ui.core.js',
	WPINC . '/js/jquery/ui.dialog.js',
	WPINC . '/js/jquery/ui.draggable.js',
	WPINC . '/js/jquery/ui.droppable.js',
	WPINC . '/js/jquery/ui.mouse.js',
	WPINC . '/js/jquery/ui.position.js',
	WPINC . '/js/jquery/ui.resizable.js',
	WPINC . '/js/jquery/ui.selectable.js',
	WPINC . '/js/jquery/ui.sortable.js',
	WPINC . '/js/jquery/ui.tabs.js',
	WPINC . '/js/jquery/ui.widget.js',
	WPINC . '/js/l10n.dev.js',
	WPINC . '/js/l10n.js',
	WPINC . '/js/tinymce/plugins/wplink/css',
	WPINC . '/js/tinymce/plugins/wplink/img',
	WPINC . '/js/tinymce/plugins/wplink/js',
	WPINC . '/js/tinymce/themes/advanced/img/wpicons.png',
	WPINC . '/js/tinymce/themes/advanced/skins/wp_theme/img/butt2.png',
	WPINC . '/js/tinymce/themes/advanced/skins/wp_theme/img/button_bg.png',
	WPINC . '/js/tinymce/themes/advanced/skins/wp_theme/img/down_arrow.gif',
	WPINC . '/js/tinymce/themes/advanced/skins/wp_theme/img/fade-butt.png',
	WPINC . '/js/tinymce/themes/advanced/skins/wp_theme/img/separator.gif',
	ADMIN_DIR . '/images/gray-star.png',
	ADMIN_DIR . '/images/logo-login.png',
	ADMIN_DIR . '/images/star.png',
	ADMIN_DIR . '/index-extra.php',
	ADMIN_DIR . '/network/index-extra.php',
	ADMIN_DIR . '/user/index-extra.php',
	ADMIN_DIR . '/images/screenshots/admin-flyouts.png',
	ADMIN_DIR . '/images/screenshots/coediting.png',
	ADMIN_DIR . '/images/screenshots/drag-and-drop.png',
	ADMIN_DIR . '/images/screenshots/help-screen.png',
	ADMIN_DIR . '/images/screenshots/media-icon.png',
	ADMIN_DIR . '/images/screenshots/new-feature-pointer.png',
	ADMIN_DIR . '/images/screenshots/welcome-screen.png',
	WPINC . '/css/editor-buttons.css',
	WPINC . '/css/editor-buttons.dev.css',
	WPINC . '/js/tinymce/plugins/paste/blank.htm',
	WPINC . '/js/tinymce/plugins/wordpress/css',
	WPINC . '/js/tinymce/plugins/wordpress/editor_plugin.dev.js',
	WPINC . '/js/tinymce/plugins/wordpress/img/embedded.png',
	WPINC . '/js/tinymce/plugins/wordpress/img/more_bug.gif',
	WPINC . '/js/tinymce/plugins/wordpress/img/page_bug.gif',
	WPINC . '/js/tinymce/plugins/wpdialogs/editor_plugin.dev.js',
	WPINC . '/js/tinymce/plugins/wpeditimage/css/editimage-rtl.css',
	WPINC . '/js/tinymce/plugins/wpeditimage/editor_plugin.dev.js',
	WPINC . '/js/tinymce/plugins/wpfullscreen/editor_plugin.dev.js',
	WPINC . '/js/tinymce/plugins/wpgallery/editor_plugin.dev.js',
	WPINC . '/js/tinymce/plugins/wpgallery/img/gallery.png',
	WPINC . '/js/tinymce/plugins/wplink/editor_plugin.dev.js',
	// Don't delete, yet: 'wp-pass.php',
	// Don't delete, yet: 'wp-register.php',
	// 3.5
	ADMIN_DIR . '/gears-manifest.php',
	ADMIN_DIR . '/includes/manifest.php',
	ADMIN_DIR . '/images/archive-link.png',
	ADMIN_DIR . '/images/blue-grad.png',
	ADMIN_DIR . '/images/button-grad-active.png',
	ADMIN_DIR . '/images/button-grad.png',
	ADMIN_DIR . '/images/ed-bg-vs.gif',
	ADMIN_DIR . '/images/ed-bg.gif',
	ADMIN_DIR . '/images/fade-butt.png',
	ADMIN_DIR . '/images/fav-arrow-rtl.gif',
	ADMIN_DIR . '/images/fav-arrow.gif',
	ADMIN_DIR . '/images/fav-vs.png',
	ADMIN_DIR . '/images/fav.png',
	ADMIN_DIR . '/images/gray-grad.png',
	ADMIN_DIR . '/images/loading-publish.gif',
	ADMIN_DIR . '/images/logo-ghost.png',
	ADMIN_DIR . '/images/logo.gif',
	ADMIN_DIR . '/images/menu-arrow-frame-rtl.png',
	ADMIN_DIR . '/images/menu-arrow-frame.png',
	ADMIN_DIR . '/images/menu-arrows.gif',
	ADMIN_DIR . '/images/menu-bits-rtl-vs.gif',
	ADMIN_DIR . '/images/menu-bits-rtl.gif',
	ADMIN_DIR . '/images/menu-bits-vs.gif',
	ADMIN_DIR . '/images/menu-bits.gif',
	ADMIN_DIR . '/images/menu-dark-rtl-vs.gif',
	ADMIN_DIR . '/images/menu-dark-rtl.gif',
	ADMIN_DIR . '/images/menu-dark-vs.gif',
	ADMIN_DIR . '/images/menu-dark.gif',
	ADMIN_DIR . '/images/required.gif',
	ADMIN_DIR . '/images/screen-options-toggle-vs.gif',
	ADMIN_DIR . '/images/screen-options-toggle.gif',
	ADMIN_DIR . '/images/toggle-arrow-rtl.gif',
	ADMIN_DIR . '/images/toggle-arrow.gif',
	ADMIN_DIR . '/images/upload-classic.png',
	ADMIN_DIR . '/images/upload-fresh.png',
	ADMIN_DIR . '/images/white-grad-active.png',
	ADMIN_DIR . '/images/white-grad.png',
	ADMIN_DIR . '/images/widgets-arrow-vs.gif',
	ADMIN_DIR . '/images/widgets-arrow.gif',
	ADMIN_DIR . '/images/wpspin_dark.gif',
	WPINC . '/images/upload.png',
	WPINC . '/js/prototype.js',
	WPINC . '/js/scriptaculous',
	ADMIN_DIR . '/css/wp-admin-rtl.dev.css',
	ADMIN_DIR . '/css/wp-admin.dev.css',
	ADMIN_DIR . '/css/media-rtl.dev.css',
	ADMIN_DIR . '/css/media.dev.css',
	ADMIN_DIR . '/css/colors-classic.dev.css',
	ADMIN_DIR . '/css/customize-controls-rtl.dev.css',
	ADMIN_DIR . '/css/customize-controls.dev.css',
	ADMIN_DIR . '/css/ie-rtl.dev.css',
	ADMIN_DIR . '/css/ie.dev.css',
	ADMIN_DIR . '/css/install.dev.css',
	ADMIN_DIR . '/css/colors-fresh.dev.css',
	WPINC . '/js/customize-base.dev.js',
	WPINC . '/js/json2.dev.js',
	WPINC . '/js/comment-reply.dev.js',
	WPINC . '/js/customize-preview.dev.js',
	WPINC . '/js/wplink.dev.js',
	WPINC . '/js/tw-sack.dev.js',
	WPINC . '/js/wp-list-revisions.dev.js',
	WPINC . '/js/autosave.dev.js',
	WPINC . '/js/admin-bar.dev.js',
	WPINC . '/js/quicktags.dev.js',
	WPINC . '/js/wp-ajax-response.dev.js',
	WPINC . '/js/wp-pointer.dev.js',
	WPINC . '/js/hoverIntent.dev.js',
	WPINC . '/js/colorpicker.dev.js',
	WPINC . '/js/wp-lists.dev.js',
	WPINC . '/js/customize-loader.dev.js',
	WPINC . '/js/jquery/jquery.table-hotkeys.dev.js',
	WPINC . '/js/jquery/jquery.color.dev.js',
	WPINC . '/js/jquery/jquery.color.js',
	WPINC . '/js/jquery/jquery.hotkeys.dev.js',
	WPINC . '/js/jquery/jquery.form.dev.js',
	WPINC . '/js/jquery/suggest.dev.js',
	ADMIN_DIR . '/js/xfn.dev.js',
	ADMIN_DIR . '/js/set-post-thumbnail.dev.js',
	ADMIN_DIR . '/js/comment.dev.js',
	ADMIN_DIR . '/js/theme.dev.js',
	ADMIN_DIR . '/js/cat.dev.js',
	ADMIN_DIR . '/js/password-strength-meter.dev.js',
	ADMIN_DIR . '/js/user-profile.dev.js',
	ADMIN_DIR . '/js/theme-preview.dev.js',
	ADMIN_DIR . '/js/post.dev.js',
	ADMIN_DIR . '/js/media-upload.dev.js',
	ADMIN_DIR . '/js/word-count.dev.js',
	ADMIN_DIR . '/js/plugin-install.dev.js',
	ADMIN_DIR . '/js/media-gallery.dev.js',
	ADMIN_DIR . '/js/custom-fields.dev.js',
	ADMIN_DIR . '/js/custom-background.dev.js',
	ADMIN_DIR . '/js/common.dev.js',
	ADMIN_DIR . '/js/inline-edit-tax.dev.js',
	ADMIN_DIR . '/js/gallery.dev.js',
	ADMIN_DIR . '/js/utils.dev.js',
	ADMIN_DIR . '/js/widgets.dev.js',
	ADMIN_DIR . '/js/wp-fullscreen.dev.js',
	ADMIN_DIR . '/js/nav-menu.dev.js',
	ADMIN_DIR . '/js/dashboard.dev.js',
	ADMIN_DIR . '/js/link.dev.js',
	ADMIN_DIR . '/js/user-suggest.dev.js',
	ADMIN_DIR . '/js/postbox.dev.js',
	ADMIN_DIR . '/js/tags.dev.js',
	ADMIN_DIR . '/js/image-edit.dev.js',
	ADMIN_DIR . '/js/media.dev.js',
	ADMIN_DIR . '/js/customize-controls.dev.js',
	ADMIN_DIR . '/js/inline-edit-post.dev.js',
	ADMIN_DIR . '/js/categories.dev.js',
	ADMIN_DIR . '/js/editor.dev.js',
	WPINC . '/js/tinymce/plugins/wpeditimage/js/editimage.dev.js',
	WPINC . '/js/tinymce/plugins/wpdialogs/js/popup.dev.js',
	WPINC . '/js/tinymce/plugins/wpdialogs/js/wpdialog.dev.js',
	WPINC . '/js/plupload/handlers.dev.js',
	WPINC . '/js/plupload/wp-plupload.dev.js',
	WPINC . '/js/swfupload/handlers.dev.js',
	WPINC . '/js/jcrop/jquery.Jcrop.dev.js',
	WPINC . '/js/jcrop/jquery.Jcrop.js',
	WPINC . '/js/jcrop/jquery.Jcrop.css',
	WPINC . '/js/imgareaselect/jquery.imgareaselect.dev.js',
	WPINC . '/css/wp-pointer.dev.css',
	WPINC . '/css/editor.dev.css',
	WPINC . '/css/jquery-ui-dialog.dev.css',
	WPINC . '/css/admin-bar-rtl.dev.css',
	WPINC . '/css/admin-bar.dev.css',
	WPINC . '/js/jquery/ui/jquery.effects.clip.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.scale.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.blind.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.core.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.shake.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.fade.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.explode.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.slide.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.drop.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.highlight.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.bounce.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.pulsate.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.transfer.min.js',
	WPINC . '/js/jquery/ui/jquery.effects.fold.min.js',
	ADMIN_DIR . '/images/screenshots/captions-1.png',
	ADMIN_DIR . '/images/screenshots/captions-2.png',
	ADMIN_DIR . '/images/screenshots/flex-header-1.png',
	ADMIN_DIR . '/images/screenshots/flex-header-2.png',
	ADMIN_DIR . '/images/screenshots/flex-header-3.png',
	ADMIN_DIR . '/images/screenshots/flex-header-media-library.png',
	ADMIN_DIR . '/images/screenshots/theme-customizer.png',
	ADMIN_DIR . '/images/screenshots/twitter-embed-1.png',
	ADMIN_DIR . '/images/screenshots/twitter-embed-2.png',
	ADMIN_DIR . '/js/utils.js',
	// Added back in 5.3 [45448], see #43895.
	// ADMIN_DIR . '/privacy.php',
	'wp-app.php',
	WPINC . '/classes/class-wp-atom-server.php',
	WPINC . '/js/tinymce/themes/advanced/skins/wp_theme/ui.css',
	// 3.5.2
	WPINC . '/js/swfupload/swfupload-all.js',
	// 3.6
	ADMIN_DIR . '/js/revisions-js.php',
	ADMIN_DIR . '/images/screenshots',
	ADMIN_DIR . '/js/categories.js',
	ADMIN_DIR . '/js/categories.min.js',
	ADMIN_DIR . '/js/custom-fields.js',
	ADMIN_DIR . '/js/custom-fields.min.js',
	// 3.7
	ADMIN_DIR . '/js/cat.js',
	ADMIN_DIR . '/js/cat.min.js',
	WPINC . '/js/tinymce/plugins/wpeditimage/js/editimage.min.js',
	// 3.8
	WPINC . '/js/tinymce/themes/advanced/skins/wp_theme/img/page_bug.gif',
	WPINC . '/js/tinymce/themes/advanced/skins/wp_theme/img/more_bug.gif',
	WPINC . '/js/thickbox/tb-close-2x.png',
	WPINC . '/js/thickbox/tb-close.png',
	WPINC . '/images/wpmini-blue-2x.png',
	WPINC . '/images/wpmini-blue.png',
	ADMIN_DIR . '/css/colors-fresh.css',
	ADMIN_DIR . '/css/colors-classic.css',
	ADMIN_DIR . '/css/colors-fresh.min.css',
	ADMIN_DIR . '/css/colors-classic.min.css',
	ADMIN_DIR . '/js/about.min.js',
	ADMIN_DIR . '/js/about.js',
	ADMIN_DIR . '/images/arrows-dark-vs-2x.png',
	ADMIN_DIR . '/images/wp-logo-vs.png',
	ADMIN_DIR . '/images/arrows-dark-vs.png',
	ADMIN_DIR . '/images/wp-logo.png',
	ADMIN_DIR . '/images/arrows-pr.png',
	ADMIN_DIR . '/images/arrows-dark.png',
	ADMIN_DIR . '/images/press-this.png',
	ADMIN_DIR . '/images/press-this-2x.png',
	ADMIN_DIR . '/images/arrows-vs-2x.png',
	ADMIN_DIR . '/images/welcome-icons.png',
	ADMIN_DIR . '/images/wp-logo-2x.png',
	ADMIN_DIR . '/images/stars-rtl-2x.png',
	ADMIN_DIR . '/images/arrows-dark-2x.png',
	ADMIN_DIR . '/images/arrows-pr-2x.png',
	ADMIN_DIR . '/images/menu-shadow-rtl.png',
	ADMIN_DIR . '/images/arrows-vs.png',
	ADMIN_DIR . '/images/about-search-2x.png',
	ADMIN_DIR . '/images/bubble_bg-rtl-2x.gif',
	ADMIN_DIR . '/images/wp-badge-2x.png',
	ADMIN_DIR . '/images/wordpress-logo-2x.png',
	ADMIN_DIR . '/images/bubble_bg-rtl.gif',
	ADMIN_DIR . '/images/wp-badge.png',
	ADMIN_DIR . '/images/menu-shadow.png',
	ADMIN_DIR . '/images/about-globe-2x.png',
	ADMIN_DIR . '/images/welcome-icons-2x.png',
	ADMIN_DIR . '/images/stars-rtl.png',
	ADMIN_DIR . '/images/wp-logo-vs-2x.png',
	ADMIN_DIR . '/images/about-updates-2x.png',
	// 3.9
	ADMIN_DIR . '/css/colors.css',
	ADMIN_DIR . '/css/colors.min.css',
	ADMIN_DIR . '/css/colors-rtl.css',
	ADMIN_DIR . '/css/colors-rtl.min.css',
	// Following files added back in 4.5, see #36083.
	// ADMIN_DIR . '/css/media-rtl.min.css',
	// ADMIN_DIR . '/css/media.min.css',
	// ADMIN_DIR . '/css/farbtastic-rtl.min.css',
	ADMIN_DIR . '/images/lock-2x.png',
	ADMIN_DIR . '/images/lock.png',
	ADMIN_DIR . '/js/theme-preview.js',
	ADMIN_DIR . '/js/theme-install.min.js',
	ADMIN_DIR . '/js/theme-install.js',
	ADMIN_DIR . '/js/theme-preview.min.js',
	WPINC . '/js/plupload/plupload.html4.js',
	WPINC . '/js/plupload/plupload.html5.js',
	WPINC . '/js/plupload/changelog.txt',
	WPINC . '/js/plupload/plupload.silverlight.js',
	WPINC . '/js/plupload/plupload.flash.js',
	// Added back in 4.9 [41328], see #41755.
	// WPINC . '/js/plupload/plupload.js',
	WPINC . '/js/tinymce/plugins/spellchecker',
	WPINC . '/js/tinymce/plugins/inlinepopups',
	WPINC . '/js/tinymce/plugins/media/js',
	WPINC . '/js/tinymce/plugins/media/css',
	WPINC . '/js/tinymce/plugins/wordpress/img',
	WPINC . '/js/tinymce/plugins/wpdialogs/js',
	WPINC . '/js/tinymce/plugins/wpeditimage/img',
	WPINC . '/js/tinymce/plugins/wpeditimage/js',
	WPINC . '/js/tinymce/plugins/wpeditimage/css',
	WPINC . '/js/tinymce/plugins/wpgallery/img',
	WPINC . '/js/tinymce/plugins/wpfullscreen/css',
	WPINC . '/js/tinymce/plugins/paste/js',
	WPINC . '/js/tinymce/themes/advanced',
	WPINC . '/js/tinymce/tiny_mce.js',
	WPINC . '/js/tinymce/mark_loaded_src.js',
	WPINC . '/js/tinymce/wp-tinymce-schema.js',
	WPINC . '/js/tinymce/plugins/media/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/media/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/media/media.htm',
	WPINC . '/js/tinymce/plugins/wpview/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/wpview/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/directionality/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/directionality/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/wordpress/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/wordpress/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/wpdialogs/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/wpdialogs/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/wpeditimage/editimage.html',
	WPINC . '/js/tinymce/plugins/wpeditimage/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/wpeditimage/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/fullscreen/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/fullscreen/fullscreen.htm',
	WPINC . '/js/tinymce/plugins/fullscreen/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/wplink/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/wplink/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/wpgallery/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/wpgallery/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/tabfocus/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/tabfocus/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/wpfullscreen/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/wpfullscreen/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/paste/editor_plugin.js',
	WPINC . '/js/tinymce/plugins/paste/pasteword.htm',
	WPINC . '/js/tinymce/plugins/paste/editor_plugin_src.js',
	WPINC . '/js/tinymce/plugins/paste/pastetext.htm',
	WPINC . '/js/tinymce/langs/wp-langs.php',
	// 4.1
	WPINC . '/js/jquery/ui/jquery.ui.accordion.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.autocomplete.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.button.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.core.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.datepicker.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.dialog.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.draggable.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.droppable.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-blind.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-bounce.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-clip.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-drop.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-explode.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-fade.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-fold.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-highlight.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-pulsate.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-scale.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-shake.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-slide.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect-transfer.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.effect.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.menu.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.mouse.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.position.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.progressbar.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.resizable.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.selectable.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.slider.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.sortable.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.spinner.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.tabs.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.tooltip.min.js',
	WPINC . '/js/jquery/ui/jquery.ui.widget.min.js',
	WPINC . '/js/tinymce/skins/wordpress/images/dashicon-no-alt.png',
	// 4.3
	ADMIN_DIR . '/js/wp-fullscreen.js',
	ADMIN_DIR . '/js/wp-fullscreen.min.js',
	WPINC . '/js/tinymce/wp-mce-help.php',
	WPINC . '/js/tinymce/plugins/wpfullscreen',
	// 4.5
	WPINC . '/theme-compat/comments-popup.php',
	// 4.6
	ADMIN_DIR . '/includes/class-wp-automatic-upgrader.php', // Wrong file name, see #37628.
	// 4.8
	WPINC . '/js/tinymce/plugins/wpembed',
	WPINC . '/js/tinymce/plugins/media/moxieplayer.swf',
	WPINC . '/js/tinymce/skins/lightgray/fonts/readme.md',
	WPINC . '/js/tinymce/skins/lightgray/fonts/tinymce-small.json',
	WPINC . '/js/tinymce/skins/lightgray/fonts/tinymce.json',
	WPINC . '/js/tinymce/skins/lightgray/skin.ie7.min.css',
	// 4.9
	ADMIN_DIR . '/css/press-this-editor-rtl.css',
	ADMIN_DIR . '/css/press-this-editor-rtl.min.css',
	ADMIN_DIR . '/css/press-this-editor.css',
	ADMIN_DIR . '/css/press-this-editor.min.css',
	ADMIN_DIR . '/css/press-this-rtl.css',
	ADMIN_DIR . '/css/press-this-rtl.min.css',
	ADMIN_DIR . '/css/press-this.css',
	ADMIN_DIR . '/css/press-this.min.css',
	ADMIN_DIR . '/includes/class-wp-press-this.php',
	ADMIN_DIR . '/js/bookmarklet.js',
	ADMIN_DIR . '/js/bookmarklet.min.js',
	ADMIN_DIR . '/js/press-this.js',
	ADMIN_DIR . '/js/press-this.min.js',
	WPINC . '/js/mediaelement/background.png',
	WPINC . '/js/mediaelement/bigplay.png',
	WPINC . '/js/mediaelement/bigplay.svg',
	WPINC . '/js/mediaelement/controls.png',
	WPINC . '/js/mediaelement/controls.svg',
	WPINC . '/js/mediaelement/flashmediaelement.swf',
	WPINC . '/js/mediaelement/froogaloop.min.js',
	WPINC . '/js/mediaelement/jumpforward.png',
	WPINC . '/js/mediaelement/loading.gif',
	WPINC . '/js/mediaelement/silverlightmediaelement.xap',
	WPINC . '/js/mediaelement/skipback.png',
	WPINC . '/js/plupload/plupload.flash.swf',
	WPINC . '/js/plupload/plupload.full.min.js',
	WPINC . '/js/plupload/plupload.silverlight.xap',
	WPINC . '/js/swfupload/plugins',
	WPINC . '/js/swfupload/swfupload.swf',
	// 4.9.2
	WPINC . '/js/mediaelement/lang',
	WPINC . '/js/mediaelement/lang/ca.js',
	WPINC . '/js/mediaelement/lang/cs.js',
	WPINC . '/js/mediaelement/lang/de.js',
	WPINC . '/js/mediaelement/lang/es.js',
	WPINC . '/js/mediaelement/lang/fa.js',
	WPINC . '/js/mediaelement/lang/fr.js',
	WPINC . '/js/mediaelement/lang/hr.js',
	WPINC . '/js/mediaelement/lang/hu.js',
	WPINC . '/js/mediaelement/lang/it.js',
	WPINC . '/js/mediaelement/lang/ja.js',
	WPINC . '/js/mediaelement/lang/ko.js',
	WPINC . '/js/mediaelement/lang/nl.js',
	WPINC . '/js/mediaelement/lang/pl.js',
	WPINC . '/js/mediaelement/lang/pt.js',
	WPINC . '/js/mediaelement/lang/ro.js',
	WPINC . '/js/mediaelement/lang/ru.js',
	WPINC . '/js/mediaelement/lang/sk.js',
	WPINC . '/js/mediaelement/lang/sv.js',
	WPINC . '/js/mediaelement/lang/uk.js',
	WPINC . '/js/mediaelement/lang/zh-cn.js',
	WPINC . '/js/mediaelement/lang/zh.js',
	WPINC . '/js/mediaelement/mediaelement-flash-audio-ogg.swf',
	WPINC . '/js/mediaelement/mediaelement-flash-audio.swf',
	WPINC . '/js/mediaelement/mediaelement-flash-video-hls.swf',
	WPINC . '/js/mediaelement/mediaelement-flash-video-mdash.swf',
	WPINC . '/js/mediaelement/mediaelement-flash-video.swf',
	WPINC . '/js/mediaelement/renderers/dailymotion.js',
	WPINC . '/js/mediaelement/renderers/dailymotion.min.js',
	WPINC . '/js/mediaelement/renderers/facebook.js',
	WPINC . '/js/mediaelement/renderers/facebook.min.js',
	WPINC . '/js/mediaelement/renderers/soundcloud.js',
	WPINC . '/js/mediaelement/renderers/soundcloud.min.js',
	WPINC . '/js/mediaelement/renderers/twitch.js',
	WPINC . '/js/mediaelement/renderers/twitch.min.js',
	// 5.0
	WPINC . '/js/codemirror/jshint.js',
	// 5.1
	WPINC . '/random_compat/random_bytes_openssl.php',
	WPINC . '/js/tinymce/wp-tinymce.js.gz',
	// 5.3
	WPINC . '/js/wp-a11y.js',     // Moved to: wp-includes/js/dist/a11y.js
	WPINC . '/js/wp-a11y.min.js', // Moved to: wp-includes/js/dist/a11y.min.js
	// 5.4
	ADMIN_DIR . '/js/wp-fullscreen-stub.js',
	ADMIN_DIR . '/js/wp-fullscreen-stub.min.js',
);

/**
 * Stores new files in WP_CONTENT_DIR to copy
 *
 * The contents of this array indicate any new bundled plugins/themes which
 * should be installed with the WordPress Upgrade. These items will not be
 * re-installed in future upgrades, this behaviour is controlled by the
 * introduced version present here being older than the current installed version.
 *
 * The content of this array should follow the following format:
 * Filename (relative to WP_CONTENT_DIR) => Introduced version
 * Directories should be noted by suffixing it with a trailing slash (/)
 *
 * @since 3.2.0
 * @since 4.7.0 New themes were not automatically installed for 4.4-4.6 on
 *              upgrade. New themes are now installed again. To disable new
 *              themes from being installed on upgrade, explicitly define
 *              CORE_UPGRADE_SKIP_NEW_BUNDLED as true.
 * @global array $_new_bundled_files
 * @var array
 * @name $_new_bundled_files
 */
global $_new_bundled_files;

$_new_bundled_files = array(
	'plugins/akismet/'        => '2.0',
	'themes/twentyten/'       => '3.0',
	'themes/twentyeleven/'    => '3.2',
	'themes/twentytwelve/'    => '3.5',
	'themes/twentythirteen/'  => '3.6',
	'themes/twentyfourteen/'  => '3.8',
	'themes/twentyfifteen/'   => '4.1',
	'themes/twentysixteen/'   => '4.4',
	'themes/twentyseventeen/' => '4.7',
	'themes/twentynineteen/'  => '5.0',
	'themes/twentytwenty/'    => '5.3',
);

/**
 * Upgrades the core of WordPress.
 *
 * This will create a .maintenance file at the base of the WordPress directory
 * to ensure that people can not access the web site, when the files are being
 * copied to their locations.
 *
 * The files in the `$_old_files` list will be removed and the new files
 * copied from the zip file after the database is upgraded.
 *
 * The files in the `$_new_bundled_files` list will be added to the installation
 * if the version is greater than or equal to the old version being upgraded.
 *
 * The steps for the upgrader for after the new release is downloaded and
 * unzipped is:
 *   1. Test unzipped location for select files to ensure that unzipped worked.
 *   2. Create the .maintenance file in current WordPress base.
 *   3. Copy new WordPress directory over old WordPress files.
 *   4. Upgrade WordPress to new version.
 *     4.1. Copy all files/folders other than WP_CONTENT_DIR
 *     4.2. Copy any language files to WP_LANG_DIR (which may differ from WP_CONTENT_DIR
 *     4.3. Copy any new bundled themes/plugins to their respective locations
 *   5. Delete new WordPress directory path.
 *   6. Delete .maintenance file.
 *   7. Remove old files.
 *   8. Delete 'update_core' option.
 *
 * There are several areas of failure. For instance if PHP times out before step
 * 6, then you will not be able to access any portion of your site. Also, since
 * the upgrade will not continue where it left off, you will not be able to
 * automatically remove old files and remove the 'update_core' option. This
 * isn't that bad.
 *
 * If the copy of the new WordPress over the old fails, then the worse is that
 * the new WordPress directory will remain.
 *
 * If it is assumed that every file will be copied over, including plugins and
 * themes, then if you edit the default theme, you should rename it, so that
 * your changes remain.
 *
 * @since 2.7.0
 *
 * @global WP_Filesystem_Base $wp_filesystem          WordPress filesystem subclass.
 * @global array              $_old_files
 * @global array              $_new_bundled_files
 * @global wpdb               $wpdb                   WordPress database abstraction object.
 * @global string             $wp_version
 * @global string             $required_php_version
 * @global string             $required_mysql_version
 *
 * @param string $from New release unzipped path.
 * @param string $to   Path to old WordPress installation.
 * @return string|WP_Error New WordPress version on success, WP_Error on failure.
 */
function update_core( $from, $to ) {
	global $wp_filesystem, $_old_files, $_new_bundled_files, $wpdb;

	set_time_limit( 300 );

	/**
	 * Filters feedback messages displayed during the core update process.
	 *
	 * The filter is first evaluated after the zip file for the latest version
	 * has been downloaded and unzipped. It is evaluated five more times during
	 * the process:
	 *
	 * 1. Before WordPress begins the core upgrade process.
	 * 2. Before Maintenance Mode is enabled.
	 * 3. Before WordPress begins copying over the necessary files.
	 * 4. Before Maintenance Mode is disabled.
	 * 5. Before the database is upgraded.
	 *
	 * @since 2.5.0
	 *
	 * @param string $feedback The core update feedback messages.
	 */
	apply_filters( 'update_feedback', __( 'Verifying the unpacked files&#8230;' ) );

	// Sanity check the unzipped distribution.
	$distro = '';
	$roots  = array( '/wordpress/', '/wordpress-mu/' );
	foreach ( $roots as $root ) {
		if ( $wp_filesystem->exists( $from . $root . 'readme.html' ) && $wp_filesystem->exists( $from . $root . WPINC . '/version.php' ) ) {
			$distro = $root;
			break;
		}
	}
	if ( ! $distro ) {
		$wp_filesystem->delete( $from, true );
		return new WP_Error( 'insane_distro', __( 'The update could not be unpacked' ) );
	}

	/*
	 * Import $wp_version, $required_php_version, and $required_mysql_version from the new version.
	 * DO NOT globalise any variables imported from `version-current.php` in this function.
	 *
	 * BC Note: $wp_filesystem->wp_content_dir() returned unslashed pre-2.8
	 */
	$versions_file = trailingslashit( $wp_filesystem->wp_content_dir() ) . 'upgrade/version-current.php';
	if ( ! $wp_filesystem->copy( $from . $distro . WPINC . '/version.php', $versions_file ) ) {
		$wp_filesystem->delete( $from, true );
		return new WP_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), WPINC . '/version.php' );
	}

	$wp_filesystem->chmod( $versions_file, FS_CHMOD_FILE );
	require WP_CONTENT_DIR . '/upgrade/version-current.php';
	$wp_filesystem->delete( $versions_file );

	$php_version       = phpversion();
	$mysql_version     = $wpdb->db_version();
	$old_wp_version    = $GLOBALS['wp_version']; // The version of WordPress we're updating from.
	$development_build = ( false !== strpos( $old_wp_version . $wp_version, '-' ) ); // A dash in the version indicates a development release.
	$php_compat        = version_compare( $php_version, $required_php_version, '>=' );
	if ( file_exists( WP_CONTENT_DIR . '/db.php' ) && empty( $wpdb->is_mysql ) ) {
		$mysql_compat = true;
	} else {
		$mysql_compat = version_compare( $mysql_version, $required_mysql_version, '>=' );
	}

	if ( ! $mysql_compat || ! $php_compat ) {
		$wp_filesystem->delete( $from, true );
	}

	$php_update_message = '';
	if ( function_exists( 'wp_get_update_php_url' ) ) {
		/* translators: %s: URL to Update PHP page. */
		$php_update_message = '</p><p>' . sprintf( __( '<a href="%s">Learn more about updating PHP</a>.' ), esc_url( wp_get_update_php_url() ) );

		if ( function_exists( 'wp_get_update_php_annotation' ) ) {
			$annotation = wp_get_update_php_annotation();
			if ( $annotation ) {
				$php_update_message .= '</p><p><em>' . $annotation . '</em>';
			}
		}
	}

	if ( ! $mysql_compat && ! $php_compat ) {
		return new WP_Error(
			'php_mysql_not_compatible',
			sprintf(
				/* translators: 1: WordPress version number, 2: Minimum required PHP version number, 3: Minimum required MySQL version number, 4: Current PHP version number, 5: Current MySQL version number. */
				__( 'The update cannot be installed because WordPress %1$s requires PHP version %2$s or higher and MySQL version %3$s or higher. You are running PHP version %4$s and MySQL version %5$s.' ),
				$wp_version,
				$required_php_version,
				$required_mysql_version,
				$php_version,
				$mysql_version
			) . $php_update_message
		);
	} elseif ( ! $php_compat ) {
		return new WP_Error(
			'php_not_compatible',
			sprintf(
				/* translators: 1: WordPress version number, 2: Minimum required PHP version number, 3: Current PHP version number. */
				__( 'The update cannot be installed because WordPress %1$s requires PHP version %2$s or higher. You are running version %3$s.' ),
				$wp_version,
				$required_php_version,
				$php_version
			) . $php_update_message
		);
	} elseif ( ! $mysql_compat ) {
		return new WP_Error(
			'mysql_not_compatible',
			sprintf(
				/* translators: 1: WordPress version number, 2: Minimum required MySQL version number, 3: Current MySQL version number. */
				__( 'The update cannot be installed because WordPress %1$s requires MySQL version %2$s or higher. You are running version %3$s.' ),
				$wp_version,
				$required_mysql_version,
				$mysql_version
			)
		);
	}

	// Add a warning when the JSON PHP extension is missing.
	if ( ! extension_loaded( 'json' ) ) {
		return new WP_Error(
			'php_not_compatible_json',
			sprintf(
				/* translators: 1: WordPress version number, 2: The PHP extension name needed. */
				__( 'The update cannot be installed because WordPress %1$s requires the %2$s PHP extension.' ),
				$wp_version,
				'JSON'
			)
		);
	}

	/** This filter is documented in ADMIN_DIR/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Preparing to install the latest version&#8230;' ) );

	// Don't copy WP_CONTENT_DIR, we'll deal with that below.
	// We also copy version.php last so failed updates report their old version.
	$skip              = array( WP_CONTENT_DIR, WPINC . '/version.php' );
	$check_is_writable = array();

	// Check to see which files don't really need updating - only available for 3.7 and higher.
	if ( function_exists( 'get_core_checksums' ) ) {
		// Find the local version of the working directory.
		$working_dir_local = WP_CONTENT_DIR . '/upgrade/' . basename( $from ) . $distro;

		$checksums = get_core_checksums( $wp_version, isset( $wp_local_package ) ? $wp_local_package : 'en_US' );
		if ( is_array( $checksums ) && isset( $checksums[ $wp_version ] ) ) {
			$checksums = $checksums[ $wp_version ]; // Compat code for 3.7-beta2.
		}
		if ( is_array( $checksums ) ) {
			foreach ( $checksums as $file => $checksum ) {
				if ( WP_CONTENT_DIR == substr( $file, 0, 10 ) ) {
					continue;
				}
				if ( ! file_exists( ABSPATH . $file ) ) {
					continue;
				}
				if ( ! file_exists( $working_dir_local . $file ) ) {
					continue;
				}
				if ( '.' === dirname( $file ) && in_array( pathinfo( $file, PATHINFO_EXTENSION ), array( 'html', 'txt' ) ) ) {
					continue;
				}
				if ( md5_file( ABSPATH . $file ) === $checksum ) {
					$skip[] = $file;
				} else {
					$check_is_writable[ $file ] = ABSPATH . $file;
				}
			}
		}
	}

	// If we're using the direct method, we can predict write failures that are due to permissions.
	if ( $check_is_writable && 'direct' === $wp_filesystem->method ) {
		$files_writable = array_filter( $check_is_writable, array( $wp_filesystem, 'is_writable' ) );
		if ( $files_writable !== $check_is_writable ) {
			$files_not_writable = array_diff_key( $check_is_writable, $files_writable );
			foreach ( $files_not_writable as $relative_file_not_writable => $file_not_writable ) {
				// If the writable check failed, chmod file to 0644 and try again, same as copy_dir().
				$wp_filesystem->chmod( $file_not_writable, FS_CHMOD_FILE );
				if ( $wp_filesystem->is_writable( $file_not_writable ) ) {
					unset( $files_not_writable[ $relative_file_not_writable ] );
				}
			}

			// Store package-relative paths (the key) of non-writable files in the WP_Error object.
			$error_data = version_compare( $old_wp_version, '3.7-beta2', '>' ) ? array_keys( $files_not_writable ) : '';

			if ( $files_not_writable ) {
				return new WP_Error( 'files_not_writable', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), implode( ', ', $error_data ) );
			}
		}
	}

	/** This filter is documented in ADMIN_DIR/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Enabling Maintenance mode&#8230;' ) );
	// Create maintenance file to signal that we are upgrading.
	$maintenance_string = '<?php $upgrading = ' . time() . '; ?>';
	$maintenance_file   = $to . '.maintenance';
	$wp_filesystem->delete( $maintenance_file );
	$wp_filesystem->put_contents( $maintenance_file, $maintenance_string, FS_CHMOD_FILE );

	/** This filter is documented in ADMIN_DIR/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Copying the required files&#8230;' ) );
	// Copy new versions of WP files into place.
	$result = _copy_dir( $from . $distro, $to, $skip );
	if ( is_wp_error( $result ) ) {
		$result = new WP_Error( $result->get_error_code(), $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );
	}

	// Since we know the core files have copied over, we can now copy the version file.
	if ( ! is_wp_error( $result ) ) {
		if ( ! $wp_filesystem->copy( $from . $distro . WPINC . '/version.php', $to . WPINC . '/version.php', true /* overwrite */ ) ) {
			$wp_filesystem->delete( $from, true );
			$result = new WP_Error( 'copy_failed_for_version_file', __( 'The update cannot be installed because we will be unable to copy some files. This is usually due to inconsistent file permissions.' ), WPINC . '/version.php' );
		}
		$wp_filesystem->chmod( $to . WPINC . '/version.php', FS_CHMOD_FILE );
	}

	// Check to make sure everything copied correctly, ignoring the contents of WP_CONTENT_DIR.
	$skip   = array( WP_CONTENT_DIR );
	$failed = array();
	if ( isset( $checksums ) && is_array( $checksums ) ) {
		foreach ( $checksums as $file => $checksum ) {
			if ( WP_CONTENT_DIR == substr( $file, 0, 10 ) ) {
				continue;
			}
			if ( ! file_exists( $working_dir_local . $file ) ) {
				continue;
			}
			if ( '.' === dirname( $file ) && in_array( pathinfo( $file, PATHINFO_EXTENSION ), array( 'html', 'txt' ) ) ) {
				$skip[] = $file;
				continue;
			}
			if ( file_exists( ABSPATH . $file ) && md5_file( ABSPATH . $file ) == $checksum ) {
				$skip[] = $file;
			} else {
				$failed[] = $file;
			}
		}
	}

	// Some files didn't copy properly.
	if ( ! empty( $failed ) ) {
		$total_size = 0;
		foreach ( $failed as $file ) {
			if ( file_exists( $working_dir_local . $file ) ) {
				$total_size += filesize( $working_dir_local . $file );
			}
		}

		// If we don't have enough free space, it isn't worth trying again.
		// Unlikely to be hit due to the check in unzip_file().
		$available_space = @disk_free_space( ABSPATH );
		if ( $available_space && $total_size >= $available_space ) {
			$result = new WP_Error( 'disk_full', __( 'There is not enough free disk space to complete the update.' ) );
		} else {
			$result = _copy_dir( $from . $distro, $to, $skip );
			if ( is_wp_error( $result ) ) {
				$result = new WP_Error( $result->get_error_code() . '_retry', $result->get_error_message(), substr( $result->get_error_data(), strlen( $to ) ) );
			}
		}
	}

	// Custom content directory needs updating now.
	// Copy languages.
	if ( ! is_wp_error( $result ) && $wp_filesystem->is_dir( $from . $distro . WP_CONTENT_DIR . '/languages' ) ) {
		if ( WP_LANG_DIR != ABSPATH . WPINC . '/languages' || @is_dir( WP_LANG_DIR ) ) {
			$lang_dir = WP_LANG_DIR;
		} else {
			$lang_dir = WP_CONTENT_DIR . '/languages';
		}

		// Check if the language directory exists first.
		if ( ! @is_dir( $lang_dir ) && 0 === strpos( $lang_dir, ABSPATH ) ) {
			// If it's within the ABSPATH we can handle it here, otherwise they're out of luck.
			$wp_filesystem->mkdir( $to . str_replace( ABSPATH, '', $lang_dir ), FS_CHMOD_DIR );
			clearstatcache(); // For FTP, need to clear the stat cache.
		}

		if ( @is_dir( $lang_dir ) ) {
			$wp_lang_dir = $wp_filesystem->find_folder( $lang_dir );
			if ( $wp_lang_dir ) {
				$result = copy_dir( $from . $distro . WP_CONTENT_DIR .'/languages/', $wp_lang_dir );
				if ( is_wp_error( $result ) ) {
					$result = new WP_Error( $result->get_error_code() . '_languages', $result->get_error_message(), substr( $result->get_error_data(), strlen( $wp_lang_dir ) ) );
				}
			}
		}
	}

	/** This filter is documented in ADMIN_DIR/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Disabling Maintenance mode&#8230;' ) );
	// Remove maintenance file, we're done with potential site-breaking changes.
	$wp_filesystem->delete( $maintenance_file );

	// 3.5 -> 3.5+ - an empty twentytwelve directory was created upon upgrade to 3.5 for some users, preventing installation of Twenty Twelve.
	if ( '3.5' == $old_wp_version ) {
		if ( is_dir( WP_CONTENT_DIR . '/themes/twentytwelve' ) && ! file_exists( WP_CONTENT_DIR . '/themes/twentytwelve/style.css' ) ) {
			$wp_filesystem->delete( $wp_filesystem->wp_themes_dir() . 'twentytwelve/' );
		}
	}

	/*
	 * Copy new bundled plugins & themes.
	 * This gives us the ability to install new plugins & themes bundled with
	 * future versions of WordPress whilst avoiding the re-install upon upgrade issue.
	 * $development_build controls us overwriting bundled themes and plugins when a non-stable release is being updated.
	 */
	if ( ! is_wp_error( $result ) && ( ! defined( 'CORE_UPGRADE_SKIP_NEW_BUNDLED' ) || ! CORE_UPGRADE_SKIP_NEW_BUNDLED ) ) {
		foreach ( (array) $_new_bundled_files as $file => $introduced_version ) {
			// If a $development_build or if $introduced version is greater than what the site was previously running.
			if ( $development_build || version_compare( $introduced_version, $old_wp_version, '>' ) ) {
				$directory             = ( '/' == $file[ strlen( $file ) - 1 ] );
				list($type, $filename) = explode( '/', $file, 2 );

				// Check to see if the bundled items exist before attempting to copy them.
				if ( ! $wp_filesystem->exists( $from . $distro . WP_CONTENT_DIR . '/' . $file ) ) {
					continue;
				}

				if ( 'plugins' == $type ) {
					$dest = $wp_filesystem->wp_plugins_dir();
				} elseif ( 'themes' == $type ) {
					// Back-compat, ::wp_themes_dir() did not return trailingslash'd pre-3.2.
					$dest = trailingslashit( $wp_filesystem->wp_themes_dir() );
				} else {
					continue;
				}

				if ( ! $directory ) {
					if ( ! $development_build && $wp_filesystem->exists( $dest . $filename ) ) {
						continue;
					}

					if ( ! $wp_filesystem->copy( $from . $distro . WP_CONTENT_DIR . '/' . $file, $dest . $filename, FS_CHMOD_FILE ) ) {
						$result = new WP_Error( "copy_failed_for_new_bundled_$type", __( 'Could not copy file.' ), $dest . $filename );
					}
				} else {
					if ( ! $development_build && $wp_filesystem->is_dir( $dest . $filename ) ) {
						continue;
					}

					$wp_filesystem->mkdir( $dest . $filename, FS_CHMOD_DIR );
					$_result = copy_dir( $from . $distro . WP_CONTENT_DIR . '/' . $file, $dest . $filename );

					// If a error occurs partway through this final step, keep the error flowing through, but keep process going.
					if ( is_wp_error( $_result ) ) {
						if ( ! is_wp_error( $result ) ) {
							$result = new WP_Error;
						}
						$result->add( $_result->get_error_code() . "_$type", $_result->get_error_message(), substr( $_result->get_error_data(), strlen( $dest ) ) );
					}
				}
			}
		} // End foreach.
	}

	// Handle $result error from the above blocks.
	if ( is_wp_error( $result ) ) {
		$wp_filesystem->delete( $from, true );
		return $result;
	}

	// Remove old files.
	foreach ( $_old_files as $old_file ) {
		$old_file = $to . $old_file;
		if ( ! $wp_filesystem->exists( $old_file ) ) {
			continue;
		}

		// If the file isn't deleted, try writing an empty string to the file instead.
		if ( ! $wp_filesystem->delete( $old_file, true ) && $wp_filesystem->is_file( $old_file ) ) {
			$wp_filesystem->put_contents( $old_file, '' );
		}
	}

	// Remove any Genericons example.html's from the filesystem.
	_upgrade_422_remove_genericons();

	// Remove the REST API plugin if its version is Beta 4 or lower.
	_upgrade_440_force_deactivate_incompatible_plugins();

	// Upgrade DB with separate request.
	/** This filter is documented in ADMIN_DIR/includes/update-core.php */
	apply_filters( 'update_feedback', __( 'Upgrading database&#8230;' ) );
	$db_upgrade_url = admin_url( 'upgrade.php?step=upgrade_db' );
	wp_remote_post( $db_upgrade_url, array( 'timeout' => 60 ) );

	// Clear the cache to prevent an update_option() from saving a stale db_version to the cache.
	wp_cache_flush();
	// Not all cache back ends listen to 'flush'.
	wp_cache_delete( 'alloptions', 'options' );

	// Remove working directory.
	$wp_filesystem->delete( $from, true );

	// Force refresh of update information.
	if ( function_exists( 'delete_site_transient' ) ) {
		delete_site_transient( 'update_core' );
	} else {
		delete_option( 'update_core' );
	}

	/**
	 * Fires after WordPress core has been successfully updated.
	 *
	 * @since 3.3.0
	 *
	 * @param string $wp_version The current WordPress version.
	 */
	do_action( '_core_updated_successfully', $wp_version );

	// Clear the option that blocks auto updates after failures, now that we've been successful.
	if ( function_exists( 'delete_site_option' ) ) {
		delete_site_option( 'auto_core_update_failed' );
	}

	return $wp_version;
}

/**
 * Copies a directory from one location to another via the WordPress Filesystem Abstraction.
 * Assumes that WP_Filesystem() has already been called and setup.
 *
 * This is a temporary function for the 3.1 -> 3.2 upgrade, as well as for those upgrading to
 * 3.7+
 *
 * @ignore
 * @since 3.2.0
 * @since 3.7.0 Updated not to use a regular expression for the skip list
 * @see copy_dir()
 *
 * @global WP_Filesystem_Base $wp_filesystem
 *
 * @param string   $from      Source directory.
 * @param string   $to        Destination directory.
 * @param string[] $skip_list Array of files/folders to skip copying.
 * @return true|WP_Error True on success, WP_Error on failure.
 */
function _copy_dir( $from, $to, $skip_list = array() ) {
	global $wp_filesystem;

	$dirlist = $wp_filesystem->dirlist( $from );

	$from = trailingslashit( $from );
	$to   = trailingslashit( $to );

	foreach ( (array) $dirlist as $filename => $fileinfo ) {
		if ( in_array( $filename, $skip_list, true ) ) {
			continue;
		}

		if ( 'f' == $fileinfo['type'] ) {
			if ( ! $wp_filesystem->copy( $from . $filename, $to . $filename, true, FS_CHMOD_FILE ) ) {
				// If copy failed, chmod file to 0644 and try again.
				$wp_filesystem->chmod( $to . $filename, FS_CHMOD_FILE );
				if ( ! $wp_filesystem->copy( $from . $filename, $to . $filename, true, FS_CHMOD_FILE ) ) {
					return new WP_Error( 'copy_failed__copy_dir', __( 'Could not copy file.' ), $to . $filename );
				}
			}
		} elseif ( 'd' == $fileinfo['type'] ) {
			if ( ! $wp_filesystem->is_dir( $to . $filename ) ) {
				if ( ! $wp_filesystem->mkdir( $to . $filename, FS_CHMOD_DIR ) ) {
					return new WP_Error( 'mkdir_failed__copy_dir', __( 'Could not create directory.' ), $to . $filename );
				}
			}

			/*
			 * Generate the $sub_skip_list for the subdirectory as a sub-set
			 * of the existing $skip_list.
			 */
			$sub_skip_list = array();
			foreach ( $skip_list as $skip_item ) {
				if ( 0 === strpos( $skip_item, $filename . '/' ) ) {
					$sub_skip_list[] = preg_replace( '!^' . preg_quote( $filename, '!' ) . '/!i', '', $skip_item );
				}
			}

			$result = _copy_dir( $from . $filename, $to . $filename, $sub_skip_list );
			if ( is_wp_error( $result ) ) {
				return $result;
			}
		}
	}
	return true;
}

/**
 * Redirect to the About WordPress page after a successful upgrade.
 *
 * This function is only needed when the existing installation is older than 3.4.0.
 *
 * @since 3.3.0
 *
 * @global string $wp_version The WordPress version string.
 * @global string $pagenow
 * @global string $action
 *
 * @param string $new_version
 */
function _redirect_to_about_wordpress( $new_version ) {
	global $wp_version, $pagenow, $action;

	if ( version_compare( $wp_version, '3.4-RC1', '>=' ) ) {
		return;
	}

	// Ensure we only run this on the update-core.php page. The Core_Upgrader may be used in other contexts.
	if ( 'update-core.php' != $pagenow ) {
		return;
	}

	if ( 'do-core-upgrade' != $action && 'do-core-reinstall' != $action ) {
		return;
	}

	// Load the updated default text localization domain for new strings.
	load_default_textdomain();

	// See do_core_upgrade().
	show_message( __( 'WordPress updated successfully' ) );

	// self_admin_url() won't exist when upgrading from <= 3.0, so relative URLs are intentional.
	show_message(
		'<span class="hide-if-no-js">' . sprintf(
			/* translators: 1: WordPress version, 2: URL to About screen. */
			__( 'Welcome to WordPress %1$s. You will be redirected to the About WordPress screen. If not, click <a href="%2$s">here</a>.' ),
			$new_version,
			'about.php?updated'
		) . '</span>'
	);
	show_message(
		'<span class="hide-if-js">' . sprintf(
			/* translators: 1: WordPress version, 2: URL to About screen. */
			__( 'Welcome to WordPress %1$s. <a href="%2$s">Learn more</a>.' ),
			$new_version,
			'about.php?updated'
		) . '</span>'
	);
	echo '</div>';
	?>
<script type="text/javascript">
window.location = 'about.php?updated';
</script>
	<?php

	// Include admin-footer.php and exit.
	require_once ABSPATH . ADMIN_DIR . '/admin-footer.php';
	exit();
}

/**
 * Cleans up Genericons example files.
 *
 * @since 4.2.2
 *
 * @global array              $wp_theme_directories
 * @global WP_Filesystem_Base $wp_filesystem
 */
function _upgrade_422_remove_genericons() {
	global $wp_theme_directories, $wp_filesystem;

	// A list of the affected files using the filesystem absolute paths.
	$affected_files = array();

	// Themes.
	foreach ( $wp_theme_directories as $directory ) {
		$affected_theme_files = _upgrade_422_find_genericons_files_in_folder( $directory );
		$affected_files       = array_merge( $affected_files, $affected_theme_files );
	}

	// Plugins.
	$affected_plugin_files = _upgrade_422_find_genericons_files_in_folder( WP_PLUGIN_DIR );
	$affected_files        = array_merge( $affected_files, $affected_plugin_files );

	foreach ( $affected_files as $file ) {
		$gen_dir = $wp_filesystem->find_folder( trailingslashit( dirname( $file ) ) );
		if ( empty( $gen_dir ) ) {
			continue;
		}

		// The path when the file is accessed via WP_Filesystem may differ in the case of FTP.
		$remote_file = $gen_dir . basename( $file );

		if ( ! $wp_filesystem->exists( $remote_file ) ) {
			continue;
		}

		if ( ! $wp_filesystem->delete( $remote_file, false, 'f' ) ) {
			$wp_filesystem->put_contents( $remote_file, '' );
		}
	}
}

/**
 * Recursively find Genericons example files in a given folder.
 *
 * @ignore
 * @since 4.2.2
 *
 * @param string $directory Directory path. Expects trailingslashed.
 * @return array
 */
function _upgrade_422_find_genericons_files_in_folder( $directory ) {
	$directory = trailingslashit( $directory );
	$files     = array();

	if ( file_exists( "{$directory}example.html" ) && false !== strpos( file_get_contents( "{$directory}example.html" ), '<title>Genericons</title>' ) ) {
		$files[] = "{$directory}example.html";
	}

	$dirs = glob( $directory . '*', GLOB_ONLYDIR );
	if ( $dirs ) {
		foreach ( $dirs as $dir ) {
			$files = array_merge( $files, _upgrade_422_find_genericons_files_in_folder( $dir ) );
		}
	}

	return $files;
}

/**
 * @ignore
 * @since 4.4.0
 */
function _upgrade_440_force_deactivate_incompatible_plugins() {
	if ( defined( 'REST_API_VERSION' ) && version_compare( REST_API_VERSION, '2.0-beta4', '<=' ) ) {
		deactivate_plugins( array( 'rest-api/plugin.php' ), true );
	}
}
