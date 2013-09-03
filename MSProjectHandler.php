<?php

/**
 * PopupWhatlinkshere extension
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @package MediaWiki
 * @subpackage Extensions
 * @author Vitaliy Filippov <vitalif@mail.ru>, 2009+
 * @license GNU General Public License 2.0 or later
 * @link http://wiki.4intra.net/PopupWhatlinkshere
 */

if (!defined('MEDIAWIKI'))
{
	echo "This file is an extension to the MediaWiki software and cannot be used standalone.\n";
	die();
}

$wgExtensionMessagesFiles['MSProjectHandler'] = dirname(__FILE__).'/MSProjectHandler.i18n.php';
$wgAutoloadClasses['MSProjectHandler'] = dirname(__FILE__).'/MSProjectHandler.class.php';
$wgExtensionCredits['parserhook'][] = array(
	'name'    => 'MS Project file page',
	'author'  => 'Vladimir Koptev',
	'url'     => 'http://wiki.4intra.net/MSProjectHandler',
	'version' => '2013-08-30',
);
$wgMediaHandlers[MSProjectHandler::MIME] = 'MSProjectHandler';
$wgExtensionFunctions[] = 'egInstallMSProjectTypes';
$extMSProject = array('mpp', 'pod');
$wgResourceModules['MSProjectHandler'] = array(
	'scripts'       => array('xslt/msp-outline.js'),
	'styles'        => array('xslt/msp-outline.css'),
	'dependencies'  => array(),
	'localBasePath' => __DIR__,
	'remoteExtPath' => 'MSProjectHandler',
//    'position'      => 'top',
);
$wgHooks['BeforePageDisplay'][] = 'efMSProjectBeforePageDisplay';

foreach ($extMSProject as $ext)
{
    if (!in_array($ext, $wgFileExtensions))
        $wgFileExtensions[] = $ext;
}

function efMSProjectBeforePageDisplay(&$output, &$skin)
{
    $output->addModules( 'MSProjectHandler' );
    return true;
}

function egInstallMSProjectTypes()
{
    global $extMSProject;
    $mm = MimeMagic::singleton();
    foreach($extMSProject as $ext)
    {
        if (empty($mm->mExtToMime[$ext]))
            $mm->mExtToMime[$ext] = 'application/x-msproject';
        elseif (strpos($mm->mExtToMime[$ext], 'application/x-msproject') === false)
            $mm->mExtToMime[$ext] = trim($mm->mExtToMime[$ext]) . ' application/x-msproject';
        if (empty($mm->mMimeToExt['application/x-msproject']))
            $mm->mMimeToExt['application/x-msproject'] = $ext;
        elseif (strpos($mm->mMimeToExt['application/x-msproject'], $ext) === false)
            $mm->mMimeToExt['application/x-msproject'] = trim($mm->mMimeToExt['application/x-msproject']) . ' ' . $ext;
    }
}
