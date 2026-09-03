<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle. If not, see <https://www.gnu.org/licenses/>.

/**
 * @package   local_moon
 * @author    Moon Framework Team https://moonframe.work
 * @copyright Copyright (C) 2026 MoonFrame.work.
 * @license https://www.gnu.org/licenses/gpl-3.0.html GNU/GPLv3 or Later
 */

namespace local_moon\library\helper;

/**
 * ArrayHelper is an array utility class for doing all sorts of odds and ends with arrays.
 *
 * @since  1.0
 */
final class array_helper
{
    /**
     * Private constructor to prevent instantiation of this class
     *
     * @since   1.0
     */
    private function __construct()
    {
    }

    /**
     * Function to convert array to integer values
     *
     * @param   array      $array    The source array to convert
     * @param   int|array  $default  A default value to assign if $array is not an array
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function to_integer($array, $default = null)
    {
        if (\is_array($array)) {
            return array_map('intval', $array);
        }

        if ($default === null) {
            return [];
        }

        if (\is_array($default)) {
            return static::to_integer($default, null);
        }

        return [(int) $default];
    }

    /**
     * Utility function to map an array to a stdClass object.
     *
     * @param   array    $array      The array to map.
     * @param   string   $class      Name of the class to create
     * @param   boolean  $recursive  Convert also any array inside the main array
     *
     * @return  object
     *
     * @since   1.0
     */
    public static function to_object(array $array, $class = 'stdClass', $recursive = true)
    {
        $obj = new $class();

        foreach ($array as $k => $v) {
            if ($recursive && \is_array($v)) {
                $obj->$k = static::to_object($v, $class);
            } else {
                $obj->$k = $v;
            }
        }

        return $obj;
    }

    /**
     * Utility function to map an array to a string.
     *
     * @param   array    $array         The array to map.
     * @param   string   $innerGlue     The glue (optional, defaults to '=') between the key and the value.
     * @param   string   $outerGlue     The glue (optional, defaults to ' ') between array elements.
     * @param   boolean  $keepOuterKey  True if final key should be kept.
     *
     * @return  string
     *
     * @since   1.0
     */
    public static function to_string(array $array, string $inner_glue = '=', string $outer_glue = ' ', $keep_outer_key = false)
    {
        $output = [];

        foreach ($array as $key => $item) {
            if (\is_array($item)) {
                if ($keep_outer_key) {
                    $output[] = $key;
                }

                // This is value is an array, go and do it again!
                $output[] = static::to_string($item, $inner_glue, $outer_glue, $keep_outer_key);
            } else {
                $output[] = $key . $inner_glue . '"' . $item . '"';
            }
        }

        return implode($outer_glue, $output);
    }

    /**
     * Utility function to map an object to an array
     *
     * @param   object   $source   The source object
     * @param   boolean  $recurse  True to recurse through multi-level objects
     * @param   string   $regex    An optional regular expression to match on field names
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function from_object($source, $recurse = true, $regex = null)
    {
        if (\is_object($source) || \is_array($source)) {
            return self::array_from_object($source, $recurse, $regex);
        }

        return [];
    }

    /**
     * Utility function to map an object or array to an array
     *
     * @param   mixed    $item     The source object or array
     * @param   boolean  $recurse  True to recurse through multi-level objects
     * @param   string   $regex    An optional regular expression to match on field names
     *
     * @return  array
     *
     * @since   1.0
     */
    private static function array_from_object($item, $recurse, $regex)
    {
        if (\is_object($item)) {
            $result = [];

            foreach (get_object_vars($item) as $k => $v) {
                if (!$regex || preg_match($regex, $k)) {
                    if ($recurse) {
                        $result[$k] = self::array_from_object($v, $recurse, $regex);
                    } else {
                        $result[$k] = $v;
                    }
                }
            }

            return $result;
        }

        if (\is_array($item)) {
            $result = [];

            foreach ($item as $k => $v) {
                $result[$k] = self::array_from_object($v, $recurse, $regex);
            }

            return $result;
        }

        return $item;
    }

