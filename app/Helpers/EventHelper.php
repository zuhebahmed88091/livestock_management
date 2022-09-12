<?php
namespace App\Helpers;

class EventHelper
{
    public static function logForCreate($data, $field = '')
    {
        $text = "";
        if (!empty($data[$field])) {
            $text = "Create new item: " . $data[$field];
        } else if (!empty($data['name'])) {
            $text = "Create new item: " . $data['name'];
        } else if (!empty($data['title'])) {
            $text = "Create new item: " . $data['title'];
        }
        
        return clean($text);
    }

    public static function logForUpdate($oldData, $data)
    {
        $fieldList = array();
        if (!empty($data)) {
            foreach ($data as $field => $value) {
                if (!empty($oldData[$field]) && $oldData[$field] != $value) {
                    $fieldList[] = $field . ': <b>' . $oldData[$field] . '</b> change to <b>' . $value . '</b>';
                }
            }
        }
        return implode(', ', $fieldList);
    }

    public static function logForDelete($data, $field = '')
    {
        $text = "";
        if (!empty($data[$field])) {
            $text = "Delete item: " . $data[$field];
        } else if (!empty($data['name'])) {
            $text = "Delete item: " . $data['name'];
        } else if (!empty($data['title'])) {
            $text = "Delete item: " . $data['title'];
        }
        return clean($text);
    }
}
