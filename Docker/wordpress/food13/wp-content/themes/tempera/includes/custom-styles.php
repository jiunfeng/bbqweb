<?php
////////// MASTER CUSTOM STYLE FUNCTION //////////

function tempera_body_classes($classes) {
	$temperas = tempera_get_theme_options();
	$classes[] = $temperas['tempera_image_style'];
	$classes[] = $temperas['tempera_caption'];

	$magazine_layout = FALSE;
	if ($temperas['tempera_magazinelayout'] == "Enable") {
		if (is_front_page()) {
			if ( ($temperas['tempera_frontpage'] == "Enable") && (intval($temperas['tempera_frontpostsperrow']) == 1) ) { /* no magazine layout */ }
																											 else { $magazine_layout = TRUE; }
		} else {
			$magazine_layout = TRUE;
		}
	}
	if ( is_front_page() && ($temperas['tempera_frontpage'] == "Enable") && (intval($temperas['tempera_frontpostsperrow']) == 2) ) { $magazine_layout = TRUE; }

	if ($magazine_layout) $classes[] = 'magazine-layout';

	if ( is_front_page() && $temperas['tempera_frontpage'] == "Enable" && (get_option('show_on_front') == 'posts') ) {
		$classes[] = 'presentation-page';
		$classes[] = 'coldisplay'.$temperas['tempera_coldisplay'];
	}
	
	if ($temperas['tempera_comlabels'] == 0) $classes[] = 'tempera-comment-placeholders';

	switch ($temperas['tempera_menualign']):
		case "center": 		$classes[] = 'tempera-menu-center'; break;
		case "right":  		$classes[] = 'tempera-menu-right'; break;
		case "rightmulti": 	$classes[] = 'tempera-menu-rightmulti'; break;
		default: 			$classes[] = 'tempera-menu-left'; break;
	  endswitch;

	switch ($temperas['tempera_topbar']):
		case "Fixed": 		$classes[] = 'tempera-topbarfixed'; break;
		case "Hide":  		$classes[] = 'tempera-topbarhide'; break;
		case "Normal": default: break;
	endswitch;

	if ($temperas['tempera_topbarwidth'] == 'Full width') $classes[] = 'tempera-topbarfull';

	return $classes;
}
add_filter('body_class','tempera_body_classes');

function tempera_custom_styles() {
	$temperas = tempera_get_theme_options();
	extract ($temperas);
	$totalwidth = intval($tempera_sidewidth) + intval($tempera_sidebar);
	$contentSize = intval($tempera_sidewidth);
	$sidebarSize = intval($tempera_sidebar);
	ob_start(); 
	
////////// LAYOUT DIMENSIONS. ////////// 
?>
#header, #main, #topbar-inner { <?php echo (($tempera_mobile == 'Enable') ? 'max-' : '');?>width: <?php echo ($totalwidth); ?>px; }
<?php
////////// COLUMNS //////////

$colPadding = $tempera_contentpadding;

?>
#container.one-column { }
#container.two-columns-right #secondary { width:<?php echo $sidebarSize; ?>px; float:right; }
#container.two-columns-right #content { width:calc(100% - <?php echo $sidebarSize + $colPadding; ?>px); float:left; }

#container.two-columns-left #primary { width:<?php echo $sidebarSize; ?>px; float:left; }
#container.two-columns-left #content { 	width:calc(100% - <?php echo $sidebarSize + $colPadding; ?>px); float:right; }

#container.three-columns-right .sidey { width:<?php echo $sidebarSize/2; ?>px; float:left; }
#container.three-columns-right #primary { margin-left:<?php echo $colPadding/2; ?>px; margin-right:<?php echo $colPadding/2; ?>px; }
#container.three-columns-right #content { width: calc(100% - <?php echo $sidebarSize+$colPadding; ?>px); float:left;}

#container.three-columns-left .sidey { width:<?php echo $sidebarSize/2; ?>px; float:left; }
#container.three-columns-left #secondary {margin-left:<?php echo $colPadding/2; ?>px; margin-right:<?php echo $colPadding/2; ?>px; }
#container.three-columns-left #content { width: calc(100% - <?php echo $sidebarSize+$colPadding; ?>px); float:right; }

#container.three-columns-sided .sidey { width:<?php echo $sidebarSize/2; ?>px; float:left; }
#container.three-columns-sided #secondary { float:right; }
#container.three-columns-sided #content { width: calc(100% - <?php echo $sidebarSize+$colPadding*2; ?>px); float:right;
		                                  margin: 0 <?php echo ($sidebarSize/2)+$colPadding;?>px 0 <?php echo -($contentSize+$sidebarSize); ?>px; }
