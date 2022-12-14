-- ========================================================================
-- Copyright (C) 2017-2018	charlie Benke  <charlie@patas-monkey.com>
--
-- This program is free software; you can redistribute it and/or modify
-- it under the terms of the GNU General Public License as published by
-- the Free Software Foundation; either version 2 of the License, or
-- (at your option) any later version.
--
-- This program is distributed in the hope that it will be useful,
-- but WITHOUT ANY WARRANTY; without even the implied warranty of
-- MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
-- GNU General Public License for more details.
--
-- You should have received a copy of the GNU General Public License
-- along with this program. If not, see <http://www.gnu.org/licenses/>.
--
-- ========================================================================

create table llx_projectbudget
(
  rowid						integer            AUTO_INCREMENT PRIMARY KEY,
  fk_project				integer,
  mnt_previs_ht				DOUBLE(24, 8) NULL DEFAULT 0,
  mnt_ajust_ht				DOUBLE(24, 8) NULL DEFAULT 0,
  fk_projectbudget_type		integer

)ENGINE=innodb;