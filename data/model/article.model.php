<?php
/**
 * 文章管理
 *
 *
 *
 */
defined('InShopBN') or exit('Access Invalid!');

class articleModel extends Model
{
    /**
     * 列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getArticleList($condition, $page = '')
    {
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'article';
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = (empty($condition['order']) ? 'aid desc' : $condition['order']);
        $result = Db::select($param, $page);
        return $result;
    }

    /**
     * 连接查询列表
     *
     * @param array $condition 检索条件
     * @param obj $page 分页
     * @return array 数组结构的返回结果
     */
    public function getJoinList($condition, $page = '')
    {
        $result = array();
        $condition_str = $this->_condition($condition);
        $param = array();
        $param['table'] = 'article,article_type';
        $param['field'] = empty($condition['field']) ? '*' : $condition['field'];;
        $param['join_type'] = empty($condition['join_type']) ? 'left join' : $condition['join_type'];
        $param['join_on'] = array('article.ac_id=article_class.ac_id');
        $param['where'] = $condition_str;
        $param['limit'] = $condition['limit'];
        $param['order'] = empty($condition['order']) ? 'article.article_sort' : $condition['order'];
        $result = Db::select($param, $page);
        return $result;
    }

    /**
     * 构造检索条件
     *
     * @param int $id 记录ID
     * @return string 字符串类型的返回结果
     */
    private function _condition($condition)
    {
        $condition_str = '';

        if ($condition['typeid'] != '') {
            $condition_str .= " and article.typeid = '" . $condition['typeid'] . "'";
        }
        if ($condition['typeids'] != '') {
            $condition_str .= " and article.typeid in(" . $condition['typeids'] . ")";
        }
        if ($condition['like_title'] != '') {
            $condition_str .= " and article.title like '%" . $condition['like_title'] . "%'";
        }
        if ($condition['home_index'] != '') {
            $condition_str .= " and (article_type.id <= 7)";
        }

        return $condition_str;
    }

    /**
     * 取单个内容
     *
     * @param int $id ID
     * @return array 数组类型的返回结果
     */
    public function getOneArticle($id)
    {
        if (intval($id) > 0) {
            $param = array();
            $param['table'] = 'article';
            $param['field'] = 'aid';
            $param['value'] = intval($id);
            $result = Db::getRow($param);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 新增
     *
     * @param array $param 参数内容
     * @return bool 布尔类型的返回结果
     */
    public function add($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            $tmp = array();
            foreach ($param as $k => $v) {
                $tmp[$k] = $v;
            }
            $result = Db::insert('article', $tmp);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 更新信息
     *
     * @param array $param 更新数据
     * @return bool 布尔类型的返回结果
     */
    public function updates($param)
    {
        if (empty($param)) {
            return false;
        }
        if (is_array($param)) {
            $tmp = array();
            foreach ($param as $k => $v) {
                $tmp[$k] = $v;
            }
            $where = " aid = '" . $param['aid'] . "'";
            $result = Db::update('article', $tmp, $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 删除
     *
     * @param int $id 记录ID
     * @return bool 布尔类型的返回结果
     */
    public function del($id)
    {
        if (intval($id) > 0) {
            $where = " aid = '" . intval($id) . "'";
            $result = Db::delete('article', $where);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * 取得文章数量
     * @param unknown $condition
     */
    public function getCount($condition = array())
    {
        return $this->table('article')->where($condition)->count();
    }
}
