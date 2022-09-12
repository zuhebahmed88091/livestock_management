<?php

namespace App\Helpers;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class CommonHelper
{
    public static function getRole()
    {
        $roleName = '';
        $priority = 0;
        if (!empty(Auth::user()) && !empty(Auth::user()->roles)) {
            foreach (Auth::user()->roles as $role) {
                if ($role['priority'] > $priority) {
                    $priority = $role['priority'];
                    $roleName = $role['name'];
                }
            }
        }
        return $roleName;
    }

    public static function isCapable($permissions)
    {
        // Pass request if admin privilege is disabled
        if (!config('settings.IS_ADMIN_PRIVILEGE_ENABLE')) {
            return true;
        }

        // Pass request if super admin
        if (Auth::user()->hasRole('Admin')) {
            return true;
        }

        return Auth::user()->can($permissions);
    }

    public static function getDays($start, $end)
    {
        $start = strtotime($start);
        $end = strtotime($end);
        return intval(ceil(abs($end - $start) / 86400) + 1);
    }

    public static function setIntFilterQuery(&$query, $field, $operator, $value)
    {
        if ($operator == 1) {
            $query->where($field, '=', $value);
        } else if ($operator == 2) {
            $query->where($field, '>=', $value);
        } else if ($operator == 3) {
            $query->where($field, '<=', $value);
        } else if ($operator == 4) {
            $parts = explode('|', $value);
            if (count($parts) == 2) {
                $query->whereBetween($field, $parts);
            } else if (count($parts) == 1) {
                $query->whereBetween($field, [0, $parts[0]]);
            }
        }
    }

    public static function numberFormatIndia($num)
    {
        return preg_replace("/(\d+?)(?=(\d\d)+(\d)(?!\d))(\.\d+)?/i", "$1,", $num);
    }

    public static function getCurrentTime()
    {
        $current_time = (time() + (3600 * 0));
        return $current_time;
    }

    public static function displayTimeFormat($time, $onlyDate = false)
    {
        $time = strtotime($time);
        $current_time = self::getCurrentTime();
        $time_diff = $current_time - $time;

        if ($time_diff < 60) {
            $display_time = 'Just now';
        } else if ($time_diff < 3600) {
            $display_time = floor($time_diff / 60) . ' minutes ago';
        } else if ($time_diff < 86400 && date('Y-m-d', $time) == date('Y-m-d', $current_time)) {
            if ($onlyDate) {
                $display_time = 'Today';
            } else {
                $display_time = 'Today ' . date('g:i a', $time);
            }
        } else if ($time_diff < 172800) {
            if ($onlyDate) {
                $display_time = 'Yesterday';
            } else {
                $display_time = 'Yesterday ' . date('g:i a', $time);
            }
        } else {
            if ($onlyDate) {
                $display_time = date('M d, Y', $time);
            } else {
                $display_time = date('M d, Y g:i a', $time);
            }
        }
        return $display_time;
    }

    public static function getComparePercent($factor1, $factor2)
    {
        $comparePercent = 'Similar';
        if ($factor1 > $factor2 && $factor2 > 0) {
            $upDownPercent = (($factor1 - $factor2) / $factor2) * 100;
            $comparePercent = ceil($upDownPercent) . '% Increase';
        } else if ($factor2 > $factor1 && $factor1 > 0) {
            $upDownPercent = (($factor2 - $factor1) / $factor1) * 100;
            $comparePercent = ceil($upDownPercent) . '% Decrease';
        }
        return $comparePercent;
    }

    public static function generatePdf($html, $fileName = '', $orientation = 'portrait')
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', $orientation); // orientation: (landscape, portrait)
        $dompdf->render();
        $dompdf->stream($fileName);
    }

    public static function setUpExcelSheet($event, $orientation = 'Portrait')
    {
        $highestRow = $event->sheet->getDelegate()->getHighestRow();
        $highestColumn = $event->sheet->getDelegate()->getHighestColumn();

        //  Loop through each row of the worksheet in turn
        for ($row = 1; $row <= $highestRow; $row++) {
            $cellRange = 'A' . $row . ':' . $highestColumn . $row;
            $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(9);

            if ($row % 2 == 0) {
                $event->sheet->getDelegate()->getStyle($cellRange)
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()->setARGB('FFF6F6F6');
            }
        }

        $styleArray = array(
            'borders' => array(
                'allBorders' => array(
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'D6D6D6']
                )
            )
        );
        $event->sheet->getDelegate()->getStyle('A1:' . $highestColumn . $highestRow)
            ->applyFromArray($styleArray);

        $event->sheet->getDelegate()->getPageSetup()
            ->setOrientation($orientation ? PageSetup::ORIENTATION_PORTRAIT : PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4);
    }

    public static function cleanDescription($content, $size = 0)
    {
        $content = preg_replace("/<br[^<>]*?>/i", "\n", $content);
        $content = preg_replace("/\n/", " ", $content);
        $content = preg_replace("/\r/", " ", $content);
        $content = preg_replace("/\s+/", " ", $content);

        $content = preg_replace("/<script.*?>.*?<\/script>/", "", $content);
        $i = 0;
        while($i < 5) {
            $i++;
            $content = preg_replace("/<[^<>]*?>/", "", $content);
        }
        $content = preg_replace("/<.*?>/", "", $content);

        $append = '';
        if (mb_strlen($content) > $size) {
            $append = '...';
        }

        if ($size > 0) {
            // trim message to n characters, regardless of where it cuts off
            $msgTrimmed = mb_substr($content, 0, $size);

            // find the index of the last space in the trimmed message
            $lastSpace = strrpos($msgTrimmed, ' ', 0);

            // now trim the message at the last space so we don't cut it off in the middle of a word
            $content = mb_substr($msgTrimmed, 0, $lastSpace);
        }

        return $content . $append;
    }

    public static function getJsDisplayDateFormat()
    {
        if (config('settings.DISPLAY_DATE_FORMAT') == 'j/n/Y') {
            return 'M/D/YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'M j, Y') {
            return 'MMM D, YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'F j, Y') {
            return 'MMMM D, YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'm.d.y') {
            return 'MM.DD.YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'j, n, Y') {
            return 'D, M, YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'Y/m/d') {
            return 'YYYY/MM/DD';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'm/d/Y') {
            return 'MM/DD/YYYY';
        } else if (config('settings.DISPLAY_DATE_FORMAT') == 'Y-m-d') {
            return 'YYYY-MM-DD';
        }

        return 'MMM DD, YYYY';
    }
}
