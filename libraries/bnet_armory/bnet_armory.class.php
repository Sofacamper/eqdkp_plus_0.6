<?php
 /*
 * Project:		EQdkp-Plus
 * License:		Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:		2011
 * Date:		$Date: 2012-09-08 11:33:23 +0200 (Sat, 08 Sep 2012) $
 * -----------------------------------------------------------------------
 * @author		$Author: wallenium $
 * @copyright	2006-2011 EQdkp-Plus Developer Team
 * @link		http://eqdkp-plus.com
 * @package		eqdkp-plus
 * @version		$Rev: 12054 $
 * 
 * $Id: bnet_armory.class.php 12054 2012-09-08 09:33:23Z wallenium $
 *
 * Based on the new battlenet API, see documentation: http://blizzard.github.com/api-wow-docs/
 */

/*********** TODO ************ 
- testing of the header sending & API KEY
******************************/

if ( !defined('EQDKP_INC') ){
	header('HTTP/1.0 404 Not Found');exit;
}

class bnet_armory {
	private $version		= '5.0';
	private $build			= '$Rev: 12054 $';
	private $chariconUpdates = 0;
	const apiurl			= 'http://{region}.battle.net/api/';
	const staticrenderurl	= 'http://{region}.battle.net/static-render/';
	const tabardrenderurl	= 'http://{region}.battle.net/wow/static/images/guild/tabards/';

	private $_config		= array(
		'serverloc'				=> 'us',
		'locale'				=> 'en',
		'caching'				=> true,
		'caching_time'			=> 24,
		'apiUrl'				=> '',
		'apiRenderUrl'			=> '',
		'apiTabardRenderUrl'	=> '',
		'apiKeyPrivate'			=> '',
		'apiKeyPublic'			=> '',
		'maxChariconUpdates'	=> 10,
	);

	protected $convert		= array(
		'classes' => array(
			1		=> '12',	// warrior
			2		=> '13',	// paladin
			3		=> '4',		// hunter
			4		=> '2',		// rogue
			5		=> '6',		// priest
			6		=> '20',	// DK
			7		=> '9',		// shaman
			8		=> '11',	// mage
			9		=> '10',	// warlock
			11		=> '7',		// druid
			10		=> '21',	//monk
		),
		'races' => array(
			'1'		=> 2,		// human
			'2'		=> 7,		// orc
			'3'		=> 3,		// dwarf
			'4'		=> 4,		// night elf
			'5'		=> 6,		// undead
			'6'		=> 8,		// tauren
			'7'		=> 1,		// gnome
			'8'		=> 5,		// troll
			'9'		=> 12,		// Goblin
			'10'	=> 10,		// blood elf
			'11'	=> 9,		// draenei
			'22'	=> 11,		// Worgen
			'24'	=> 13,		// Pandaren neutral
			'25'	=> 13,		// Pandaren alliance
			'26'	=> 13,		// Pandaren horde
		),
		'gender' => array(
			'0'		=> 'Male',
			'1'		=> 'Female',
		),
	);

	private $serverlocs		= array(
		'eu'	=> 'EU',
		'us'	=> 'US',
		'kr'	=> 'KR',
		'tw'	=> 'TW',
	);
	private $converts		= array();

	/**
	* Initialize the Class
	* 
	* @param $serverloc		Location of Server
	* @param $locale		The Language of the data
	* @return bool
	*/
	public function __construct($serverloc='us', $locale='en_EN', $apikeys=false){
		global $pcache;
		//$this->stringIsUTF8			= ($this->isUTF8($utf8test) == 1) ? true : false;
		$this->stringIsUTF8			= false;
		$this->pcache				= $pcache;
		$this->_config['serverloc']	= $serverloc;
		$this->_config['locale']	= $locale;
		$this->setApiUrl($this->_config['serverloc']);
		if(isset($apikeys['apiKeyPrivate']) && isset($apikeys['apiKeyPublic'])){
			$this->_config['apiKeyPrivate']	= $apikeys['apiKeyPrivate'];
			$this->_config['apiKeyPublic']	= $apikeys['apiKeyPublic'];
		}
	}
	
