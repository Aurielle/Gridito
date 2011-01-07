<?php
/** 
 * PHP-in' CMS Framework
 * 
 * Copyright (c) 2010 Vaclav Vrbka (http://www.php-info.cz)
 * 
 * This source file is subject to the "PHP-in' CMS Framework licence".
 * For more information please see http://www.phpin.eu
 */

namespace Gridito;

use Ormion\Collection;

/**
 * OrmionModel
 *
 * @author Vaclav Vrbka
 */
class OrmionModel extends AbstractModel
{
	// <editor-fold defaultstate="collapsed" desc="variables">

	/** @var Collection */
	protected $collection;

	/** @var string */
	protected $rowClass;

	// </editor-fold>

	/**
	 * Constructor
	 * @param Collection data
	 */
	public function __construct(Collection $collection)
	{
		$this->collection = $collection;
		$this->rowClass = $collection->getItemType();
	}



	/**
	 * Get iterator
	 */
	public function getIterator()
	{
		return $this->collection->getIterator();
	}



	/**
	 * Maybe not the best
	 * @todo
	 */
	public function getItemByUniqueId($uniqueId)
	{
		$collection = clone $this->collection;
		$collection->where("%n = %i", $this->getPrimaryKey(), $uniqueId);
		return $collection->fetch();
	}


	public function getItems()
	{
		$collection = clone $this->collection;

		$collection->limit($this->getLimit());
		$collection->offset($this->getOffset());

		list($sortColumn, $sortType) = $this->getSorting();
		if ($sortColumn) {
			$collection->orderBy("[$sortColumn] $sortType");
		}

		return $collection->fetchAll();
	}



	/**
	 * Process action parameter
	 * @param mixed
	 * @return mixed
	 */
	/*public function processActionParam($param)
	{
		if ($param === null) {
			return null;
		}

		$class = $this->rowClass;
		return $class::create($param);
	}*/



	/**
	 * Setup grid after model connect
	 * @param Grid
	 */
	/*public function setupGrid(Grid $grid)
	{
		$class = $this->rowClass;
		$grid->setPrimaryKey($class::getConfig()->getPrimaryColumn());
	}*/



	/**
	 * Set sorting
	 * @param string column
	 * @param string asc or desc
	 */
	public function setSorting($column, $type)
	{
		$this->collection->removeClause("orderBy")->orderBy("[$column] $type");
		return $this->sorting = array($column, $type);
	}



	/**
	 * Item count
	 * @return int
	 */
	protected function _count()
	{
		return $this->collection->count();
	}



	/**
	 * Set limit
	 * @param int limit
	 */
	public function setLimit($limit)
	{
		$this->collection->removeClause("limit")->limit($limit);
		$this->limit = $limit;
	}



	/**
	 * Set offset
	 * @param int offset
	 */
	public function setOffset($offset)
	{
		$this->collection->removeClause("offset")->offset($offset);
		$this->offset = $offset;
	}

}