<?php

/**
 * Bludit Last.FM Sidebar Plugin
 *
 * @package    Bludit
 * @category   Plugins
 * @author     TomboFry
 * @copyright  2016 Tom Gardiner
 * @license    MIT
 * @version    1.0
 * @link       https://tombofry.co.uk/webdev/bludit-lastfm
 * @link       https://github.com/TomboFry/bludit-lastfm-plugin
 * @since      1.4
 */

class pluginLastFM extends Plugin {

	public function init() {
		$this->dbFields = array(
			"label" => "Most Played Tracks",
			"lastfm_username" => "",
			"count" => 5,
			"time" => "LAST_7_DAYS"
		);
	}

	public function form() {

		global $Language;

		HTML::formInputText(array(
			'name'=>'label',
			'label'=>$Language->g("Label"),
			'value'=>$this->getDbField('label'),
			'placeholder'=>$Language->g("Label")
		));

		HTML::formInputText(array(
			'name'=>'lastfm_username',
			'label'=>$Language->g("lastfm-username"),
			'value'=>$this->getDbField('lastfm_username'),
			'placeholder'=>$Language->g("Username")
		));

		HTML::formSelect(array(
			'name'=>'time',
			'label'=>$Language->g("time-label"),
			'options'=>array(
				'LAST_7_DAYS'	=> $Language->g("last-7-days"),
				'LAST_30_DAYS'	=> $Language->g("last-30-days"),
				'LAST_90_DAYS'	=> $Language->g("last-90-days"),
				'LAST_180_DAYS'	=> $Language->g("last-180-days"),
				'LAST_365_DAYS'	=> $Language->g("last-365-days")
			),
			'selected'=>$this->getDbField('time'),
			'tip'=>''
		));

		HTML::formInputText(array(
			'name'=>'count',
			'label'=>$Language->g("count-label"),
			'value'=>$this->getDbField('count'),
			'placeholder'=>'5',
			'tip'=>$Language->g("count-warning")
		));

	}

	public function siteSidebar()
	{
		// Get the username field and make sure it's not empty.
		// If it is, we can't load the page, so we return to the sidebar empty.
		$user = strtolower($this->getDbField('lastfm_username'));
		if (empty($user)) return "";

		// The user is told to enter a number, but if they didn't make sure
		// it's still a valid number and set to default if it isn't.
		$count = intval($this->getDbField('count'));
		if (empty($count)) $count = 5;

		// We can only display up to 50 because that's how many are displayed
		// on the Last.fm page.
		if ($count > 50) $count = 50;

		$time = $this->getDbField('time');

		// Simple HTML DOM - Allows for jQuery-like selectors
		// Modified slightly to restrict images from downloading as well
		//  - TomboFry <tom@tombofry.co.uk>
		require_once(dirname(__FILE__) . DS . 'simple_html_dom.php');

		$dom_html = file_get_html('http://www.last.fm/user/' . $user . '/library/tracks?date_preset=' . $time);

		$html  = '<div class="plugin plugin-lastfm">';
		$html .= '<h2 class="plugin-title">'.$this->getDbField('label').'</h2>';
		$html .= '<div class="plugin-content">';
		// An ordered list because it's listing tracks in an order
		$html .= '<ol>';

		$i = 0;
		// Find all the rows in the specified page with song titles and artists
		foreach ($dom_html->find('.chartlist-name') as $content) {
			// Extract the data from them.
			$artist = $content->find('a',0)->plaintext;
			$title = $content->find('a',1)->plaintext;
			$link = $content->find('a',1)->href;

			// Change a URL from (for example):
			// "/user/tombofry/library/music/Coldplay/_/Magic?date_preset=LAST_7_DAYS"
			// to:
			// "http://last.fm/music/Coldplay/_/Magic?date_preset=LAST_7_DAYS"
			$link = str_replace(
				'/user/' . $user . '/library',
				'http://www.last.fm',
				$link
			);

			// Remove the "?date_preset=[...]" from the URL as well
			$link = strstr($link, "?date_preset=" . $time, true);

			// Finally, add it as an item to the list.
			$html .= '<li>';
			// Opens in a new tab because we're leaving our own domain
			$html .= '<a target="_blank" href="' . $link . '">' . $artist . ' - ' . $title . '</a>';
			$html .='</li>';

			// Check to make sure we only add as many as specified
			$i++;
			if ($i >= $count) break;
		}

		$html .= '</ol></div></div>';

		return $html;
	}

}
