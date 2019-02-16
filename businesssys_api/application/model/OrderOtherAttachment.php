<?php

namespace app\model;

use think\Model;

/**其他业务附件
 * Class OrderOtherAttachment
 * @package app\model
 * @author: bordon
 */
class OrderOtherAttachment extends Model
{
    /**添加附件
     * @param array $ids 附件id
     * @param $other_id
     * @return int|string
     * @author: bordon
     */
    public static function addAttachment($ids = [], $other_id)
    {
        $arr = [];
        if (count($ids) < 1) {
            return true;
        }
        foreach ($ids as $item) {
            $arr[] = [
                'order_other_id' => $other_id,
                'attachment_id' => $item,
                'create_time' => time()
            ];
        }
        return self::insertAll($arr);
    }
}
