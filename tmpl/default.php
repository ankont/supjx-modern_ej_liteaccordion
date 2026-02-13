<?php
/**
 * @version	2.0.0 (2025-06-04)
 * @subpackage	mod_ej_liteaccordion
 * @copyright	Copyright (C) 2006-2025 Andreas Kontarinis & Element-J.com
 *              Modified from the EJLA J3 module with assistance from ChatGPT and Gemini
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
// no direct access
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

// Παίρνουμε το document και φτιάχνουμε το base URL του module
$document      = Factory::getDocument();
$moduleBaseUrl = Uri::base() . 'modules/' . $module->module . '/';

// -----------------------------------
// 1) FIRST OF ALL, READ XML FIELDS
// -----------------------------------
// 1.1) Core / Advanced settings
$fldPicPathType    = $params->get('ejla_pic_pathtype', 1);
$fldSiteSubfolder  = $params->get('ejla_site_subfolder', '');

// 1.2) Slider settings
$fldActivation     = $params->get('ejla_activate_on', 'click');
$fldAutostart      = $params->get('ejla_autostart', 1);
$fldEasing         = $params->get('ejla_easing', 'swing');
$fldRoundCorners   = $params->get('ejla_roundedstyle', 1);
$fldPauseOnHover   = $params->get('ejla_pauseonhover', 1);
$fldCycleSpeed     = (int) $params->get('ejla_cyclespeed', 6000);
$fldSlideSpeed     = (int) $params->get('ejla_slidespeed', 800);
$fldHeaderWidth    = (int) $params->get('ejla_headerwidth', 48);
$fldPHolderColor   = $params->get('ejla_placeholder_color', '');
$fldPHolderCss     = $params->get('ejla_placeholder_css', '');
$fldPHolderImage   = $params->get('ejla_placeholder_image', '');
$fldFrameCss       = $params->get('ejla_frame_css', '');
$fldDetailsCss     = $params->get('ejla_details_css', '');
// 1.3) Numbering / navigation
$fldBuildNavNums   = $params->get('ejla_buildnavnum', 1);
$fldPagingNumsCss  = $params->get('elja_pagingnums_css', '');
$fldPagingNumsOnCss= $params->get('elja_pagingnums_on_css', '');
$fldVtitleCss      = $params->get('ejla_vtitle_css', '');
// 1.4) Styling (Theme / Custom class)
$fldStyleScheme        = $params->get('ejla_stylescheme', 'dark');
$fldStyleSchemeCustom  = $params->get('ejla_stylescheme_custom', '');
// 1.5) Large Image (Full image) settings
$fldPicLink        = $params->get('ejla_pic_link', 1);
$fldPicWidth       = (int) $params->get('ejla_pic_width', 900);
$fldPicHeight      = (int) $params->get('ejla_pic_height', 400);
$fldPicClass       = $params->get('ejla_pic_class', '');
$fldPicCss         = $params->get('ejla_pic_css', '');
$fldPicFit         = $params->get('ejla_pic_fit', 'cover');

// 1.6) Captions – fields and options
$fldShowCaption          = $params->get('ejla_show_caption', 1);
$fldLinkCaption          = $params->get('ejla_link_caption', 1);

$fldShowImageCaption     = $params->get('ejla_show_image_caption', 0);
$fldImageCaptionColor    = $params->get('ejla_image_caption_color', '');
$fldImageCaptionCss      = $params->get('ejla_image_caption_css', '');

$fldShowTitle            = $params->get('ejla_show_title', 1);
$fldLinkTitle            = $params->get('ejla_link_title', 0);
$fldTitleColor           = $params->get('ejla_title_color', '');
$fldTitleCss             = $params->get('ejla_title_css', '');

$fldShowDate             = $params->get('ejla_show_date', 0);
$fldDateFormat           = $params->get('ejla_date_format', 'DATE_FORMAT_LC2');
$fldDateFormatCustom     = $params->get('ejla_date_format_custom', '');
$fldDateColor            = $params->get('ejla_date_color', '');
$fldDateCss              = $params->get('ejla_date_css', '');

$fldShowAuthor           = $params->get('ejla_show_author', 0);
$fldAuthorColor          = $params->get('ejla_author_color', '');
$fldAuthorCss            = $params->get('ejla_author_css', '');

$fldShowIntro            = $params->get('ejla_show_intro', 0);
$fldMaxIntro             = (int) $params->get('ejla_maxintro', '');
$fldIntroFallback        = $params->get('ejla_intro_fallback', 0);
$fldIntroColor           = $params->get('ejla_intro_color', '');
$fldIntroCss             = $params->get('ejla_introtext_css', '');

$fldShowThumb            = $params->get('ejla_show_thumb', 1);
$fldLinkThumb            = $params->get('ejla_thumb_link', 0);
$fldThumbFallback        = $params->get('ejla_thumb_fallback', 0);
$fldThumbWidth           = (int) $params->get('ejla_thumb_width', 80);
$fldThumbHeight          = (int) $params->get('ejla_thumb_height', 80);
$fldThumbColor           = $params->get('ejla_thumb_color', '');
$fldThumbCss             = $params->get('ejla_thumb_css', '');
$fldThumbFit             = $params->get('ejla_thumb_fit', 'cover');

$fldShowReadmore         = $params->get('ejla_show_readmore', 0);
$fldReadmoreText         = $params->get('ejla_readmoretext', 'Read More...');
$fldForceReadmore        = $params->get('ejla_force_readmore', 0);
$fldReadmoreColor        = $params->get('ejla_readmore_color', '');
$fldReadmoreCss          = $params->get('ejla_readmore_css', '');

// -------------------------
// 2) DO SOME CALCULATIONS
// -------------------------
// Create special string
$specialinstance         = rand(1, 4000) - 1;

// Find module class
if ($fldStyleScheme == 'custom') {
	$moduleStyle = $fldStyleSchemeCustom;
} else {
	$moduleStyle = $fldStyleScheme;
}

// Calculate maxWidth for wrapper
$itemCount = count($list);
$maxWidth  = ($itemCount * $fldHeaderWidth) + $fldPicWidth;

// --------------------
// 3) HELPER FUNCTION
// --------------------
/**
 * Επιστρέφει το inline style string για ένα χρώμα και ένα επιπλέον CSS,
 * μόνο εφόσον υπάρχει κάποιο από αυτά τα δύο.
 *
 * @param   string  $color  Η τιμή χρώματος (π.χ. '#ff0000') ή κενό
 * @param   string  $css    Επιπλέον CSS declarations ή κενό
 * @return  string          Το τελικό style="…" ή κενή συμβολοσειρά
 */
