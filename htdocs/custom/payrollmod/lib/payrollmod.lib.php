<?php
/* Copyright (C) 2003      Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2012 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@capnetworks.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**

/**
 *	\file		lib/payrollmod.lib.php
 *	\ingroup	payrollmod
 *	\brief		This file is an example module library
 *				Put some comments here
 */
function payrollmodAdminPrepareHead()
{
    global $langs, $conf;
    
    $langs->load("payrollmod@payrollmod");
    
    $h = 0;
    $head = array();
    
  
    
    complete_head_from_modules($conf, $langs, $object, $head, $h, 'payrollmod');
    
    return $head;
}

