<?php
/**
 * DISCLAIMER
 *
 * Do not edit or add to this file.
 * You are not authorized to modify, copy or redistribute this file.
 * Permissions are reserved by Satoshi Brasileiro.
 *
 *  @author    Satoshi Brasileiro
 *  @copyright Satoshi Brasileiro 2024
 *  @license   Single domain
 */
if (!defined('_PS_VERSION_')) {
    exit;
}

class FontInfo
{
    private $arr_info;

    public function __construct($str_font_path)
    {
        $this->arr_info = $this->getFontInfo($str_font_path);
    }

    private function getFontInfo($str_font_path)
    {
        $obint_jfile = fopen($str_font_path, 'r');
        $str_text = fread($obint_jfile, filesize($str_font_path));
        fclose($obint_jfile);

        $int_number_of_tables = hexdec($this->dec2ord($str_text[4]) . $this->dec2ord($str_text[5]));
        for ($int_i = 0; $int_i < $int_number_of_tables; ++$int_i) {
            $str_tag = $str_text[12 + $int_i * 16] . $str_text[12 + $int_i * 16 + 1] . $str_text[12 + $int_i * 16 + 2] . $str_text[12 + $int_i * 16 + 3];
            if ($str_tag == 'name') {
                $int_offset = hexdec($this->dec2ord($str_text[12 + $int_i * 16 + 8]) . $this->dec2ord($str_text[12 + $int_i * 16 + 8 + 1]) . $this->dec2ord($str_text[12 + $int_i * 16 + 8 + 2]) . $this->dec2ord($str_text[12 + $int_i * 16 + 8 + 3]));
                $int_offset_storage = hexdec($this->dec2ord($str_text[$int_offset + 4]) . $this->dec2ord($str_text[$int_offset + 5]));
                $int_number_of_name_records = hexdec($this->dec2ord($str_text[$int_offset + 2]) . $this->dec2ord($str_text[$int_offset + 3]));
            }
        }

        $int_storage_decimal = $int_offset_storage + $int_offset;
        $arr_font_tags = [];
        for ($int_k = 0; $int_k < $int_number_of_name_records; ++$int_k) {
            $int_name_id = hexdec($this->dec2ord($str_text[$int_offset + 6 + $int_k * 12 + 6]) . $this->dec2ord($str_text[$int_offset + 6 + $int_k * 12 + 7]));
            $int_string_length = hexdec($this->dec2ord($str_text[$int_offset + 6 + $int_k * 12 + 8]) . $this->dec2ord($str_text[$int_offset + 6 + $int_k * 12 + 9]));
            $int_string_offset = hexdec($this->dec2ord($str_text[$int_offset + 6 + $int_k * 12 + 10]) . $this->dec2ord($str_text[$int_offset + 6 + $int_k * 12 + 11]));

            if (!empty($int_name_id) && empty($arr_font_tags[$int_name_id])) {
                for ($int_j = 0; $int_j < $int_string_length; ++$int_j) {
                    if (ord($str_text[$int_storage_decimal + $int_string_offset + $int_j]) == '0') {
                        continue;
                    } else {
                        $arr_font_tags[$int_name_id] .= $str_text[$int_storage_decimal + $int_string_offset + $int_j];
                    }
                }
            }
        }

        return $arr_font_tags;
    }

    /**
     * Converts decimal to hex using the ascii value.
     *
     * @param int_decimal
     *
     * @return
     */
    protected function dec2ord($int_decimal)
    {
        return $this->dec2hex(ord($int_decimal));
    }

    /**
     * Performs hexadecimal to decimal conversion with proper padding.
     *
     * @param int_decimal
     *
     * @return
     */
    protected function dec2hex($int_decimal)
    {
        return str_repeat('0', 2 - Tools::strlen($str_hexadecimal = Tools::strtoupper(dechex($int_decimal)))) . $str_hexadecimal;
    }

    /**
     * Gets the copyright.
     *
     * @return
     */
    public function getCopyright()
    {
        return $this->arr_info[0];
    }

    /**
     * Gets the font family.
     *
     * @return
     */
    public function getFontFamily()
    {
        return $this->arr_info[1];
    }

    /**
     * Gets the sub font family.
     *
     * @return
     */
    public function getFontSubFamily()
    {
        return $this->arr_info[2];
    }

    /**
     * Gets the font id.
     *
     * @return
     */
    public function getFontId()
    {
        return $this->arr_info[3];
    }

    /**
     * Gets the font name.
     *
     * @return
     */
    public function getFontName()
    {
        return $this->arr_info[4];
    }
}
