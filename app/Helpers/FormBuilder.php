<?php

namespace App\Helpers;

class FormBuilder
{
    protected static $model;

    protected static function attributes(array $options): string
    {
        $html = '';
        foreach ($options as $key => $value) {
            if (is_bool($value)) {
                if ($value) {
                    $html .= ' ' . e($key);
                }
            } else {
                $html .= ' ' . e($key) . '="' . e($value) . '"';
            }
        }
        return $html;
    }

    protected static function renderInput(string $type, string $name, $value = null, array $options = []): string
    {
        $value = $value ?? old($name, self::$model ? data_get(self::$model, $name) : null);
        return '<input type="' . e($type) . '" name="' . e($name) . '" value="' . e($value) . '"' . self::attributes($options) . '>';
    }

    public static function open(array $options = []): string
    {
        $method = 'POST';
        $url = '';
        $files = false;

        if (isset($options['method'])) {
            $method = strtoupper($options['method']);
            unset($options['method']);
        }
        if (isset($options['url'])) {
            $url = $options['url'];
            unset($options['url']);
        }
        if (isset($options['files']) && $options['files']) {
            $files = true;
            unset($options['files']);
        }
        if (isset($options['route'])) {
            $url = route($options['route'][0], array_slice($options['route'], 1));
            unset($options['route']);
        }

        $html = '<form method="' . ($method === 'GET' ? 'GET' : 'POST') . '"';
        $html .= ' action="' . e($url) . '"';
        if ($files) {
            $html .= ' enctype="multipart/form-data"';
        }
        $html .= self::attributes($options) . '>';

        if (!in_array($method, ['GET', 'POST'])) {
            $html .= '<input type="hidden" name="_method" value="' . e($method) . '">';
        }
        if ($method !== 'GET') {
            $html .= csrf_field();
        }

        return $html;
    }

    public static function model($model, array $options = []): string
    {
        self::$model = $model;
        return self::open($options);
    }

    public static function close(): string
    {
        self::$model = null;
        return '</form>';
    }

    public static function input(string $type, string $name, $value = null, array $options = []): string
    {
        return self::renderInput($type, $name, $value, $options);
    }

    public static function text(string $name, $value = null, array $options = []): string
    {
        return self::renderInput('text', $name, $value, $options);
    }

    public static function email(string $name, $value = null, array $options = []): string
    {
        return self::renderInput('email', $name, $value, $options);
    }

    public static function password(string $name, array $options = []): string
    {
        return self::renderInput('password', $name, null, $options);
    }

    public static function hidden(string $name, $value = null, array $options = []): string
    {
        return self::renderInput('hidden', $name, $value, $options);
    }

    public static function file(string $name, $value = null, array $options = []): string
    {
        return self::renderInput('file', $name, $value, $options);
    }

    public static function number(string $name, $value = null, array $options = []): string
    {
        return self::renderInput('number', $name, $value, $options);
    }

    public static function date(string $name, $value = null, array $options = []): string
    {
        return self::renderInput('date', $name, $value, $options);
    }

    public static function radio(string $name, $value = null, $checked = false, array $options = []): string
    {
        $modelValue = self::$model ? data_get(self::$model, $name) : null;
        if ($modelValue !== null && (string) $modelValue === (string) $value) {
            $options['checked'] = 'checked';
        } elseif ($checked) {
            $options['checked'] = 'checked';
        }
        return '<input type="radio" name="' . e($name) . '" value="' . e($value) . '"' . self::attributes($options) . '>';
    }

    public static function textarea(string $name, $value = null, array $options = []): string
    {
        $value = $value ?? old($name, self::$model ? data_get(self::$model, $name) : null);
        return '<textarea name="' . e($name) . '"' . self::attributes($options) . '>' . e($value) . '</textarea>';
    }

    public static function select(string $name, $list = [], $selected = null, array $options = []): string
    {
        if ($list instanceof \Illuminate\Support\Collection) {
            $list = $list->all();
        }
        $selected = $selected ?? old($name, self::$model ? data_get(self::$model, $name) : null);
        $html = '<select name="' . e($name) . '"' . self::attributes($options) . '>';
        foreach ($list as $key => $value) {
            $sel = ($key == $selected) ? ' selected' : '';
            $html .= '<option value="' . e($key) . '"' . $sel . '>' . e($value) . '</option>';
        }
        $html .= '</select>';
        return $html;
    }

    public static function submit($value = null, array $options = []): string
    {
        return '<button type="submit"' . self::attributes($options) . '>' . e($value) . '</button>';
    }

    public static function label(string $name, $value = null, array $options = []): string
    {
        return '<label for="' . e($name) . '"' . self::attributes($options) . '>' . e($value ?? $name) . '</label>';
    }
}