<?php
////////// FONTS //////////
$tempera_googlefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_googlefont));
$tempera_googlefonttitle = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_googlefonttitle));
$tempera_googlefontside = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_googlefontside));
$tempera_headingsgooglefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_headingsgooglefont));
$tempera_sitetitlegooglefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_sitetitlegooglefont));
$tempera_menugooglefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_menugooglefont));
$tempera_fontfamily = cryout_fontname_cleanup($tempera_fontfamily);
$tempera_fonttitle = cryout_fontname_cleanup($tempera_fonttitle);
$tempera_fontside = cryout_fontname_cleanup($tempera_fontside);
$tempera_sitetitlefont = cryout_fontname_cleanup($tempera_sitetitlefont);
$tempera_menufont = cryout_fontname_cleanup($tempera_menufont);
$tempera_headingsfont = cryout_fontname_cleanup($tempera_headingsfont);
?>
body {  font-family: <?php echo (empty($tempera_googlefont)?$tempera_fontfamily:sprintf('"%s"', $tempera_googlefont)); ?>; }
#content h1.entry-title a, #content h2.entry-title a, 
#content h1.entry-title , #content h2.entry-title {
		font-family: <?php echo (empty($tempera_googlefonttitle)?str_replace('font-general', 'inherit', $tempera_fonttitle):sprintf('"%s"', $tempera_googlefonttitle)); ?>; }
.widget-title, .widget-title a { 
		font-family: <?php echo (empty($tempera_googlefontside)?str_replace('font-general', 'inherit', $tempera_fontside):sprintf('"%s"', $tempera_googlefontside)); ?>; }
.entry-content h1, .entry-content h2, .entry-content h3,
.entry-content h4, .entry-content h5, .entry-content h6,
#comments #reply-title, .nivo-caption h2, #front-text1 h2, #front-text2 h2,
.column-header-image, .column-header-noimage {
		font-family: <?php echo (empty($tempera_headingsgooglefont)?str_replace('font-general', 'inherit', $tempera_headingsfont):sprintf('"%s"', $tempera_headingsgooglefont)); ?>; }
#site-title span a {
		font-family: <?php echo (empty($tempera_sitetitlegooglefont)?str_replace('font-general', 'inherit', $tempera_sitetitlefont):sprintf('"%s"', $tempera_sitetitlegooglefont)); ?>; }
#access ul li a, #access ul li a span {
		font-family: <?php echo (empty($tempera_menugooglefont)?str_replace('font-general', 'inherit', $tempera_menufont):sprintf('"%s"', $tempera_menugooglefont)); ?>; }

<?php
////////// COLORS //////////
?>
body { color: <?php echo $tempera_contentcolortxt; ?>; background-color: <?php echo $tempera_backcolormain; ?> }
a { color: <?php echo $tempera_linkcolortext; ?>; }
a:hover,.entry-meta span a:hover, .comments-link a:hover { color: <?php echo $tempera_linkcolorhover; ?>; }
#header { background-color: <?php echo $tempera_backcolorheader; ?>; }
#site-title span a { color:<?php echo $tempera_titlecolor; ?>; }
#site-description { color:<?php echo $tempera_descriptioncolor; ?>; <?php if(cryout_hex2rgb($tempera_descriptionbg)): ?>background-color: rgba(<?php echo cryout_hex2rgb($tempera_descriptionbg); ?>,0.3); padding-left: 6px; <?php endif; ?>}

.socials a { background-color: <?php echo $tempera_socialcolorbg; ?>; }
.socials .socials-hover { background-color: <?php echo $tempera_socialcolorbghover; ?>; }
/* Main menu top level */
#access a, #nav-toggle span, li.menu-main-search .searchform input[type="search"] { color: <?php echo $tempera_menucolortxtdefault; ?>; }
li.menu-main-search .searchform input[type="search"] { background-color: <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>; border-left-color: <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; }
#access, #nav-toggle {background-color: <?php echo $tempera_menucolorbgdefault; ?>; }
#access > .menu > ul > li > a > span { border-color: <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
-webkit-box-shadow: 1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>;
box-shadow: 1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>; }
/*.rtl #access > .menu > ul > li > a > span { -webkit-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>;
box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>; } */
#access a:hover {background-color: <?php echo cryout_hexadder($tempera_menucolorbgdefault,'13');?>; }
#access ul li.current_page_item > a, #access ul li.current-menu-item > a,
#access ul li.current_page_ancestor > a, #access ul li.current-menu-ancestor > a {
       background-color: <?php echo cryout_hexadder($tempera_menucolorbgdefault,'13');?>; }
