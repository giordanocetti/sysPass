<?php
/**
 * sysPass
 *
 * @author    nuxsmin
 * @link      http://syspass.org
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

namespace SP\Services\Account;

use SP\Core\Exceptions\SPException;
use SP\Core\Traits\InjectableTrait;
use SP\DataModel\FileData;
use SP\DataModel\FileExtData;
use SP\DataModel\ItemSearchData;
use SP\Repositories\Account\AccountFileRepository;

/**
 * Class AccountFileService
 *
 * @package SP\Services\Account
 */
class AccountFileService
{
    use InjectableTrait;

    /**
     * @var AccountFileRepository
     */
    protected $accountFileRepository;

    /**
     * AccountFileService constructor.
     *
     * @throws \SP\Core\Dic\ContainerException
     */
    public function __construct()
    {
        $this->injectDependencies();
    }

    /**
     * @param AccountFileRepository $accountFileRepository
     */
    public function inject(AccountFileRepository $accountFileRepository)
    {
        $this->accountFileRepository = $accountFileRepository;
    }

    /**
     * Creates an item
     *
     * @param FileData $itemData
     * @return mixed
     * @throws \SP\Core\Exceptions\ConstraintException
     * @throws \SP\Core\Exceptions\QueryException
     */
    public function create($itemData)
    {
        return $this->accountFileRepository->create($itemData);
    }

    /**
     * @param $id
     * @return FileExtData
     */
    public function getInfoById($id)
    {
        return $this->accountFileRepository->getInfoById($id);
    }

    /**
     * Returns the item for given id
     *
     * @param int $id
     * @return mixed
     */
    public function getById($id)
    {
        return $this->accountFileRepository->getById($id);
    }

    /**
     * Returns all the items
     *
     * @return FileExtData[]
     */
    public function getAll()
    {
        return $this->accountFileRepository->getAll();
    }

    /**
     * Returns all the items for given ids
     *
     * @param array $ids
     * @return array
     */
    public function getByIdBatch(array $ids)
    {
        return $this->accountFileRepository->getByIdBatch($ids);
    }

    /**
     * Deletes all the items for given ids
     *
     * @param array $ids
     * @return void
     * @throws SPException
     */
    public function deleteByIdBatch(array $ids)
    {
        foreach ($ids as $id) {
            $this->delete($id);
        }
    }

    /**
     * Deletes an item
     *
     * @param $id
     * @return AccountFileRepository
     * @throws SPException
     */
    public function delete($id)
    {
        return $this->accountFileRepository->delete($id);
    }

    /**
     * Searches for items by a given filter
     *
     * @param ItemSearchData $searchData
     * @return mixed
     */
    public function search(ItemSearchData $searchData)
    {
        return $this->accountFileRepository->search($searchData);
    }

    /**
     * Returns the item for given id
     *
     * @param int $id
     * @return mixed
     */
    public function getByAccountId($id)
    {
        return $this->accountFileRepository->getByAccountId($id);
    }
}