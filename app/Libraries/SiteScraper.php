<?php

namespace App\Libraries;

Class SiteScraper
{
    function logData($title, $data)
    {
        $fp = fopen(config('constants.BASE_DIR') . "/errorData.txt", "a+") or die("Unable to open file!");
        fwrite($fp, "Log Time: " . date('Y-m-d H:i:s'));
        fwrite($fp, "\n------------------------------" . $title . "------------------------------\n");
        fwrite($fp, print_r($data, true));
        fwrite($fp, "\n------------------------------END------------------------------\n\n");
        fclose($fp);
    }

    function getFileExtension($url)
    {
        return strtolower(strrchr($url, '.'));
    }

    function cleanNumber($number)
    {
        $number = preg_replace("/[^0-9\.]/", "", $number);
        if (empty($number)) {
            return 0;
        }
        return $number;
    }

    function getPageContent($url, $header = false, $show_error = false)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:63.0) Gecko/20100101 Firefox/63.0');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $content = curl_exec($ch);

        if ($show_error && curl_errno($ch)) {
            echo 'error:' . curl_error($ch);
        }

        curl_close($ch);

        return $content;
    }

    function reformatContent($content)
    {
        $content = preg_replace("/&amp;/", "&", $content);
        $content = preg_replace("/&nbsp;/", " ", $content);
        $content = preg_replace("/&lt;/", "<", $content);
        $content = preg_replace("/&gt;/", ">", $content);
        $content = preg_replace("/\n/", " ", $content);
        $content = preg_replace("/\r/", " ", $content);
        $content = preg_replace("/\s+/", " ", $content);

        return $content;
    }

    function getPageSegment($content, $match1, $match2)
    {
        $segment = '';
        $pos1 = strpos($content, $match1);
        if ($pos1 !== false) {
            $pos2 = strpos($content, $match2, $pos1);
            $len = $pos2 - $pos1;
            $segment = substr($content, $pos1, $len);
        }
        return $segment;
    }

    function getOriginId($field)
    {
        $originId = 0;
        preg_match("/([\d]+)\s*.php/", $field, $matches);
        if (!empty($matches[1])) {
            $originId = trim($matches[1]);
        }
        return $originId;
    }

    function pr($msg)
    {
        echo '<pre>';
        print_r($msg);
        echo '</pre>';
    }

    function tr($res)
    {
        echo '<textarea cols = "130" rows = "35">';
        print_r($res);
        echo '</textarea>';
    }

    function cleanText($text)
    {
        $text = trim($text);
        $text = preg_replace("/<br[^<>]*?>/i", "\n", $text);
        $text = preg_replace("/\n/", " ", $text);
        $text = preg_replace("/\r/", " ", $text);
        $text = preg_replace("/\s+/", " ", $text);

        $text = preg_replace("/<script.*?>.*?<\/script>/", "", $text);
        $i = 0;
        while ($i < 5) {
            $i++;
            $text = preg_replace("/<[^<>]*?>/", "", $text);
        }
        $text = preg_replace("/<.*?>/", "", $text);

        return $text;
    }

    function scrapeMySmartPrice($url)
    {
        $variants = [];
        $pageContent = $this->getPageContent($url);
        $pageSegment = $this->getPageSegment(
            $pageContent,
            '<div class="avlbl-sizes">',
            '<div class="prc-box prc-tbl--fullcards">'
        );

        if (preg_match_all('/data-open-link="(.*?)">/', $pageSegment, $matches)) {
            if (!empty($matches[1])) {
                for ($i = 0, $n = count($matches); $i < $n; $i++) {
                    $pageUrl = trim($matches[1][$i]);
                    $pageContent = $this->getPageContent($pageUrl);

                    $price = 0;
                    if (preg_match('/<span class="prdct-dtl__prc-val">(.*?)<\/span>/', $pageContent, $result)) {
                        $price = $this->cleanNumber($result[1]);
                    }

                    $ram = '';
                    if (preg_match('/<li class="kyspc__item kyspc__item--ram">(.*?)<\/li>/', $pageContent, $result)) {
                        $ramParts = explode(' ', $result[1]);
                        $ram = str_replace('GB', ' GB', $ramParts[0]);
                        $ram = str_replace('MB', ' MB', $ram);
                    }

                    $storage = '';
                    if (preg_match('/<li class="kyspc__item kyspc__item--strge">(.*?)<\/li>/', $pageContent, $result)) {
                        $storageParts = explode(' ', $result[1]);
                        $storage = str_replace('GB', ' GB', $storageParts[0]);
                        $storage = str_replace('MB', ' MB', $storage);
                    }

                    /*if ($price == 0 || $ram == 0 || $storage == 0) {
                        $this->logData('Missing From MySmartPrice', $variants);
                    }*/

                    $variants[] = [
                        'title' => $storage . ', ' . $ram . ' RAM',
                        'price' => $price,
                        'currency_id' => config('constants.CURRENCY_INR')
                    ];
                }
            }
        } else {
            $price = 0;
            if (preg_match('/<span class="prdct-dtl__prc-val">(.*?)<\/span>/', $pageContent, $result)) {
                $price = $this->cleanNumber($result[1]);
            }

            if ($price == 0 && preg_match('/<strong>Expected Price<\/strong><\/td>\s*<td>(.*?)<\/td>/', $pageContent, $result)) {
                $price = str_replace('&#8377;', '', $result[1]);
                $price = $this->cleanNumber($price);
            }

            $ram = 0;
            if (preg_match('/<td>RAM<\/td>\s*<td>(.*?)<\/td>/', $pageContent, $result)) {
                $ram = trim($result[1]);
            }

            $storage = 0;
            if (preg_match('/<td>Storage<\/td>\s*<td>(.*?)<\/td>/', $pageContent, $result)) {
                $storage = trim($result[1]);
            }

            $variants[] = [
                'title' => $storage . ', ' . $ram . ' RAM',
                'price' => $price,
                'currency_id' => config('constants.CURRENCY_INR')
            ];
        }

        return $variants;
    }

    function scrape91mobiles($url)
    {
        $variants = [];
        $pageContent = $this->getPageContent($url);
        $pageContent = $this->reformatContent($pageContent);
        if (preg_match_all('/data-variant-memory-prourl="(.*?)"/', $pageContent, $matches)) {
            if (!empty($matches[1])) {
                for ($i = 0, $n = count($matches[1]); $i < $n; $i++) {
                    $pageUrl = 'https://www.91mobiles.com/' . trim($matches[1][$i]);
                    $pageContent = $this->getPageContent($pageUrl);
                    $pageContent = $this->reformatContent($pageContent);

                    $price = 0;
                    if (preg_match('/<span id\s*="productLatestPrice">(.*?)<\/span>/', $pageContent, $result)) {
                        $price = $this->cleanNumber($result[1]);
                    }

                    $ram = 0;
                    if (preg_match('/<td class="spec_ttle">RAM<\/td>(.*?)<td class="spec_des">(.*?)(<span|<\/td>)/', $pageContent, $result)) {
                        $ram = trim($result[2]);
                    }

                    $storage = 0;
                    if (preg_match('/<td class="spec_ttle">Internal Memory<\/td>(.*?)<td class="spec_des">(.*?)(<span|<\/td>)/', $pageContent, $result)) {
                        $storage = trim($result[2]);
                    }

                    /*if ($price == 0 || $ram == 0 || $storage == 0) {
                        $this->logData('Missing From MySmartPrice', $variants);
                    }*/

                    $variants[] = [
                        'title' => $storage . ', ' . $ram . ' RAM',
                        'price' => $price,
                        'currency_id' => config('constants.CURRENCY_INR')
                    ];
                }
            }
        } else {
            $price = 0;
            if (preg_match('/<span class="big_prc" itemprop="price"(.*?)>(.*?)<\/span>/', $pageContent, $result)) {
                $price = $this->cleanNumber($result[2]);
            }

            $ram = 0;
            if (preg_match('/<td class="spec_ttle">RAM<\/td>(.*?)<td class="spec_des">(.*?)(<span|<\/td>)/', $pageContent, $result)) {
                $ram = trim($result[2]);
            }

            $storage = 0;
            if (preg_match('/<td class="spec_ttle">Internal Memory<\/td>(.*?)<td class="spec_des">(.*?)(<span|<\/td>)/', $pageContent, $result)) {
                $storage = trim($result[2]);
            }

            $variants[] = [
                'title' => $storage . ', ' . $ram . ' RAM',
                'price' => $price,
                'currency_id' => config('constants.CURRENCY_INR')
            ];
        }

        return $variants;
    }

    function getSisterSites($url)
    {
        $sisterSites = [];
        $replaceList = [
            'sa.pricena.com' => [
                'path' => 'price-in-saudi-arabia',
                'currency' => config('constants.CURRENCY_SAR')
            ],
            'ae.pricena.com' => [
                'path' => 'price-in-dubai-uae',
                'currency' => config('constants.CURRENCY_AED')
            ],
            'eg.pricena.com' => [
                'path' => 'price-in-egypt',
                'currency' => config('constants.CURRENCY_EGP')
            ],
            'kw.pricena.com' => [
                'path' => 'price-in-kuwait',
                'currency' => config('constants.CURRENCY_KWD')
            ],
            'qa.pricena.com' => [
                'path' => 'price-in-qatar',
                'currency' => config('constants.CURRENCY_QAR')
            ]
        ];

        $host = parse_url($url, PHP_URL_HOST);
        if (!empty($replaceList[$host])) {
            $path = $replaceList[$host]['path'];
            $findWith = [$host, $path];

            foreach ($replaceList as $host => $site) {
                $replaceWith = [$host, $site['path']];
                $sisterSites[] = [
                    'url' => str_replace($findWith, $replaceWith, $url),
                    'currency' => $site['currency']
                ];
            }
        }

        return $sisterSites;
    }

    function scrapePricena($url, $pageContent, $currencyId)
    {
        $variants = [];
        if (empty($pageContent)) {
            $pageContent = $this->getPageContent($url);
        }

        $pageContent = $this->reformatContent($pageContent);
        $segment = $this->getPageSegment($pageContent, '<div class="allopts" id="alloptions">', '</ul>');

        if (preg_match_all('/<span class="text">(.*?)<\/span>\s*<span class="blue">(.*?)<\/span>/', $segment, $matches)) {
            if (!empty($matches[1])) {
                for ($i = 0, $n = count($matches[1]); $i < $n; $i++) {
                    $title = trim($matches[1][$i]);
                    $price = $this->cleanNumber($matches[2][$i]);

                    $variants[] = [
                        'title' => $title,
                        'price' => round($price),
                        'currency_id' => $currencyId
                    ];
                }
            }
        }

        return $variants;
    }

    function getAllVariants($url, $pageContent)
    {
        $variants = [];
        if (stripos($url, 'mysmartprice.com') !== false) {
            $variants = $this->scrapeMySmartPrice($url);
        } else if (stripos($url, '91mobiles.com') !== false) {
            $variants = $this->scrape91mobiles($url);
        } else if (stripos($url, 'sa.pricena.com') !== false) {
            $variants = $this->scrapePricena($url, $pageContent, config('constants.CURRENCY_SAR'));
        } else if (stripos($url, 'ae.pricena.com') !== false) {
            $variants = $this->scrapePricena($url, $pageContent, config('constants.CURRENCY_AED'));
        } else if (stripos($url, 'eg.pricena.com') !== false) {
            $variants = $this->scrapePricena($url, $pageContent, config('constants.CURRENCY_EGP'));
        } else if (stripos($url, 'kw.pricena.com') !== false) {
            $variants = $this->scrapePricena($url, $pageContent, config('constants.CURRENCY_KWD'));
        } else if (stripos($url, 'qa.pricena.com') !== false) {
            $variants = $this->scrapePricena($url, $pageContent, config('constants.CURRENCY_QAR'));
        }

        /*$sisterSites = $this->getSisterSites($url);
        foreach ($sisterSites as $site) {
            $result = $this->scrapePricena($site['url'], $site['currency']);
            $variants = array_merge($variants, $result);
        }*/

        return $variants;
    }
}