/* Main menu Submenus */
#access > .menu > ul > li > ul:before {border-bottom-color:<?php echo $tempera_submenucolorbgdefault; ?>;}
#access ul ul ul li:first-child:before { border-right-color:<?php echo $tempera_submenucolorbgdefault; ?>;}
#access ul ul li {
background-color:<?php echo $tempera_submenucolorbgdefault; ?>;
border-top-color:<?php echo cryout_hexadder($tempera_submenucolorbgdefault,'14');?>;
border-bottom-color:<?php echo cryout_hexadder($tempera_submenucolorbgdefault,'-11');?>
}
#access ul ul li a{color:<?php echo $tempera_submenucolortxtdefault; ?>}
#access ul ul li a:hover{background:<?php echo cryout_hexadder($tempera_submenucolorbgdefault,'14');?>}
#access ul ul li.current_page_item > a, #access ul ul li.current-menu-item > a,
#access ul ul li.current_page_ancestor > a, #access ul ul li.current-menu-ancestor > a  {
background-color:<?php echo cryout_hexadder($tempera_submenucolorbgdefault,'14');?>; }
<?php if (cryout_hex2rgb($tempera_submenucolorshadow)): ?>#access ul ul { box-shadow: 3px 3px 0 rgba(<?php echo cryout_hex2rgb($tempera_submenucolorshadow); ?>,0.3); }<?php endif; ?>

#topbar {
	background-color:  <?php echo $tempera_topbarcolorbg; ?>;border-bottom-color:<?php echo cryout_hexadder($tempera_topbarcolorbg,'40');?>;
	box-shadow:3px 0 3px <?php echo cryout_hexadder($tempera_topbarcolorbg,'-40');?>;
}
.topmenu ul li a, .topmenu .searchsubmit { color: <?php echo $tempera_topmenucolortxt; ?>; }
.topmenu ul li a:hover, .topmenu .searchform input[type="search"] { color: <?php echo $tempera_topmenucolortxthover; ?>; border-bottom-color: rgba( <?php echo cryout_hex2rgb( $tempera_accentcolora ); ?>, 0.5); }

#main { background-color: <?php echo $tempera_contentcolorbg; ?>; }
#author-info, #entry-author-info, #content .page-title { border-color: <?php echo $tempera_accentcolora; ?>; background: <?php echo $tempera_accentcolore; ?>; }
#entry-author-info #author-avatar, #author-info #author-avatar { border-color: <?php echo $tempera_accentcolorc; ?>; }

.sidey .widget-container { color: <?php echo $tempera_sidetxt; ?>; background-color: <?php echo $tempera_sidebg; ?>; }
.sidey .widget-title { color: <?php echo $tempera_sidetitletxt; ?>; background-color: <?php echo $tempera_sidetitlebg; ?>;border-color:<?php echo cryout_hexadder($tempera_sidetitlebg,'-40');?>;}
.sidey .widget-container a {color:<?php echo $tempera_linkcolorside;?>;}
.sidey .widget-container a:hover {color:<?php echo $tempera_linkcolorsidehover;?>;}

.entry-content h1, .entry-content h2, .entry-content h3, .entry-content h4, .entry-content h5, .entry-content h6 {
     color: <?php echo $tempera_contentcolortxtheadings; ?>; }
 .sticky .entry-header {border-color:<?php echo $tempera_accentcolora; ?> }
.entry-title, .entry-title a { color: <?php echo $tempera_contentcolortxttitle; ?>; }
.entry-title a:hover { color: <?php echo $tempera_contentcolortxttitlehover; ?>; }
#content span.entry-format { color: <?php echo $tempera_menucolortxtdefault; ?>; background-color: <?php echo $tempera_menucolorbgdefault; ?>; }

#footer { color: <?php echo $tempera_footercolortxt; ?>; background-color: <?php echo $tempera_backcolorfooterw; ?>; }
#footer2 { color: <?php echo $tempera_footercolortxt; ?>; background-color: <?php echo $tempera_backcolorfooter; ?>;  }
#footer a { color: <?php echo $tempera_linkcolorwooter; ?>; }
#footer a:hover { color: <?php echo $tempera_linkcolorwooterhover; ?>; }
#footer2 a, .footermenu ul li:after  { color: <?php echo $tempera_linkcolorfooter; ?>; }
#footer2 a:hover { color: <?php echo $tempera_linkcolorfooterhover; ?>; }
#footer .widget-container { color: <?php echo $tempera_widgettxt; ?>; background-color: <?php echo $tempera_widgetbg; ?>; }
#footer .widget-title { color: <?php echo $tempera_widgettitletxt; ?>; background-color: <?php echo $tempera_widgettitlebg; ?>;border-color:<?php echo cryout_hexadder($tempera_widgettitlebg,'-40');?> }