	public function __get($name) {
		if(class_exists('registry')) {
			if($name == 'pfh') return registry::register('file_handler');
			if($name == 'puf') return registry::register('urlfetcher');
		}
		return null;
	}

	/**
	* Set some settings
	* 
	* @param $setting	Which language to import
	* @return bool
	*/
	public function setSettings($setting){
		if(isset($setting['loc'])){
			$this->_config['serverloc']	= $setting['loc'];
			$this->setApiUrl($this->_config['serverloc']);
		}
		if(isset($setting['locale'])){
			$this->_config['locale']	= $setting['locale'];
		}
		if(isset($setting['caching_time'])){
			$this->_config['caching_time']	= $setting['caching_time'];
		}
		if(isset($setting['caching'])){
			$this->_config['caching']	= $setting['caching'];
		}
		if(isset($setting['apiKeyPrivate']) && isset($setting['apiKeyPublic'])){
			$this->_config['apiKeyPrivate']	= $setting['apiKeyPrivate'];
			$this->_config['apiKeyPublic']	= $setting['apiKeyPublic'];
		}
	}

	public function getServerLoc(){
		return $this->serverlocs;
	}

	public function getVersion(){
		return $this->version.((preg_match('/\d+/', $this->build, $match))? '#'.$match[0] : '');
	}