function renderStyle(string $color, string $css, string $style = ''): string
{
    if (trim($color) !== '') {
        $style .= 'color: ' . htmlspecialchars($color, ENT_QUOTES, 'UTF-8') . '; ';
    }

    if (trim($css) !== '') {
        $style .= $css;
    }

    return trim($style) !== '' ? 'style="' . trim($style) . '"' : '';
}
?>

<!--[if lt IE 9]>
<script>
    document.createElement('figure');
    document.createElement('figcaption');
</script>
<![endif]-->
<script type="text/javascript">
    jQuery.noConflict();
    jQuery(document).ready(accordionWidth);

    var lastWidth = jQuery(window).width();

    jQuery(window).resize(function() {
        if (lastWidth != jQuery(window).width()) {
            lastWidth = jQuery(window).width();
            jQuery('#liteaccordion<?php echo $specialinstance; ?>').liteAccordion('destroy');
        }
    });
    jQuery(window).resize(accordionWidth);

    function accordionWidth() {
        var totalAccordionWidth = jQuery('.ejla-frame<?php echo $specialinstance; ?> #liteaccordion<?php echo $specialinstance; ?>').width();
        var olWidth = jQuery('.ejla-frame<?php echo $specialinstance; ?> #liteaccordion<?php echo $specialinstance; ?> > ol').width();
        var acw = totalAccordionWidth - olWidth;
        var parentWidth = jQuery('.ejla-frame<?php echo $specialinstance; ?>').parent().width();
        var newwidth = parentWidth - acw;

        var headerWidthPx     = <?php echo $fldHeaderWidth; ?>;
        var largeImageWidthPx = <?php echo $fldPicWidth; ?>;
        var itemCount         = <?php echo count($list); ?>;
        var maxlightacc = (itemCount * headerWidthPx) + largeImageWidthPx;
        if (newwidth > maxlightacc) {
            newwidth = maxlightacc;
        }

        jQuery('#liteaccordion<?php echo $specialinstance; ?>').liteAccordion({
            onTriggerSlide       : function() {
                this.find('figcaption').fadeOut('fast');
                this.find('figcaption h2').slideUp();
            },
            onSlideAnimComplete  : function() {
                this.find('figcaption').fadeIn('slow');
                this.find('figcaption h2').slideDown();
            },
            activateOn           : '<?php echo $fldActivation; ?>',
            autoPlay             : <?php echo $fldAutostart; ?>,
            containerHeight      : <?php echo $fldPicHeight; ?>,
            containerWidth       : newwidth,
            headerWidth          : headerWidthPx,
            pauseOnHover         : <?php echo $fldPauseOnHover; ?>,
            theme                : '<?php
                                        if (isset($_GET['theme'])) {
                                            echo htmlspecialchars($_GET['theme'], ENT_QUOTES, 'UTF-8');
                                        } else {
                                            echo $moduleStyle;
                                        }
                                        ?>',
            rounded              : <?php echo $fldRoundCorners; ?>,
            slideSpeed           : <?php echo $fldSlideSpeed; ?>,
            cycleSpeed           : <?php echo $fldCycleSpeed; ?>,
            easing               : '<?php echo $fldEasing; ?>',
            enumerateSlides      : <?php echo $fldBuildNavNums; ?>
        }).find('figcaption:first').show();

        jQuery('#liteaccordion<?php echo $specialinstance; ?>').css('opacity', '1');
    }
