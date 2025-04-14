<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Multitenancy\Models\Tenant as BaseTenant;
use Spatie\Multitenancy\Events\TenantCreated;

class Tenant extends Model
{
    protected $fillable = ['name', 'domain', 'database'];

    protected static function booted()
    {
        static::created(function ($tenant) {
            // Auto-create tenant DB
            \DB::statement("CREATE DATABASE IF NOT EXISTS `{$tenant->database}`");

            // Run migrations on tenant DB
            Artisan::call('tenants:artisan', [
                'artisanCommand' => 'migrate',
                '--tenant' => [$tenant->id],
            ]);
        });
    }
}