	/**
	* Generate Link to Armory
	* 
	* @param $user			Name of the User
	* @param $server		Name of the WoW Server
	* @param $mode			Which page to open? (char, talent1, talent2, statistics, reputation, guild, achievements)
	* @param $guild			Name of the guild
	* @return string		output
	*/
	public function bnlink($user, $server, $mode='char', $guild=''){
		$linkprfx	= str_replace('/api', '/wow', $this->_config['apiUrl']);
		switch ($mode) {
			case 'char':
				return $linkprfx.sprintf('character/%s/%s/simple', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'talent1':
				return $linkprfx.sprintf('character/%s/%s/talent/primary', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'talent2':
				return $linkprfx.sprintf('character/%s/%s/talent/secondary', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'statistics':
				return $linkprfx.sprintf('character/%s/%s/statistic', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'reputation':
				return $linkprfx.sprintf('character/%s/%s/reputation', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'achievements':
				return $linkprfx.sprintf('character/%s/%s/achievement', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
			case 'guild':
				return $linkprfx.sprintf('guild/%s/%s/roster', $this->ConvertInput($server, true, true), $this->ConvertInput($guild));break;
			case 'character-feed':
				return $linkprfx.sprintf('character/%s/%s/feed', $this->ConvertInput($server, true, true), $this->ConvertInput($user));break;
		}
	}

	/**
	* Fetch character information
	* 
	* @param $user		Character Name
	* @param $realm		Realm Name
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function character($user, $realm, $force=false){
		$realm	= $this->ConvertInput($this->cleanServername($realm));
		$user	= $this->ConvertInput($user);
		$wowurl	= $this->_config['apiUrl'].sprintf('wow/character/%s/%s?locale%s&fields=guild,stats,talents,items,reputation,titles,professions,appearance,companions,mounts,pets,achievements,progression,pvp,quests', $realm, $user, $this->_config['locale']);
		if(!$json	= $this->get_CachedData('chardata_'.$user.$realm, $force)){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'chardata_'.$user.$realm);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		return (!$errorchk) ? $chardata: $errorchk;
	}

	/**
	* Create full character Icon Link
	* 
	* @param $thumb		Thumbinformation returned by battlenet JSON feed
	* @return string
	*/
	public function characterIcon($chardata, $forceUpdateAll = false){
		$cached_img	= str_replace('/', '_', 'image_character_'.$this->_config['serverloc'].'_'.$chardata['thumbnail']);
		$img_charicon	= $this->get_CachedData($cached_img, false, true);
		if(!$img_charicon && ($forceUpdateAll || ($this->chariconUpdates < $this->_config['maxChariconUpdates']))){
			$this->set_CachedData($this->read_url($this->_config['apiRenderUrl'].sprintf('%s/%s', $this->_config['serverloc'], $chardata['thumbnail'])), $cached_img, true);
			$img_charicon	= $this->get_CachedData($cached_img, false, true);

			// this is due to an api bug and may be removed some day, thumbs are always set and could be 404!
			if(filesize($img_charicon) < 400){
				$linkprfx	= str_replace('/api', '/wow/static/images/2d/avatar/', $this->_config['apiUrl']);
				$this->set_CachedData($this->read_url($linkprfx.sprintf('%s-%s.jpg', $chardata['race'], $chardata['gender'])), $cached_img, true);
			}
			$this->chariconUpdates++;
		}
		return $img_charicon;
	}

	/**
	* Fetch guild information
	* 
	* @param $user		Character Name
	* @param $realm		Realm Name
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function guild($guild, $realm, $force=false){
		$realm	= $this->ConvertInput($this->cleanServername($realm));
		$guild	= $this->ConvertInput($guild);
		$wowurl	= $this->_config['apiUrl'].sprintf('wow/guild/%s/%s?locale=%s&fields=members,achievements', $realm, $guild, $this->_config['locale']);
		if(!$json	= $this->get_CachedData('guilddata_'.$guild.$realm, $force)){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'guilddata_'.$guild.$realm);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		return (!$errorchk) ? $chardata: $errorchk;
	}

	/**
	* Generate guild tabard & save in cache
	* 
	* @param $emblemdata	emblem data array of battle.net api
	* @param $faction		name of the faction
	* @param $guild			name of the guild
	* @param $imgwidth		width of the image
	* @return bol
	*/
	public function guildTabard($emblemdata, $faction, $guild, $imgwidth=215){
		$cached_img	= sprintf('image_tabard_%s_w%s.png', strtolower(str_replace(' ', '', $guild)), $imgwidth);
		if(!$imgfile = $this->get_CachedData($cached_img, false, true)){
			if(!function_exists('imagecreatefrompng') || !function_exists('imagelayereffect')){
				return $this->root_path.sprintf('games/wow/guild/tabard_%s.png', (($faction == 0) ? 'alliance' : 'horde'));
			}
			$imgfile	= $this->get_CachedData($cached_img, false, true, true);

			// set the URL of the required image parts
			$img_emblem		= $this->_config['apiTabardRenderUrl'].sprintf('emblem_%02s', $emblemdata['icon']) .'.png';
			$img_border		= $this->_config['apiTabardRenderUrl']."border_".(($emblemdata['border'] == '-1') ? sprintf("%02s", $emblemdata['border']) : '00').".png";
			$img_ring		= $this->_config['apiTabardRenderUrl'].sprintf('ring-%s', (($faction == 0) ? 'alliance' : 'horde')) .'.png';
			$img_background	= $this->_config['apiTabardRenderUrl'].'bg_00.png';
			$img_shadow		= $this->_config['apiTabardRenderUrl'].'shadow_00.png';
			$img_overlay	= $this->_config['apiTabardRenderUrl'].'overlay_00.png';
			$img_hooks		= $this->_config['apiTabardRenderUrl'].'hooks.png';

			// set the image size (max width 215px) & generate the guild tabard image
			$img_resampled	= false;
			if ($imgwidth > 1 && $imgwidth < 215){
				$img_resampled	= true;
				$imgheight		= ($imgwidth/215)*230;
				$img_tabard		= imagecreatetruecolor($imgwidth, $imgheight);
				$tranparency	= imagecolorallocatealpha($img_tabard, 0, 0, 0, 127);
				imagefill($img_tabard, 0, 0, $tranparency);
				imagesavealpha($img_tabard,true);
				imagealphablending($img_tabard, true);
			}

			// generate the output image
			$img_genoutput	= imagecreatetruecolor(215, 230);
			imagesavealpha($img_genoutput,true);
			imagealphablending($img_genoutput, true);
			$tranparency	= imagecolorallocatealpha($img_genoutput, 0, 0, 0, 127);
			imagefill($img_genoutput, 0, 0, $tranparency);

			// generate the ring
			$ring			= imagecreatefrompng($img_ring);
			$ring_size		= getimagesize($img_ring);
			$emblem_image	= imagecreatefrompng($img_emblem);
			$emblem_size	= getimagesize($img_emblem);
			imagelayereffect($emblem_image, IMG_EFFECT_OVERLAY);
			$tmp_emblemcolor= preg_replace('/^ff/i','',$emblemdata['iconColor']);
			$emblemcolor	= array(hexdec(substr($tmp_emblemcolor,0,2)), hexdec(substr($tmp_emblemcolor,2,2)), hexdec(substr($tmp_emblemcolor,4,2)));
			imagefilledrectangle($emblem_image,0,0,$emblem_size[0],$emblem_size[1],imagecolorallocate($emblem_image, $emblemcolor[0], $emblemcolor[1], $emblemcolor[2]));

			// generate the border
			$border			= imagecreatefrompng($img_border);
			$border_size	= getimagesize($img_border);
			imagelayereffect($border, IMG_EFFECT_OVERLAY);
			$tmp_bcolor		= preg_replace('/^ff/i','',$emblemdata['borderColor']);
			$bordercolor	= array(hexdec(substr($tmp_bcolor,0,2)), hexdec(substr($tmp_bcolor,2,2)), hexdec(substr($tmp_bcolor,4,2)));
			imagefilledrectangle($border,0,0,$border_size[0]+100,$border_size[0]+100,imagecolorallocate($border, $bordercolor[0], $bordercolor[1], $bordercolor[2]));

			// generate the background
			$shadow			= imagecreatefrompng($img_shadow);
			$bg				= imagecreatefrompng($img_background);
			$bg_size		= getimagesize($img_background);
			imagelayereffect($bg, IMG_EFFECT_OVERLAY);
			$tmp_bgcolor	= preg_replace('/^ff/i','',$emblemdata['backgroundColor']);
			$bgcolor		= array(hexdec(substr($tmp_bgcolor,0,2)), hexdec(substr($tmp_bgcolor,2,2)), hexdec(substr($tmp_bgcolor,4,2)));
			imagefilledrectangle($bg,0,0,$bg_size[0]+100,$bg_size[0]+100,imagecolorallocate($bg, $bgcolor[0], $bgcolor[1], $bgcolor[2]));

			// put it together...
			imagecopy($img_genoutput,$ring,0,0,0,0, $ring_size[0],$ring_size[1]);
			$size			= getimagesize($img_shadow);
			imagecopy($img_genoutput,$shadow,20,23,0,0, $size[0],$size[1]);
			imagecopy($img_genoutput,$bg,20,23,0,0, $bg_size[0],$bg_size[1]);
			imagecopy($img_genoutput,$emblem_image,37,53,0,0, $emblem_size[0],$emblem_size[1]);
			imagecopy($img_genoutput,$border,32,38,0,0, $border_size[0],$border_size[1]);
			$size			= getimagesize($img_overlay);
			imagecopy($img_genoutput,imagecreatefrompng($img_overlay),20,25,0,0, $size[0],$size[1]);
			$size			= getimagesize($img_hooks);
			imagecopy($img_genoutput,imagecreatefrompng($img_hooks),18,23,0,0, $size[0],$size[1]);

			// check if the image is the same size as the image file parts, if not, resample the image
			if ($img_resampled){
				imagecopyresampled($img_tabard, $img_genoutput, 0, 0, 0, 0, $imgwidth, $imgheight, 215, 230);
			}else{
				$img_tabard = $img_genoutput;
			}
			imagepng($img_tabard,$imgfile);
		}
		return $imgfile;
	}

	/**
	* Fetch realm information
	* 
	* @param $realm		Realm Name
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function realm($realms, $force=false){
		$wowurl = $this->_config['apiUrl'].sprintf('wow/realm/status?locale=%s&realms=%s', $this->_config['locale'], $realms = ((is_array($realms)) ? implode(",",$realms) : ''));
		if(!$json	= $this->get_CachedData('realmdata_'.str_replace(",", "", $realms), $force)){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'realmdata_'.str_replace(",", "", $realms));
		}
		$realmdata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($realmdata);
		return (!$errorchk) ? $realmdata: $errorchk;
	}

	/**
	* Fetch item information
	* 
	* @param $itemid	battlenet Item ID
	* @param $force		Force the cache to update?
	* @return bol
	*/
	public function item($itemid, $force=false){
		$wowurl = $this->_config['apiUrl'].sprintf('wow/data/item/%s?locale=%s', $itemid, $this->_config['locale']);
		if(!$json	= $this->get_CachedData('itemdata_'.$itemid, $force)){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'itemdata_'.$itemid);
		}
		$itemdata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($itemdata);
		return (!$errorchk) ? $itemdata: $errorchk;
	}
	

	// DATA RESOURCES
	public function getdata_achievements($type='character', $force=false){
		$type = (($type == 'character') ? 'character' : 'guild');
		$wowurl	= $this->_config['apiUrl'].sprintf('wow/data/'.$type.'/achievements?locale=%s&fields=members,achievements', $this->_config['locale']);
		if(!$json	= $this->get_CachedData('achievementdata_'.$type, $force)){
			$json	= $this->read_url($wowurl);
			$this->set_CachedData($json, 'achievementdata_'.$type);
		}
		$chardata	= json_decode($json, true);
		$errorchk	= $this->CheckIfError($chardata);
		return (!$errorchk) ? $chardata: $errorchk;
	}

	/**
	* Check if the JSON is an error result
	* 
	* @param $data		XML Data of Char
	* @return error code
	*/
	protected function CheckIfError($data){
		$status	= (isset($data['status'])) ? $data['status'] : false;
		$reason	= (isset($data['reason'])) ? $data['reason'] : false;
		$error = '';
		if($status){
			return array('status'=>$status,'reason'=>$reason);
		}else{
			return false;
		}
	}

	/**
	* Clean the Servername if taken from Database
	* 
	* @return string output
	*/
	public function cleanServername($server){
		return html_entity_decode($server,ENT_QUOTES,"UTF-8");
	}

	/**
	* Convert from Armory ID to EQDKP Id or reverse
	* 
	* @param $name			name/id to convert
	* @param $type			int/string?
	* @param $cat			category (classes, races, months)
	* @param $ssw			if set, convert from eqdkp id to armory id
	* @return string/int output
	*/
	public function ConvertID($name, $type, $cat, $ssw=''){
		if($ssw){
			if(!is_array($this->converts[$cat])){
				$this->converts[$cat] = array_flip($this->convert[$cat]);
			}
			return ($type == 'int') ? $this->converts[$cat][(int) $name] : $this->converts[$cat][$name];
		}else{
			return ($type == 'int') ? $this->convert[$cat][(int) $name] : $this->convert[$cat][$name];
		}
	}

	/**
	* Prepare a string for beeing sent to armory
	* 
	* @param $input 
	* @return string output
	*/
	public function ConvertInput($input, $removeslash=false, $removespace=false){
		global $user;

		$input = ($removespace) ? str_replace(" ", "-", $input) : $input;
		if($removeslash){
			// new servername convention: mal'ganis = malganis
			$out    = ($this->stringIsUTF8) ? stripslashes(str_replace("'", "", $input)) : stripslashes(mb_convert_encoding(str_replace("'", "", $input),"UTF-8",$user->lang['ENCODING']));
		}else{
			$out    = ($this->stringIsUTF8) ? stripslashes(rawurlencode($input)) : stripslashes(rawurlencode(mb_convert_encoding($input,"UTF-8",$user->lang['ENCODING'])));
		}
		return $out;
	}

	protected function isUTF8($string){
		if (is_array($string)){
			$enc = implode('', $string);
			return @!((ord($enc[0]) != 239) && (ord($enc[1]) != 187) && (ord($enc[2]) != 191));
		}else{
			return (utf8_encode(utf8_decode($string)) == $string);
		}   
	}

	/**
	* Write JSON to Cache
	* 
	* @param	$json		XML string
	* @param	$filename	filename of the cache file
	* @return --
	*/
	protected function set_CachedData($json, $filename, $binary=false){
		if($this->_config['caching']){
			$cachinglink = $this->binaryORdata($filename, $binary);
			file_put_contents(((is_object($this->pcache)) ? $this->pcache->FolderPath('armory', 'eqdkp') : 'data/').$cachinglink, $json);
		}
	}

	/**
	* get the cached JSON if not outdated & available
	* 
	* @param	$filename	filename of the cache file
	* @param	$force		force an update of the cached json file
	* @return --
	*/
	protected function get_CachedData($filename, $force=false, $binary=false, $returniffalse=false){
		if(!$this->_config['caching']){return false;}
		$data_ctrl = false;
		$rfilename	= (is_object($this->pcache)) ? $this->pcache->FolderPath('armory', 'cache').$this->binaryORdata($filename, $binary) : 'data/'.$this->binaryORdata($filename, $binary);
		if(is_file($rfilename)){
			$data_ctrl	= (!$force && (filemtime($rfilename)+(3600*$this->_config['caching_time'])) > time()) ? true : false;
		}
		return ($data_ctrl || $returniffalse) ? (($binary) ? $rfilename : @file_get_contents($rfilename)) : false;
	}

	/**
	* check if binary files or json/data
	* 
	* @param	$input	the input
	* @param	$binary	true/false
	* @return --
	*/
	protected function binaryORdata($input, $binary=false){
		return ($binary) ? $input : 'data_'.md5($input);
	}

	/**
	* set the API Url
	* 
	* @param	$serverloc	the location of the server
	* @return --
	*/
	protected function setApiUrl($serverloc){
		$this->_config['apiUrl']				= str_replace('{region}', $serverloc, self::apiurl);
		$this->_config['apiRenderUrl']			= str_replace('{region}', $serverloc, self::staticrenderurl);
		$this->_config['apiTabardRenderUrl']	= str_replace('{region}', $serverloc, self::tabardrenderurl);
	}

	/**
	* Fetch the Data from URL
	* 
	* @param $url URL to Download
	* @return json
	*/
	protected function read_url($url) {
		$apikeyhead = (isset($this->_config['apiKeyPrivate']) && isset($this->_config['apiKeyPublic']) && $this->_config['apiKeyPrivate'] != '' && $this->_config['apiKeyPublic'] != '') ? $this->gen_api_header($url) : '';
		if(!is_object($this->puf)) {
			include_once('urlfetcher.class.php');
			$this->puf = new urlfetcher();
		}
		return $this->puf->fetch($url, $apikeyhead);
	}

	private function gen_api_header($url){
		$date = date(DATE_RFC2822);
		$headers = array(
			'Date: '. $date,
			'Authorization: BNET '. $this->_config['apiKeyPublic'] .':'. base64_encode(hash_hmac('sha1', "GET\n{$date}\n{$url}\n", $this->_config['apiKeyPrivate'], true))
		);
		return $headers;
	}

	/**
	* Check if an error occured
	* 
	* @return error
	*/
	public function CheckError(){
		return ($this->error) ? $this->error : false;
	}
}
#if(version_compare(PHP_VERSION, '5.3.0', '<')) registry::add_const('dep_bnet_armory', bnet_armory::$dependencies);
?>