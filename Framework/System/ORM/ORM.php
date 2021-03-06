<?php
/**
 * ORM.php
 *
 * @category   System
 * @package    ORM
 * @copyright  2010 Equalteam
 * @license    GPLv3
 * @version    $Revision: 133 $
 */

namespace Framework\System\ORM;

/**
 * ORM
 *
 * @category   System
 * @package    ORM
 * @copyright  2010 Equalteam
 * @license    GPLv3
 * @version    $Revision: 133 $
 */ 
class ORM// extends PDO
{
    /**
     * Тип обєднання UNION - 'ALL', в результаті обєднання будуть всі записи
     */
    const UNION_ALL = 'ALL';

    /**
     * Тип обєднання UNION - 'DISTINCT', в результаті обєднання будуть тільки унікальні записи
     */
    const UNION_DISTINCT = 'DISTINCT';

    /**
     * Назва базового класу ОРМ моделей
     */
    const ORM_RECORD_NAME = 'ORM_Record';

    public static function cache()
    {
        return new \ORM_CacheAdapter();
    }

    /**
     * Створює новий SELECT запит до БД за допомогою ORM_Query_Select
     *
     * @param string $from   Назва моделі
     * @param string $fields Список полів моделі, розділених комою
     *
     * @return \ORM_Query_Select
     */
    public static function select($from = null, $fields = null)
    {
        $builder = new \ORM_Query_Select();
        if ($from != null) {
            if (strpos($from, ' ') === false) {
                $from .= ' f';
            }
            $builder->from($from);
        }
        if ($fields != null) {
            $builder->select($fields);
        }
        return $builder;
    }

    /**
     * Створює новий DELETE запит до БД за допомогою ORM_Query_Delete
     *
     * @param string $from Назва моделі
     *
     * @return \ORM_Query_Delete
     */
    public static function delete($from = null)
    {
        $builder = new \ORM_Query_Delete();
        if ($from != null) {
            $builder->from($from);
        }
        return $builder;
    }

    /**
     * Створює новий INSERT запит до БД за допомогою ORM_Query_Insert
     *
     * @param string $from Назва моделі
     * @param string $set  Об'єкт ORMRecord
     *
     * @throws ORM_Exception_Insert
     * @return ORM_Query_Insert
     */
    public static function insert($from, $set = null)
    {
        $builder = new \ORM_Query_Insert();
        if ($from == 'DUAL' || !$from) {
            throw new \ORM_Exception_Insert('INTO parameter not set', $builder);
        }
        $builder->from($from);

        if ($set != null) {
            $builder->set($set);
        }
        return $builder;
    }

    /**
     * Створює новий UPDATE запит до БД за допомогою ORM_Query_Update
     *
     * @param string $model Назва моделі
     * @param string $set  Об'єкт ORMRecord
     *
     * @throws ORM_Exception_Model
     * @return ORM_Query_Update
     */
    public static function update($model, $set = null)
    {
        $builder = new \ORM_Query_Update();
        if (!$model) {
            throw new \ORM_Exception_Model('Model parameter not set', $model);
        }
        $builder->from($model);

        if ($set != null) {
            $builder->set($set);
        }
        return $builder;
    }

    /**
     * Обєднує результати двох запитів
     *
     * @param \ORM_Query_Builder $query1 Запит для обєднання
     * @param \ORM_Query_Builder $query2 Запит для обєднання
     * @param string            $type   Тип обднання
     *
     * @return \ORM_Query_Union
     */
    public static function union(\ORM_Query_Builder $query1, \ORM_Query_Builder $query2, $type = ORM::UNION_DISTINCT)
    {
        $builder = new \ORM_Query_Union($query1, $query2);
        if ($type != ORM::UNION_DISTINCT) {
            $builder->all();
        }
        return $builder;
    }

    /**
     * Перевір'яє чи існує таблиця в БД
     *
     * @param string $name Назва таблиці
     *
     * @return bool
     */
    public static function isTableExists($name)
    {
        $q = new \ORM_Query('SHOW TABLES LIKE "' . $name . '";');
        return $q->fetch() != null;
    }

    /**
     * Видаляє таблицю з БД
     *
     * @param string $name Назва таблиці
     *
     * @return void
     */
    public static function dropTable($name)
    {
        $q = new \ORM_Query('DROP TABLE IF EXISTS `' . $name . '`;');
        $q->exec();
    }

    /**
     * Розпочинає транзакцію
     *
     * @return void
     */
    public static function begin()
    {
        \ORM_Connection_Manager::getConnection()->begin();
    }

    /**
     * Комітить транзакцію
     *
     * @return void
     */
    public static function commit()
    {
        \ORM_Connection_Manager::getConnection()->commit();
    }

    /**
     * Робить відкат змін в межах розпочатої транзакції
     *
     * @return void
     */
    public static function rollBack()
    {
        \ORM_Connection_Manager::getConnection()->rollBack();
    }
}