    /**
     * Adds a column to an array of arrays or objects
     *
     * @param   array   $array    The source array
     * @param   array   $column   The array to be used as new column
     * @param   string  $colName  The index of the new column or name of the new object property
     * @param   string  $keyCol   The index of the column or name of object property to be used for mapping with the new column
     *
     * @return  array  An array with the new column added to the source array
     *
     * @since   1.5.0
     * @see     https://www.php.net/manual/en/language.types.array.php
     */
    public static function add_column(array $array, array $column, $col_name, $key_col = null)
    {
        $result = [];

        foreach ($array as $i => $item) {
            $value = null;

            if (!isset($key_col)) {
                $value = static::get_value($column, $i);
            } else {
                // Convert object to array
                $subject = \is_object($item) ? static::from_object($item) : $item;

                if (isset($subject[$key_col]) && is_scalar($subject[$key_col])) {
                    $value = static::get_value($column, $subject[$key_col]);
                }
            }

            // Add the column
            if (\is_object($item)) {
                if (isset($col_name)) {
                    $item->$col_name = $value;
                }
            } else {
                if (isset($col_name)) {
                    $item[$col_name] = $value;
                } else {
                    $item[] = $value;
                }
            }

            $result[$i] = $item;
        }

        return $result;
    }

    /**
     * Remove a column from an array of arrays or objects
     *
     * @param   array   $array    The source array
     * @param   string  $colName  The index of the column or name of object property to be removed
     *
     * @return  array  Column of values from the source array
     *
     * @since   1.5.0
     * @see     https://www.php.net/manual/en/language.types.array.php
     */
    public static function drop_column(array $array, $col_name)
    {
        $result = [];

        foreach ($array as $i => $item) {
            if (\is_object($item) && isset($item->$col_name)) {
                unset($item->$col_name);
            } elseif (\is_array($item) && isset($item[$col_name])) {
                unset($item[$col_name]);
            }

            $result[$i] = $item;
        }

        return $result;
    }

    /**
     * Extracts a column from an array of arrays or objects
     *
     * @param   array   $array     The source array
     * @param   string  $valueCol  The index of the column or name of object property to be used as value
     *                             It may also be NULL to return complete arrays or objects (this is
     *                             useful together with <var>$keyCol</var> to reindex the array).
     * @param   string  $keyCol    The index of the column or name of object property to be used as key
     *
     * @return  array  Column of values from the source array
     *
     * @since   1.0
     * @see     https://www.php.net/manual/en/language.types.array.php
     * @see     https://www.php.net/manual/en/function.array-column.php
     */
    public static function get_column(array $array, $value_col, $key_col = null)
    {
        return \array_reduce(
            $array,
            function ($result, $item) use ($key_col, $value_col) {
                $array = \is_object($item) ? get_object_vars($item) : $item;

                if ($value_col === null) {
                    $value = $item;
                } else {
                    if (!array_key_exists($value_col, $array)) {
                        return $result;
                    }

                    $value = $array[$value_col];
                }

                if ($key_col !== null && \array_key_exists($key_col, $array) && \is_scalar($array[$key_col])) {
                    $result[$array[$key_col]] = $value;
                } else {
                    $result[] = $value;
                }

                return $result;
            },
            []
        );
    }

    /**
     * Utility function to return a value from a named array or a specified default
     *
     * @param   array|\ArrayAccess  $array    A named array or object that implements ArrayAccess
     * @param   string              $name     The key to search for (this can be an array index or a dot separated key sequence as in Registry)
     * @param   mixed               $default  The default value to give if no key found
     * @param   string              $type     Return type for the variable (INT, FLOAT, STRING, WORD, BOOLEAN, ARRAY)
     *
     * @return  mixed
     *
     * @since   1.0
     * @throws  \InvalidArgumentException
     */
    public static function get_value($array, $name, $default = null, string $type = '')
    {
        if (!\is_array($array) && !($array instanceof \ArrayAccess)) {
            throw new \InvalidArgumentException('The object must be an array or an object that implements ArrayAccess');
        }

        $result = null;

        if (isset($array[$name])) {
            $result = $array[$name];
        } elseif (strpos($name, '.')) {
            list($name, $subset) = explode('.', $name, 2);

            if (isset($array[$name]) && \is_array($array[$name])) {
                return static::get_value($array[$name], $subset, $default, $type);
            }
        }

        // Handle the default case
        if ($result === null) {
            $result = $default;
        }

        // Handle the type constraint
        switch (strtoupper($type)) {
            case 'INT':
            case 'INTEGER':
                // Only use the first integer value
                @preg_match('/-?[0-9]+/', $result, $matches);
                $result = @(int) $matches[0];

                break;

            case 'FLOAT':
            case 'DOUBLE':
                // Only use the first floating point value
                @preg_match('/-?[0-9]+(\.[0-9]+)?/', $result, $matches);
                $result = @(float) $matches[0];

                break;

            case 'BOOL':
            case 'BOOLEAN':
                $result = (bool) $result;

                break;

            case 'ARRAY':
                if (!\is_array($result)) {
                    $result = [$result];
                }

                break;

            case 'STRING':
                $result = (string) $result;

                break;

            case 'WORD':
                $result = (string) preg_replace('#\W#', '', $result);

                break;

            case 'NONE':
            default:
                // No casting necessary
                break;
        }

        return $result;
    }

