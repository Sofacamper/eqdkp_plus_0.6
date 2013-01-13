/*
 * Project:     EQdkp Shoutbox
 * License:     Creative Commons - Attribution-Noncommercial-Share Alike 3.0 Unported
 * Link:        http://creativecommons.org/licenses/by-nc-sa/3.0/
 * -----------------------------------------------------------------------
 * Began:       2008
 * Date:        $Date: 2009-06-05 12:44:22 +0200 (Fri, 05 Jun 2009) $
 * -----------------------------------------------------------------------
 * @author      $Author: Dallandros $
 * @copyright   2008 Aderyn
 * @link        http://eqdkp-plus.com
 * @package     shoutbox
 * @version     $Rev: 5030 $
 *
 * $Id: mark.js 5030 2009-06-05 10:44:22Z Dallandros $
 */

function markAllSBEntries(container_id)
{
  var rows = document.getElementById(container_id).getElementsByTagName('tr');
  var checkbox;

  for (var i = 0; i < rows.length; i++)
  {
    checkboxes = rows[i].getElementsByTagName('input');
    for (var j = 0; j < checkboxes.length; j++)
    {
      if (checkboxes[j] && checkboxes[j].type == 'checkbox')
      {
        if (checkboxes[j].disabled == false)
        {
          checkboxes[j].checked = true;
        }
      }
    }
  }

  return true;
}

function unmarkAllSBEntries(container_id)
{
  var rows = document.getElementById(container_id).getElementsByTagName('tr');
  var checkbox;

  for (var i = 0; i < rows.length; i++)
  {
    checkboxes = rows[i].getElementsByTagName('input');
    for (var j = 0; j < checkboxes.length; j++)
    {
      if (checkboxes[j] && checkboxes[j].type == 'checkbox')
      {
        if (checkboxes[j].disabled == false)
        {
          checkboxes[j].checked = false;
        }
      }
    }
  }

  return true;
}
