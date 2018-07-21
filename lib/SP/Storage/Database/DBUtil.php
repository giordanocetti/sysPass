<?php
/**
 * sysPass
 *
 * @author    nuxsmin
 * @link      https://syspass.org
 * @copyright 2012-2018, Rubén Domínguez nuxsmin@$syspass.org
 *
 * This file is part of sysPass.
 *
 * sysPass is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * sysPass is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 *  along with sysPass.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace SP\Storage\Database;

use SP\Core\Exceptions\SPException;

/**
 * Class DBUtil con utilidades de la BD
 *
 * @package SP\Storage
 */
class DBUtil
{
    /**
     * @var array Tablas de la BBDD
     */
    public static $tables = [
        'Client',
        'Category',
        'Tag',
        'UserGroup',
        'UserProfile',
        'User',
        'Account',
        'AccountToFavorite',
        'AccountFile',
        'AccountToUserGroup',
        'AccountHistory',
        'AccountToTag',
        'AccountToUser',
        'AuthToken',
        'Config',
        'CustomFieldType',
        'CustomFieldDefinition',
        'CustomFieldData',
        'EventLog',
        'PublicLink',
        'UserPassRecover',
        'UserToUserGroup',
        'Plugin',
        'Notification',
        'account_data_v',
        'account_search_v'
    ];

    /**
     * Escapar una cadena de texto con funciones de mysqli.
     *
     * @param string             $str string con la cadena a escapar
     * @param DBStorageInterface $DBStorage
     *
     * @return string con la cadena escapada
     */
    public static function escape($str, DBStorageInterface $DBStorage)
    {
        try {
            return $DBStorage->getConnection()->quote(trim($str));
        } catch (SPException $e) {
            processException($e);
        }

        return $str;
    }

    /**
     * Obtener la información del servidor de base de datos
     *
     * @param DBStorageInterface $DBStorage
     *
     * @return array
     */
    public static function getDBinfo(DBStorageInterface $DBStorage)
    {
        $dbinfo = [];

        try {
            $db = $DBStorage->getConnection();

            $attributes = [
                'SERVER_VERSION',
                'CLIENT_VERSION',
                'SERVER_INFO',
                'CONNECTION_STATUS',
            ];

            foreach ($attributes as $val) {
                $dbinfo[$val] = $db->getAttribute(constant('PDO::ATTR_' . $val));
            }
        } catch (\Exception $e) {
            processException($e);

            debugLog($e->getMessage());
        }

        return $dbinfo;
    }

    /**
     * Comprobar que la base de datos existe.
     *
     * @param DBStorageInterface $DBStorage
     * @param string             $dbName
     *
     * @return bool
     */
    public static function checkDatabaseExist(DBStorageInterface $DBStorage, $dbName)
    {
        try {
            $tables = implode(',', array_map(function ($value) {
                return '\'' . $value . '\'';
            }, self::$tables));

            $query = /** @lang SQL */
                'SELECT COUNT(*) 
                FROM information_schema.tables
                WHERE table_schema = \'' . $dbName . '\'
                AND `table_name` IN (' . $tables . ')';

            $numTables = (int)$DBStorage->getConnection()->query($query)->fetchColumn();

            return $numTables === count(self::$tables);
        } catch (\Exception $e) {
            processException($e);
        }

        return false;
    }
}