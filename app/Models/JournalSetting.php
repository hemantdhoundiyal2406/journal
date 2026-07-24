<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JournalSetting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'type',
        'group',
    ];

    /**
     * Get setting value by key with optional default fallback
     */
    public static function getByKey(string $key, mixed $default = null): mixed
    {
        $setting = static::where('key', $key)->first();
        if (!$setting) {
            return $default;
        }
        return $setting->value ?? $default;
    }

    /**
     * Set or update setting value
     */
    public static function setKey(string $key, mixed $value, string $group = 'general', string $type = 'string'): void
    {
        static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'group' => $group,
                'type' => $type,
            ]
        );
    }
}