a.continue-reading-link, #cryout_ajax_more_trigger { color:<?php echo $tempera_menucolortxtdefault; ?>; background:<?php echo $tempera_menucolorbgdefault; ?>; border-bottom-color:<?php echo $tempera_accentcolora; ?>; }
a.continue-reading-link:hover { border-bottom-color:<?php echo $tempera_accentcolorb; ?>; }
a.continue-reading-link i.crycon-right-dir {color:<?php echo $tempera_accentcolora; ?>}
a.continue-reading-link:hover i.crycon-right-dir {color:<?php echo $tempera_accentcolorb; ?>}
.page-link a, .page-link > span > em {border-color:<?php echo $tempera_accentcolord;?>}

.columnmore a {background:<?php echo $tempera_accentcolora;?>;color:<?php echo $tempera_accentcolore; ?>}
.columnmore a:hover {background:<?php echo $tempera_accentcolorb;?>;}

.button, #respond .form-submit input#submit, input[type="submit"], input[type="reset"] {
	background-color: <?php echo $tempera_accentcolora; ?>; }
.button:hover, #respond .form-submit input#submit:hover {
	background-color: <?php echo $tempera_accentcolorb; ?>; }
.entry-content tr th, .entry-content thead th {
	color: <?php echo $tempera_contentcolortxtheadings; ?>; }
.entry-content table, .entry-content fieldset, .entry-content tr td, .entry-content tr th, .entry-content thead th { border-color: <?php echo $tempera_accentcolord; ?>; }
.entry-content tr.even td { background-color: <?php echo $tempera_accentcolore; ?> !important; }
hr { border-color: <?php echo $tempera_accentcolord; ?>; } 
input[type="text"], input[type="password"], input[type="email"], textarea, select,
input[type="color"],input[type="date"],input[type="datetime"],input[type="datetime-local"],input[type="month"],input[type="number"],input[type="range"],
input[type="search"],input[type="tel"],input[type="time"],input[type="url"],input[type="week"] {
	background-color: <?php echo $tempera_accentcolore; ?>;
    border-color: <?php echo $tempera_accentcolord; ?> <?php echo $tempera_accentcolorc; ?> <?php echo $tempera_accentcolorc; ?> <?php echo $tempera_accentcolord; ?>;
	color: <?php echo $tempera_contentcolortxt; ?>; }
input[type="submit"], input[type="reset"] {
	color: <?php echo $tempera_contentcolorbg; ?>;
	background-color: <?php echo $tempera_accentcolora; ?>; }
input[type="text"]:hover, input[type="password"]:hover, input[type="email"]:hover, textarea:hover,
input[type="color"]:hover, input[type="date"]:hover, input[type="datetime"]:hover, input[type="datetime-local"]:hover, input[type="month"]:hover, input[type="number"]:hover, input[type="range"]:hover,
input[type="search"]:hover, input[type="tel"]:hover, input[type="time"]:hover, input[type="url"]:hover, input[type="week"]:hover {
	<?php if(cryout_hex2rgb($tempera_accentcolore)): ?>background-color: rgba(<?php echo cryout_hex2rgb($tempera_accentcolore); ?>,0.4); <?php endif; ?> }
.entry-content pre {
	border-color: <?php echo $tempera_accentcolord; ?>;
	border-bottom-color:<?php echo $tempera_accentcolora ;?>;}
.entry-content code { background-color:<?php echo $tempera_accentcolore; ?>;}
.entry-content blockquote {
	border-color: <?php echo $tempera_accentcolorc; ?>; }
abbr, acronym { border-color: <?php echo $tempera_contentcolortxt; ?>; }
.comment-meta a { color: <?php echo $tempera_contentcolortxt; ?>; }
#respond .form-allowed-tags { color: <?php echo $tempera_contentcolortxtlight; ?>; }

.entry-meta .crycon-metas:before {color:<?php echo $tempera_metacoloricons; ?>;}
.entry-meta span a, .comments-link a, .entry-meta {color:<?php echo $tempera_metacolorlinks; ?>;}
.entry-meta span a:hover, .comments-link a:hover {color:<?php echo $tempera_metacolorlinkshover; ?>;}

.nav-next a:hover {}
.nav-previous a:hover {
}
.pagination { border-color:<?php echo cryout_hexadder($tempera_accentcolore,'-10');?>;}
.pagination span, .pagination a {
	background:<?php echo $tempera_accentcolore;?>;
	border-left-color:<?php echo cryout_hexadder($tempera_accentcolore,'-26'); ?>;
	border-right-color:<?php echo cryout_hexadder($tempera_accentcolore,'16'); ?>;
}
.pagination a:hover { background: <?php echo cryout_hexadder($tempera_accentcolore,'8'); ?>; }