    /**
     * Takes an associative array of arrays and inverts the array keys to values using the array values as keys.
     *
     * Example:
     * $input = array(
     *     'New' => array('1000', '1500', '1750'),
     *     'Used' => array('3000', '4000', '5000', '6000')
     * );
     * $output = ArrayHelper::invert($input);
     *
     * Output would be equal to:
     * $output = array(
     *     '1000' => 'New',
     *     '1500' => 'New',
     *     '1750' => 'New',
     *     '3000' => 'Used',
     *     '4000' => 'Used',
     *     '5000' => 'Used',
     *     '6000' => 'Used'
     * );
     *
     * @param   array  $array  The source array.
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function invert(array $array)
    {
        $return = [];

        foreach ($array as $base => $values) {
            if (!\is_array($values)) {
                continue;
            }

            foreach ($values as $key) {
                // If the key isn't scalar then ignore it.
                if (is_scalar($key)) {
                    $return[$key] = $base;
                }
            }
        }

        return $return;
    }

    /**
     * Method to determine if an array is an associative array.
     *
     * @param   array  $array  An array to test.
     *
     * @return  boolean
     *
     * @since   1.0
     */
    public static function is_associative($array)
    {
        if (\is_array($array)) {
            foreach (array_keys($array) as $k => $v) {
                if ($k !== $v) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Pivots an array to create a reverse lookup of an array of scalars, arrays or objects.
     *
     * @param   array   $source  The source array.
     * @param   string  $key     Where the elements of the source array are objects or arrays, the key to pivot on.
     *
     * @return  array  An array of arrays pivoted either on the value of the keys, or an individual key of an object or array.
     *
     * @since   1.0
     */
    public static function pivot(array $source, $key = null)
    {
        $result  = [];
        $counter = [];

        foreach ($source as $index => $value) {
            // Determine the name of the pivot key, and its value.
            if (\is_array($value)) {
                // If the key does not exist, ignore it.
                if (!isset($value[$key])) {
                    continue;
                }

                $result_key   = $value[$key];
                $result_value = $source[$index];
            } elseif (\is_object($value)) {
                // If the key does not exist, ignore it.
                if (!isset($value->$key)) {
                    continue;
                }

                $result_key   = $value->$key;
                $result_value = $source[$index];
            } else {
                // Just a scalar value.
                $result_key   = $value;
                $result_value = $index;
            }

            // The counter tracks how many times a key has been used.
            if (empty($counter[$result_key])) {
                // The first time around we just assign the value to the key.
                $result[$result_key]  = $result_value;
                $counter[$result_key] = 1;
            } elseif ($counter[$result_key] == 1) {
                // If there is a second time, we convert the value into an array.
                $result[$result_key] = [
                    $result[$result_key],
                    $result_value,
                ];
                $counter[$result_key]++;
            } else {
                // After the second time, no need to track any more. Just append to the existing array.
                $result[$result_key][] = $result_value;
            }
        }

        unset($counter);

        return $result;
    }

    /**
     * Utility function to sort an array of objects on a given field
     *
     * @param   array  $a              An array of objects
     * @param   mixed  $k              The key (string) or an array of keys to sort on
     * @param   mixed  $direction      Direction (integer) or an array of direction to sort in [1 = Ascending] [-1 = Descending]
     * @param   mixed  $caseSensitive  Boolean or array of booleans to let sort occur case sensitive or insensitive
     * @param   mixed  $locale         Boolean or array of booleans to let sort occur using the locale language or not
     *
     * @return  array
     *
     * @since   1.0
     */
    public static function sort_objects(array $a, $k, $direction = 1, $case_sensitive = true, $locale = false)
    {
        if (!\is_array($locale) || !\is_array($locale[0])) {
            $locale = [$locale];
        }

        $sort_case      = (array) $case_sensitive;
        $sort_direction = (array) $direction;
        $key           = (array) $k;
        $sort_locale    = $locale;

        usort(
            $a,
            function ($a, $b) use ($sort_case, $sort_direction, $key, $sort_locale) {
                for ($i = 0, $count = \count($key); $i < $count; $i++) {
                    if (isset($sort_direction[$i])) {
                        $direction = $sort_direction[$i];
                    }

                    if (isset($sort_case[$i])) {
                        $case_sensitive = $sort_case[$i];
                    }

                    if (isset($sort_locale[$i])) {
                        $locale = $sort_locale[$i];
                    }

                    $va = $a->{$key[$i]};
                    $vb = $b->{$key[$i]};

                    if ((\is_bool($va) || is_numeric($va)) && (\is_bool($vb) || is_numeric($vb))) {
                        $cmp = $va - $vb;
                    } elseif ($case_sensitive) {
                        $cmp = string_helper::strcmp($va, $vb, $locale);
                    } else {
                        $cmp = string_helper::strcasecmp($va, $vb, $locale);
                    }

                    if ($cmp > 0) {
                        return $direction;
                    }

                    if ($cmp < 0) {
                        return -$direction;
                    }
                }

                return 0;
            }
        );

        return $a;
    }

    /**
     * Multidimensional array safe unique test
     *
     * @param   array  $array  The array to make unique.
     *
     * @return  array
     *
     * @see     https://www.php.net/manual/en/function.array-unique.php
     * @since   1.0
     */
    public static function array_unique(array $array)
    {
        $array = array_map('serialize', $array);
        $array = array_unique($array);
        $array = array_map('unserialize', $array);

        return $array;
    }

    /**
     * An improved array_search that allows for partial matching of strings values in associative arrays.
     *
     * @param   string   $needle         The text to search for within the array.
     * @param   array    $haystack       Associative array to search in to find $needle.
     * @param   boolean  $caseSensitive  True to search case sensitive, false otherwise.
     *
     * @return  mixed    Returns the matching array $key if found, otherwise false.
     *
     * @since   1.0
     */
    public static function array_search($needle, array $haystack, $case_sensitive = true)
    {
        foreach ($haystack as $key => $value) {
            $search_func = ($case_sensitive) ? 'strpos' : 'stripos';

            if ($search_func($value, $needle) === 0) {
                return $key;
            }
        }

        return false;
    }

    /**
     * Method to recursively convert data to a one dimension array.
     *
     * @param   array|object  $array      The array or object to convert.
     * @param   string        $separator  The key separator.
     * @param   string        $prefix     Last level key prefix.
     *
     * @return  array
     *
     * @since   1.3.0
     */
    public static function flatten($array, $separator = '.', $prefix = '')
    {
        if ($array instanceof \Traversable) {
            $array = iterator_to_array($array);
        } elseif (\is_object($array)) {
            $array = get_object_vars($array);
        }

        $result = [];

        foreach ($array as $k => $v) {
            $key = $prefix ? $prefix . $separator . $k : $k;

            if (\is_object($v) || \is_array($v)) {
                $result[] = static::flatten($v, $separator, $key);
            } else {
                $result[] = [$key => $v];
            }
        }

        return array_merge(...$result);
    }

    /**
     * Merge array recursively.
     *
     * @param   array  ...$args  Array list to be merged.
     *
     * @return  array  Merged array.
     *
     * @since   2.0.0
     * @throws  \InvalidArgumentException
     */
    public static function merge_recursive(...$args): array
    {
        $result = [];

        foreach ($args as $i => $array) {
            if (!\is_array($array)) {
                throw new \InvalidArgumentException(sprintf('Argument #%d is not an array.', $i + 2));
            }

            foreach ($array as $key => &$value) {
                if (\is_array($value) && isset($result[$key]) && \is_array($result[$key])) {
                    $result[$key] = static::merge_recursive($result [$key], $value);
                } else {
                    $result[$key] = $value;
                }
            }
        }

        return $result;
    }
}
