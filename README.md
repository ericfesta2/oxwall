CONTENTS OF THIS FILE
====================

 * About Oxwall
 * About This Fork
 * Compatibility with Legacy Oxwall
 * Copyright
 * Installation and Configuration
 * Appearance
 * Oxwall Store

ABOUT OXWALL
============

Oxwall is an open source social network platform, which supports a variety of community driven websites, from narrow fan clubs and local communities to extended social networking portals. It was designed and developed with a creative approach pursuing one purpose - to allow people running communities as easy as possible.
Oxwall was launched in 2010 and is supported by a nonprofit organization Oxwall Foundation (see https://www.oxwall.org/foundation/). For more information, visit Oxwall website at https://www.oxwall.org/.

ABOUT THIS FORK
===============

This fork of Oxwall is maintained by a longtime enthusiast unaffiliated with the Oxwall Foundation to keep the project
up-to-date with modern technologies and standards. While the goal of this project is to maintain Oxwall's compatibility
with a variety of web hosts, it raises the minimum supported version of PHP to 8.3 and MySQL to 8.0.

The original Oxwall repository can be found here: https://github.com/oxwall/oxwall

COMPATIBILITY WITH LEGACY OXWALL
================================

This fork of Oxwall cannot be assumed to be compatible with the original ("legacy") Oxwall software, whose latest
version is 1.8.4 as of July 2024, so seamless migrations of legacy Oxwall sites to this fork are presumed not to
be possible. Known incompatibilities so far:

* This fork does not have access to legacy Oxwall's official update system, so existing sites cannot be updated
  in-place via the Oxwall admin area.
* This fork improves the security of how users' passwords are stored and verified, removing a hardcoded salt
  value that legacy Oxwall relies on. Switching to the new password management algorithms will prevent any
  previously-registered users from logging in, unless those users' passwords were reset manually.

Because of these limitations, this fork is designed primarily for setting up new Oxwall sites. If you need greater
interoperability between legacy Oxwall and this fork, feel free to open a pull request in this repo!

COPYRIGHT
=========

Oxwall platform is licensed under Common Public Attribution License 1.0.
In short the license states that:
This software is open source and can be freely used, modified, and distributed;
This software can be used for commercial purposes;
Attribution to the authorship of this software in the source code files can not be waived under any circumstances;
Attribution to the authorship of this software on the site frontend in the form of labels and hyperlinks can be waived with permission of the original author. Contact us if you need that for your project.

Legal information about Oxwall:
 * Full license text:
        See LICENSE.txt in the same directory
 * Logo policy:
        http://www.oxwall.org/attribution/
 * Terms of use:
        http://www.oxwall.org/terms/

INSTALLATION AND CONFIGURATION
==============================

To get started you need to download Oxwall main package from http://www.oxwall.org/download/. The package includes the core of the platform and some additional modules (called plugins), such as Photo Uploading, Video Sharing, Instant Chat, Forum and Blogs. Every plugin can be enabled/disabled in the admin panel.
Upload unpacked package with an FTP client, type http://www.mycommunity/install/ in your browser, and follow instructions.
Oxwall core has numerous options, which allow site-specific configuration. In addition to predefined plugins, there are also many original and third-party plugins for extended functionality, not included in the main package.

More information:
 * Detailed installation and update instructions:
        See INSTALL.txt and UPDATE.txt in the same directory.
 * Learn more about Oxwall:
        http://docs.oxwall.org/
 * Post your questions:
        http://www.oxwall.org/forum/
 * Download additional plugins:
        http://www.oxwall.org/store/

APPEARANCE
==========

The visual appearance of an Oxwall-powered site is defined by a selected theme (themes are extensions that set site’s fonts, color scheme, and layout). Oxwall default package includes several standard themes, with more themes available for download. Users can also create their own custom themes. Themes can be customized via the Admin Panel, using simple customization interface. Additional theme customization can be done with an FTP client.

More about themes:
 * Download more original and contributed themes:
        http://www.oxwall.org/store/themes/
 * Learn more about theme customization:
        http://docs.oxwall.org/design:index

OXWALL STORE
============

Oxwall has its own public Store with numerous themes and plugins available for download. Themes and plugins in the Store are created by the Oxwall Foundation team or third-party contributors. Users can share their plugins and themes with the community by submitting them to the Store.

More info about Oxwall Store:
 * Oxwall Store:
	http://www.oxwall.org/store/
 * Oxwall Store terms of use:
        http://www.oxwall.org/store/terms
 * Oxwall Store Commercial License:
        http://www.oxwall.org/store/oscl
