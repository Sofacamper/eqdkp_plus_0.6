 /*
 * Project:     EQdkp Newsletter
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:		    http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2007
 * Date:        $Date: 2009-03-04 00:28:05 +0100 (Mi, 04 Mrz 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: wallenium $
 * @copyright   2005-2008 Simon (Wallenium) Wallmann
 * @link        http://eqdkp-plus.com
 * @package     newsletter
 * @version     $Rev: 4087 $
 * 
 * $Id: newsletter_plugin_class.php 4087 2009-03-03 23:28:05Z wallenium $
 */

INSTALLATION

* Copy the folder "ticket" into the Plugin folder
* Go to the administration panel and find "manage plugins". Click "Install"
* Adjust permissions for Users and administrators
* Edit the plugin specific settings accessable via the administrationpanel

\\::\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//:://////////////////////////////////////////////

USAGE / FEATURES

* User are able to submit tickets which every admin with proper permission can answer
* More than one answer to a ticket is possible
* Users are able to submit replies to answers by admins
* Email Notification for admins und users is possible (for user the default is on)
* visual notification in the menu if new tickets/answers exist 
* Deletion of tickets is possible

\\::\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
//:://////////////////////////////////////////////

CHANGELOG:

to 0.02:
* Admin: when submitting a reply without a ticket an error is displayed if the username is not found and the message stays in the field
* Small bug with variable assignment fixed

to 0.03
* Adminpanel: Now every ticket is shown immediately, the admin nolonger has to have submitted a ticket himself (titan_flippi Bugreport)
* The "answer with another ticket" link for users is now centered and has an icon

to 0.04
* Adminpanel: Answers to tickets are now shown correctly (titan_flippi Bugreport)
* Language a little bit redone

to 0.05
* Adminpanel: by admins deleted tickets can be undeleted now so it is then possible to submit new answers
* Adminpanel: "answer to tickets" has now also an icon
* User: If a user deletes a ticket without marking possible answers as read they are now marked read automatically
* If tickets are deleted permanently now all replies, replytickets and replies to replytickets are deleted from the DB
* The "Ticket"-Link is now displayed in MainMenu2 as it is more a User thing than a Memberthing

to 0.06
* Installation: tried to fix error that occurs with the default value when creating the field firstreplydate
* New Link for admins in MainMenu2

to 0.07
* Notification emails now contain a link to the appropriate page
* the number of open tickets is now displayed to the right of the link for admins also

to 0.08
* Bug fixed, where Users which never set settings for themselves had to click accept twice to see a change
* If one is not logged in, one will not see the ticket-link in the menu anymore even if the settings should imply differently
* At the admin page the symbol "delete" is no longer shown if you look at deleted tickets

to 0.1
* Updated Readme files

to 0.101
* If a ticket gets deleted it is also set to replied (even if there is no reply) so it won't show up as a new ticket

to 0.102
* Runs with eqDKP 1.3.1 and 1.3.2

to 0.103
* fixed some display bugs including the bug that some answer tickets didn't show up (screentech's bugreport)

to 0.104
* tiny display change and a typo

to 0.105
* the admin installing the plugin automatically gains all permissions of it