</script>

<style type="text/css" media="screen">
    .ejla-frame<?php echo $specialinstance; ?> a.readon:hover {
        background-position: bottom;
    }

    .ejla-frame<?php echo $specialinstance; ?> .ejla .panel {
        <?php echo $fldDetailsCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> .liteAccordion .slide > h2.title span {
        <?php echo $fldVtitleCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> span.introtext {
        display: block;
        <?php echo $fldIntrotextCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> .picture img {
        <?php echo $fldPicCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> a.readon {
        <?php echo $fldReadmoreCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> {
        margin: 0 auto;
        <?php echo $fldFrameCss; ?>
    }

    span.ej<?php echo $specialinstance; ?>_created_date {
        font-size: 11px;
        <?php echo $fldDateCss; ?>
    }

    span.ej<?php echo $specialinstance; ?>_created_by {
        font-size: 11px;
        <?php echo $fldAuthorCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> .title b {
        <?php echo $fldPagingCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> .title.selected b {
        <?php echo $fldPagingOnCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> figure {
        display: block;
        width: 100%;
        height: 100%;
        margin: 0;
    }

    .ejla-frame<?php echo $specialinstance; ?> figure img {
        max-width: <?php echo $fldPicWidth; ?>px;
    }

    .ejla-frame<?php echo $specialinstance; ?> figcaption {
        width: 50%;
        padding: 12px 10px;
        position: absolute;
        bottom: 20px;
        right: 30px;
        z-index: 3;
        background: rgba(0, 0, 0, 0.7);
        color: white;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        <?php echo $fldDetailsCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> figcaption h2 {
        margin: 0 0 10px 0;
        font-size: 24px;
    }

    .ejla-frame<?php echo $specialinstance; ?> figcaption h2.title,
    .ejla-frame<?php echo $specialinstance; ?> figcaption h2.title a {
        <?php echo $fldTitleCss; ?>
    }

    .ejla-frame<?php echo $specialinstance; ?> h2 span {
        overflow: hidden;
    }

    .clr { clear: both; }
</style>

<div class="ejla-frame<?php echo $specialinstance; ?>"
     style="max-width: <?php echo $maxWidth; ?>px;">
    <div id="liteaccordion<?php echo $specialinstance; ?>" style="opacity: 0;" class="accordion">
        <ol>
			<?php foreach ($list as $item) : ?>
				<?php
				// 1) Αρχικοποίηση: ψάχνουμε την εικόνα από το JSON πεδίο "images"
				$img = '';
				$imageCaption = '';

				if (!empty($item->images)) {
					$imagesJson = json_decode($item->images);

					if (!empty($imagesJson->image_fulltext)) {
						$img = $imagesJson->image_fulltext;
					} elseif (!empty($imagesJson->image_fulltext_alt)) {
						$img = $imagesJson->image_fulltext_alt;
					}

					if (!empty($imagesJson->image_fulltext_caption)) {
						$imageCaption = $imagesJson->image_fulltext_caption;
					}

					// Joomla 4 & T4 framework: cleanImageURL
					if (\defined('JVERSION') && version_compare(JVERSION, '4.0.0', 'ge') && !empty($img)) {
						if (class_exists('\T4\Helper\Metadata')) {
							$cleanImg = \T4\Helper\Metadata::cleanImageURL($img);
							$img = $cleanImg->url;
						}
					}
				}

				// 2) Αν δεν βρέθηκε εικόνα από JSON, ψάχνουμε μέσα στο introtext
				if (empty($img)) {
					$ini = strpos(strtolower($item->introtext), '<img');
					if ($ini !== false) {
						$ini  = strpos($item->introtext, 'src="', $ini) + 5;
						$fin  = strpos($item->introtext, '"', $ini);
						$img  = substr($item->introtext, $ini, $fin - $ini);
						// Οτιδήποτε μετά το src μέχρι το επόμενο ">" αγνοείται
					}
				}

				// 3) Δημιουργούμε το $intro (strip_tags + trim + ellipsis) με βάση $fldMaxIntro
				$introText   = strip_tags($item->introtext);
				// Αν δεν υπάρχει intro και έχει ενεργοποιηθεί fallback, πάρουμε από fulltext
				if ($introText === '' && $fldIntroFallback === '1') {
					$introText = strip_tags($item->fulltext);
				}
			    $introText = trim($introText);
				if ($fldMaxIntro > 0 && mb_strlen($introText) > $fldMaxIntro) {
					$introText = mb_substr($introText, 0, $fldMaxIntro) . '…';
				}

				// 4) Δημιουργούμε το τελικό path της εικόνας, ελέγχοντας αν είναι ήδη απόλυτο URL
				if (!empty($img)) {
					if (preg_match('#^(https?:)?//#i', $img)) {
						// Ήδη απόλυτο URL (π.χ. "https://site.com/pic.jpg" ή "//cdn/site.jpg")
						$imagePath = $img;
					} else {
						$fldSiteSubfolder = trim($fldSiteSubfolder, '/');

						if ($fldPicPathType === '1') {
							// JURI::base(true) + σχετική διαδρομή
							$imagePath = Uri::base(true) . '/' . ltrim($img, '/');
						} else {
							// root-based
							if ($fldSiteSubfolder !== '') {
								$imagePath = '/' . $fldSiteSubfolder . '/' . ltrim($img, '/');
							} else {
								$imagePath = '/' . ltrim($img, '/');
							}
						}
					}
				} else {
					$imagePath = '';
				}

				// 5) Use Placeholder Image 
				if (empty($imagePath) && !empty($fldPHolderImage)) {
					$ph = $fldPHolderImage;
					if (preg_match('#^(https?://|//)#i', $ph)) {
						$imagePath = $ph;
					} else {
						$imagePath = Uri::base(true) . '/' . ltrim($ph, '/');
					}
				}

				$stylePos = 'width: ' . $fldPicWidth . 'px; height: ' . $fldPicHeight . 'px; ';
				$styleFit = 'object-position: 50% 50%; object-fit: ' . htmlspecialchars($fldPicFit, ENT_QUOTES, 'UTF-8') . '; background-';
				$attrStylePic = renderStyle($fldPHolderColor, $fldPicCss, $stylePos . $styleFit);

				if ($fldShowThumb) {
					$styleThumb = 'width: ' . $fldThumbWidth . 'px; 
								   height: ' . $fldThumbHeight . 'px;
								   object-position: 50% 50%;
								   object-fit: ' . htmlspecialchars($fldThumbFit, ENT_QUOTES, 'UTF-8') . '; background-';
					$attrStyleThumb = renderStyle($fldThumbColor, $fldThumbCss, $styleThumb);
				}
				?>

				<li>
					<!-- Επικεφαλίδα στοιχείου -->
					<h2 class="title">
						<span><?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?></span>
					</h2>
					<div>
						<figure>
							<?php if ($fldPicLink) : ?>
							<a href="<?php echo htmlspecialchars($item->link, ENT_QUOTES, 'UTF-8'); ?>">
							<?php endif; ?>

							<?php if (!empty($imagePath)) : ?>
								<img
									 class="<?php echo htmlspecialchars($fldPicClass, ENT_QUOTES, 'UTF-8'); ?>"
									 <?php echo $attrStylePic; ?>
									 alt="<?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>"
									 src="<?php echo htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8'); ?>" />

							<?php else : ?>
								<div class="ejla-placeholder-block <?php echo htmlspecialchars($fldPicClass, ENT_QUOTES, 'UTF-8'); ?>"
									 style="<?php echo renderStyle($fldPHolderColor, $fldPHolderCss, $stylePos); ?>">
								</div>
							<?php endif; ?>


							<?php if ($fldPicLink) : ?>
							</a>
							<?php endif; ?>

							<?php
							if ($fldShowCaption === '1') {
								$htmlcaption  = '<figcaption>';
								$has_caption  = false;

								if ($fldLinkCaption) {
								   $htmlcaption .='<a href="' . htmlspecialchars($item->link, ENT_QUOTES, 'UTF-8') . '">';
								}

								// Εικόνα caption από το JSON
								$imageCaption = htmlspecialchars($imageCaption, ENT_QUOTES, 'UTF-8');
								if ($fldShowImageCaption === '1' && trim($imageCaption) !== '') {
									$has_caption = true;
									$htmlcaption .= '<span' . renderStyle($fldImageCaptionColor, $fldImageCaptionCss) . '>'
										. $imageCaption
										. '</span>';
								}

								// Τίτλος
								if ($fldShowTitle === '1') {
									$titleText = htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8');
									$has_caption = true;

									// Ξεκινάμε το <h2> με renderStyle για χρώμα+css
									$htmlcaption .= '<h2' . renderStyle($fldTitleColor, $fldTitleCss) . '>';

									if ($fldLinkTitle === '1') {
										// Αν θέλουμε link, τυπώνουμε <a> με ίδια renderStyle
										$htmlcaption .= '<a href="' 
											. htmlspecialchars($item->link, ENT_QUOTES, 'UTF-8')
											. '" title="' . $titleText . '"'
											. renderStyle($fldTitleColor, $fldTitleCss)
											. '>'
											. $titleText
											. '</a>';
									} else {
										// Χωρίς σύνδεσμο
										$htmlcaption .= $titleText;
									}

									$htmlcaption .= '</h2>';
								}

								// Thumbnail (Intro Image) in Caption
								if ($fldShowThumb === '1') {
									// 1) Προσπαθούμε να πάρουμε το intro image από το JSON
									$thumb = '';
									if (!empty($imagesJson->image_intro)) {
										$thumb = $imagesJson->image_intro;
									}
									// 2) Αν δεν υπάρχει intro image και έχουμε fallback, χρησιμοποιούμε το full image
									elseif ($fldThumbFallback === '1' && !empty($img)) {
										$thumb = $img;
									}

									if (!empty($thumb)) {
										// 3) Φτιάχνουμε το τελικό path για το thumbnail, όπως κάναμε και για το full image
										if (preg_match('#^(https?:)?//#i', $thumb)) {
											// Ήδη απόλυτο URL
											$thumbPath = $thumb;
										} else {
											if ($fldPicPathType === '1') {
												$thumbPath = Uri::base(true) . '/' . ltrim($thumb, '/');
											} else {
												$sub = trim($fldSiteSubfolder, '/');
												if ($sub !== '') {
													$thumbPath = '/' . $sub . '/' . ltrim($thumb, '/');
												} else {
													$thumbPath = '/' . ltrim($thumb, '/');
												}
											}
										}

										// 4) Αν θέλουμε να υπάρχει link γύρω από το thumbnail
										if ($fldLinkThumb === '1') {
											$htmlcaption .= '<a href="' . htmlspecialchars($item->link, ENT_QUOTES, 'UTF-8') . '">';
										}

										// 5) Εμφανίζουμε το <img> με inline CSS αν υπάρχει
										$htmlcaption .= '<img src="' . htmlspecialchars($thumbPath, ENT_QUOTES, 'UTF-8') . '"'
											. $attrStyleThumb . ' />';

										if ($fldLinkThumb === '1') {
											$htmlcaption .= '</a>';
										}

										$has_caption = true;
									}
								}

								// Ημερομηνία
								if ($fldShowDate === '1') {
									$rawDate = HTMLHelper::_('date', $item->created, Text::_($fldDateFormat));
									$dateText = Text::sprintf('COM_CONTENT_CREATED_DATE_ON', $rawDate);

									if (trim($dateText) !== '') {
										$has_caption = true;
										$htmlcaption .= '<span class="_created_date"' 
											. renderStyle($fldDateColor, $fldDateCss) 
											. '>'
											. $dateText
											. '</span>';
									}
								}

								// Συγγραφέας
								if ($fldShowAuthor === '1') {
									$authorName = $item->created_by_alias ?: $item->author;
									$escapedAuthor = htmlspecialchars($authorName, ENT_QUOTES, 'UTF-8');

									if (trim($escapedAuthor) !== '') {
										$has_caption = true;
										$htmlcaption .= '<span class="_created_by"'
											. renderStyle($fldAuthorColor, $fldAuthorCss)
											. '>by ' . $escapedAuthor . '</span>';
									}
								}

								// Intro Text
								if ($fldShowIntro === '1') {
									$introText = htmlspecialchars($introText, ENT_QUOTES, 'UTF-8');
									if (trim($introText) !== '') {
										$has_caption = true;
										$htmlcaption .= '<span class="introtext"'
											. renderStyle($fldIntroColor, $fldIntroCss)
											. '>' . $introText . '</span>';
									}
								}

								// Read More
								if ($fldShowReadmore === '1') {
									// Εμφάνισε αν υπάρχει fulltext ή αν έχει επιλεγεί το “Always Show”
									$hasFulltext = !empty($item->fulltext);
									if ($hasFulltext || $fldForceReadmore === '1') {
										$has_caption = true;
										$escapedReadmore = htmlspecialchars($fldReadmoreText, ENT_QUOTES, 'UTF-8');
										$htmlcaption .= '<a class="readon" href="'
											. htmlspecialchars($item->link, ENT_QUOTES, 'UTF-8') . '"'
											. renderStyle($fldReadmoreColor, $fldReadmoreCss)
											. '>' . $escapedReadmore . '</a>';
									}
								}

								if ($fldLinkCaption) {
									$htmlcaption .= '</a>';
								}
								$htmlcaption .= '</figcaption>';

								if ($has_caption) {
									echo $htmlcaption;
								}
							}
							?>
						</figure>
					</div>
				</li>
            <?php endforeach; ?>
        </ol>
        <noscript>
            <p>Please enable JavaScript to get the full experience.</p>
        </noscript>
    </div>
</div>

<style>
    ul.effects_list li,
    ul.style_list li,
    ul.thumb_list li {
        display: inline;
        padding: 0px;
    }
</style>

<script>
    jQuery(document).ready(function() {
        jQuery.urlParam = function(name) {
            var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(window.location.href);
            return results ? (results[1] || 0) : null;
        };
        jQuery('select.style_list option[value="' + jQuery.urlParam('theme') + '"]').prop('selected', true);
        jQuery('select.style_list').change(function() {
            var stylename = '';
            if (jQuery('select.style_list').val() !== '') {
                stylename = '&theme=' + jQuery('select.style_list').val();
                window.location.href = '/ej2016/extensions/ej-liteaccordion?demo=true' + stylename;
            }
        });
    });
</script>