#searchform input[type="text"] {color:<?php echo $tempera_contentcolortxtlight; ?>;}

.caption-accented .wp-caption {<?php if(cryout_hex2rgb($tempera_accentcolora)):?> background-color:rgba(<?php echo cryout_hex2rgb($tempera_accentcolora);?>,0.8); <?php endif; ?>
	color:<?php echo $tempera_contentcolorbg;?>}

.tempera-image-one .entry-content img[class*='align'], .tempera-image-one .entry-summary img[class*='align'],
.tempera-image-two .entry-content img[class*='align'], .tempera-image-two .entry-summary img[class*='align'],
.tempera-image-one .entry-content [class*='wp-block'][class*='align'] img, .tempera-image-one .entry-summary [class*='wp-block'][class*='align'] img,
.tempera-image-two .entry-content [class*='wp-block'][class*='align'] img, .tempera-image-two .entry-summary [class*='wp-block'][class*='align'] img {
	border-color:<?php echo $tempera_accentcolora; ?>;}
<?php
////////// LAYOUT //////////
?>
html { font-size:<?php echo $tempera_fontsize ?>;  line-height:<?php echo (float) $tempera_lineheight; ?>; }
.entry-content, .entry-summary, #frontpage blockquote { text-align:<?php echo $tempera_textalign;  ?>; }
.entry-content, .entry-summary, .widget-area {
	<?php if ($tempera_wordspace != 'Default') { ?> word-spacing: <?php echo esc_html($tempera_wordspace) ?>; <?php } ?>
	<?php if ($tempera_letterspace != 'Default') { ?> letter-spacing: <?php echo esc_html($tempera_letterspace) ?>; <?php } ?> }
<?php if ($tempera_uppercasetext==1): ?> #site-title a, #site-description, #access a, .topmenu ul li a, .footermenu a, .entry-meta span a, .entry-utility span a, #content span.entry-format,
span.edit-link, h3#comments-title, h3#reply-title, .comment-author cite, .comments .reply a, .widget-title, #site-info a, .nivo-caption h2, a.continue-reading-link,
.column-image h3, #front-columns h3.column-header-noimage, .tinynav , .entry-title, .breadcrumbs, .page-link{ text-transform: uppercase; }<?php endif; ?>
<?php if ($tempera_hcenter): ?> #bg_image {display:block;margin:0 auto;} <?php endif; ?>
#content h1.entry-title, #content h2.entry-title { font-size:<?php echo $tempera_headfontsize; ?> ;}
.widget-title, .widget-title a { font-size:<?php echo $tempera_sidefontsize; ?> ;}
<?php $font_root = 2.375;
for($i=1;$i<=6;$i++) { ?>
	h<?php echo $i ?> { font-size: <?php echo round(($font_root-($i*0.27))*(preg_replace("/[^\d]/","",$tempera_headingsfontsize)/100),4); ?>em; }
<?php } ?>
#site-title { font-size:<?php echo $tempera_sitetitlesize; ?> ;}
#access ul li a, li.menu-main-search .searchform input[type="search"] { font-size:<?php echo $tempera_menufontsize; ?> ;}
<?php /*if ($tempera_postseparator == "Show") { ?> article.post, article.page { padding-bottom: 10px; border-bottom: 3px solid #EEE; } <?php }*/ ?>
<?php if ($tempera_contentlist == "Hide") { ?> #content ul li { background-image: none; padding-left: 0; } <?php } ?>
<?php if ($tempera_comtext == "Hide") { ?> #respond .form-allowed-tags { display:none;} <?php } ?>
<?php switch ($tempera_comclosed) {
	case "Hide in posts": ?> .nocomments { display:none;} <?php break;
	case "Hide in pages": ?> .nocomments2 {display:none;} <?php break;
	case "Hide everywhere": ?> .nocomments, .nocomments2 {display:none;} <?php break;
};//switch ?>
<?php if ($tempera_comoff == "Hide") { ?> .comments-link span { display:none;} <?php } ?>
<?php if ($tempera_tables == "Enable") { ?>
		.entry-content table, .entry-content tr th, .entry-content tr td { border: none; } 
		.entry-content tr, .entry-content tr th, .entry-content thead th { background: none; }
<?php } ?>
<?php if ($tempera_headingsindent == "Enable") { ?>
		#content h1, #content h2, #content h3, #content h4, #content h5, #content h6 { margin-left:20px; }
		.sticky hgroup { padding-left: 15px;}
<?php } ?>
#header-container > div { margin:<?php echo $tempera_headermargintop; ?>px 0 0 <?php echo $tempera_headermarginleft; ?>px;}
<?php if ($tempera_pagetitle == "Hide") { ?> .page h1.entry-title, .home .page h2.entry-title { display:none; } <?php } ?>
<?php if ($tempera_categtitle == "Hide") { ?> header.page-header, .archive h1.page-title { display:none; }  <?php } ?>
.entry-content p, .entry-content ul, .entry-content ol, .entry-content dd, .entry-content pre, .entry-content hr, .commentlist p { margin-bottom: <?php echo $tempera_paragraphspace;?>; }
<?php if ($tempera_parindent != "0px") { ?> .entry-content p:not(.continue-reading-button) { text-indent:<?php echo $tempera_parindent;?>;} <?php } ?>

<?php if ( ($tempera_metapos == 'Top' || $tempera_metapos == 'Hide') && ! is_single() ) { ?> footer.entry-meta { display: none; } <?php } ?>
<?php if ( ($tempera_metapos == 'Bottom' || $tempera_metapos == 'Hide') && ! is_single() ) { ?> header.entry-header > .entry-meta { display: none; } <?php } ?>

<?php switch ($tempera_menualign):
		case "center": ?> #access > .menu > ul { border-left: 1px solid <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>;
										-moz-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
										-webkit-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
										box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>; } <?php
		break;
		case "right": ?> #access > .menu > ul > li > a > span { border-left:1px solid <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>;
							-moz-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
							-webkit-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
							box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
							border-right: 0; } <?php
		break;
		case "rightmulti": ?> #access > .menu > ul > li > a > span { border-left:1px solid <?php echo cryout_hexadder($tempera_menucolorbgdefault,'24');?>;
							-moz-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
							-webkit-box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
							box-shadow: -1px 0 0 <?php echo cryout_hexadder($tempera_menucolorbgdefault,'-30');?>;
							border-right:0;	} <?php
		break;
		default:
		break;
	  endswitch; ?>
#toTop {background:<?php echo $tempera_contentcolorbg; ?>;margin-left:<?php echo $totalwidth+150 ?>px;}
<?php if (is_rtl() ) { ?> #toTop {margin-right:<?php echo $totalwidth+150 ?>px;-moz-border-radius:10px 0 0 10px;-webkit-border-radius:10px 0 0 10px;border-radius:10px 0 0 10px;}		<?php } ?>
#toTop:hover .crycon-back2top:before {color:<?php echo $tempera_accentcolorb;?>;}

@media (max-width: <?php echo $totalwidth+45 ?>px) {
	#footer2 #toTop {
		position: relative;
		margin-left: auto !important;
		margin-right: auto !important;
		bottom: 0;
		display: block;
		width: 45px;
		border-radius: 4px 4px 0 0;
		opacity: 1;
	}
}

#main {margin-top:<?php echo $tempera_contentmargintop;?>px; }
#forbottom {margin-left: <?php echo $tempera_contentpadding;?>px; margin-right: <?php echo $tempera_contentpadding;?>px;}
#header-widget-area { width: <?php echo $tempera_headerwidgetwidth; ?>; }
<?php
////////// HEADER IMAGE //////////
?>
#branding { height:<?php echo $tempera_hheight; ?>px; }
<?php if ($tempera_hratio) { ?> @media (max-width: 1920px) {#branding, #bg_image { display: block; height:auto; max-width:100%; min-height:inherit !important; display: block; } } <?php } ?>
<?php
	return apply_filters( 'tempera_custom_styles', preg_replace( array( '/(([\w-]+):\s*?;?\s*?([;}]))/i', '/:\s?default/i' ), array( '$3', ':inherit' ), ob_get_clean() ) );
} // tempera_custom_styles()

/* = PRESENTATION PAGE CUSTOM CSS
-----------------------------------------------*/
function tempera_presentation_css() {
	$temperas= tempera_get_theme_options();
	extract($temperas);
	ob_start();

	if ($tempera_fronthideheader) {?> #branding {display: none;} <?php }
	if ($tempera_fronthidemenu) {?> #access, body #nav-toggle, .topmenu {display: none;} <?php }
  	if ($tempera_fronthidewidget) {?> #colophon {display: none;} <?php }
	if ($tempera_fronthidefooter) {?> #footer2 {display: none;} <?php }
    if ($tempera_fronthideback) {?> #main {background: none;} <?php }

	if ($tempera_fpslider_topmargin) { ?> .slider-wrapper {padding-top: <?php echo $tempera_fpslider_topmargin; ?>px;} <?php }
?>

.slider-wrapper {
	max-width: <?php echo ($tempera_fpsliderwidth) ?>px ;
	max-height: <?php echo $tempera_fpsliderheight ?>px ;
	}
.slider-shadow {
	/* width: <?php echo ($tempera_fpsliderwidth) ?>px ; */
	}
#slider{
	max-width: <?php echo ($tempera_fpsliderwidth) ?>px ;
	max-height: <?php echo $tempera_fpsliderheight ?>px ;
<?php if ($tempera_fpslider_bordersize): ?> border:<?php echo $tempera_fpslider_bordersize ;?>px solid <?php echo $tempera_fpsliderbordercolor; ?>; <?php endif; ?> }
.theme-default .nivo-controlNav {top:-<?php echo $tempera_fpslider_bordersize+40 ?>px;}

#front-text1 h2, #front-text2 h2 {
	color: <?php echo $tempera_fronttitlecolor; ?>; }

#front-columns > div, #front-columns > li {
	width: <?php switch ($tempera_nrcolumns) {
    case 0: break;
	case 1: echo "100"; break;
    case 2: echo "47.5"; break;
    case 3: echo "30"; break;
    case 4: echo "21.2"; break;
	} ?>%; }

#front-columns > div.column<?php echo $tempera_nrcolumns; ?>, #front-columns > li:nth-child(n+<?php echo $tempera_nrcolumns; ?>) { margin-right: 0; }
.rtl #front-columns > div.column<?php echo $tempera_nrcolumns; ?>, .rtl #front-columns > li:nth-child(n+<?php echo $tempera_nrcolumns; ?>) { margin-left: 0; }

.column-image {	max-width:<?php echo $tempera_colimagewidth;?>px;margin:0 auto;}
.column-image img {	max-width:<?php echo $tempera_colimagewidth;?>px;  max-height:<?php echo $tempera_colimageheight;?>px;}

.nivo-caption { background-color: rgba(<?php echo cryout_hex2rgb($tempera_fpslidercaptionbg); ?>,0.7); }
.nivo-caption, .nivo-caption a { color: <?php echo $tempera_fpslidercaptioncolor; ?>; }
.theme-default .nivo-controlNav, .theme-default .nivo-directionNav a { background-color:<?php echo $tempera_fpsliderbordercolor; ?>; }
.slider-bullets .nivo-controlNav a { background-color: <?php echo cryout_hexadder($tempera_fpsliderbordercolor,'-24');?>; }
.slider-bullets .nivo-controlNav a:hover { background-color:  <?php echo cryout_hexadder($tempera_fpsliderbordercolor,'-44');?>; }
.slider-bullets .nivo-controlNav a.active {background-color: <?php echo $tempera_accentcolora; ?>; }
.slider-numbers .nivo-controlNav a { color:<?php echo $tempera_fpslidercaptioncolor; ?>;background-color:<?php echo $tempera_fpslidercaptionbg;?>;}
.slider-numbers .nivo-controlNav a:hover { color: <?php echo $tempera_accentcolora; ?>; }
.slider-numbers .nivo-controlNav a.active { color:<?php echo $tempera_accentcolora; ?>;}

<?php

	return apply_filters( 'tempera_custom_pp_styles', preg_replace( array( '/((background-)?color:\s*?)[;}]/i', '/:\s?default/i' ), array( '', ':inherit' ), ob_get_clean() ) );
} // tempera_presentation_css()


// Tempera function for inserting the Custom CSS into the header
function tempera_customcss() {
	$temperas = tempera_get_theme_options();
	if (!empty($temperas['tempera_customcss'])) {
		return htmlspecialchars_decode($temperas['tempera_customcss'], ENT_QUOTES);
	}
} // tempera_customcss()


// Tempera function for inseting the Custom JS into the header
function tempera_customjs() {
	$temperas = tempera_get_theme_options(); ?>
	<script type="text/javascript">
	var cryout_global_content_width = <?php echo $temperas['tempera_sidewidth'] ?>;
	var cryout_toTop_offset = <?php echo ($temperas['tempera_sidewidth']+$temperas['tempera_sidebar']) ?>;
	<?php if (is_rtl()) { ?>var cryout_toTop_offset = <?php echo ($temperas['tempera_sidewidth']+$temperas['tempera_sidebar']) ?>;<?php } ?>
	<?php if ($temperas['tempera_customjs'] != "") { ?>
		<?php echo htmlspecialchars_decode( $temperas['tempera_customjs'], ENT_QUOTES ); ?>
	<?php } ?>
	</script> <?php
} // tempera_customjs()


/*
 * Dynamic styles for the admin MCE Editor
 */
function tempera_editor_styles() {
	header( 'Content-type: text/css' );
	$options = tempera_get_theme_options();
	extract($options);

	$content_body = floor( (int) $tempera_sidewidth - 40 - (int) $tempera_contentpadding * 2 );

	$tempera_googlefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_googlefont));
	$tempera_headingsgooglefont = str_replace('+',' ',preg_replace('/[:&].*/','',$tempera_headingsgooglefont));
	$tempera_fontfamily = cryout_fontname_cleanup($tempera_fontfamily);
	$tempera_headingsfont = cryout_fontname_cleanup($tempera_headingsfont);

	ob_start();
?>
body.mce-content-body {
	max-width: <?php echo esc_html( $content_body ); ?>px;
	font-family: <?php echo ((!$tempera_googlefont)?$tempera_fontfamily:"\"$tempera_googlefont\""); ?>;
	font-size:<?php echo $tempera_fontsize ?>;
	line-height:<?php echo (float) $tempera_lineheight; ?>;
	color: <?php echo $tempera_contentcolortxt; ?>;
	background-color: <?php echo $tempera_contentcolorbg; ?>; }
body.mce-content-body * {
	color: <?php echo $tempera_contentcolortxt; ?>; }
body.mce-content-body p, body.mce-content-body ul, body.mce-content-body ol, body.mce-content-body select,
body.mce-content-body input, body.mce-content-body textarea, ody.mce-content-body input, ody.mce-content-body label {
	font-family: <?php echo ((!$tempera_googlefont)?$tempera_fontfamily:"\"$tempera_googlefont\""); ?>;
	font-size:<?php echo $tempera_fontsize ?>; }
<?php $font_root = 2.375; for ($i=1;$i<=6;$i++) { ?>
.mce-content-body h<?php echo $i ?> {
	font-size: <?php echo round(($font_root-($i*0.27))*(preg_replace("/[^\d]/","",$tempera_headingsfontsize)/100),4); ?>em; }
<?php } ?>
.mce-content-body h1, .mce-content-body h2, .mce-content-body h3, .mce-content-body h4, .mce-content-body h5, .mce-content-body h6 {
	font-family: <?php echo ((!$tempera_googlefonttitle)?(($tempera_fonttitle == 'General Font')?'inherit':"\"$tempera_fonttitle\""):"\"$tempera_googlefonttitle\""); ?>;
	color: <?php echo $tempera_contentcolortxtheadings ?>; }

.mce-content-body pre, .mce-content-body code, .mce-content-body blockquote {
	max-width: <?php echo esc_html( $content_body ) ?>px;
	color: <?php echo $tempera_contentcolortxt; ?>; }
.mce-content-bodyhr { background-color: <?php echo $tempera_accentcolord; ?>; }
.mce-content-body input, .mce-content-body select .mce-content-body textarea {
	background-color: <?php echo $tempera_accentcolore; ?>;
    border-color: <?php echo $tempera_accentcolord; ?> <?php echo $tempera_accentcolorc; ?> <?php echo $tempera_accentcolorc; ?> <?php echo $tempera_accentcolord; ?>;
	color: <?php echo $tempera_contentcolortxt; ?>; }
.mce-content-body input[type="submit"], .mce-content-body input[type="reset"] {
	color: <?php echo $tempera_contentcolorbg; ?>;
	background-color: <?php echo $tempera_accentcolora; ?>;
	border-color: <?php echo $tempera_accentcolord; ?>; }
.mce-content-body pre {
	background: transparent;
	border-color: <?php echo $tempera_accentcolord; ?>;
	border-bottom-color:<?php echo $tempera_accentcolora ;?>;}
.mce-content-body code { background-color:<?php echo $tempera_accentcolore; ?>;}
.mce-content-body blockquote {
	border-color: <?php echo $tempera_accentcolorc; ?>; }
.mce-content-body abbr, .mce-content-body acronym { border-color: <?php echo $tempera_contentcolortxt; ?>; }

.mce-content-body a 		{ color: <?php echo esc_html( $tempera_linkcolortext ); ?>; }
.mce-content-body a:hover	{ color: <?php echo esc_html( $tempera_linkcolorhover ); ?>; }

.mce-content-body p, .mce-content-body ul, .mce-content-body ol, .mce-content-body dd,
.mce-content-body pre, .mce-content-body hr { margin-bottom: <?php echo esc_html( $tempera_paragraphspace ) ?>; }
.mce-content-body p { text-indent: <?php echo esc_html( $tempera_parindent ) ?>;}

<?php // end </style>
	echo apply_filters( 'tempera_editor_styles', ob_get_clean() );
} // tempera_editor_styles()

// FIN
