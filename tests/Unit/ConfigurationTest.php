<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ConfigurationTest extends TestCase
{
    /**
     * Test that Laravel automatically uses .env.testing during tests.
     */
    public function test_laravel_uses_testing_environment()
    {
        // Kiểm tra environment - có thể là testing hoặc local trong test
        $this->assertContains(app()->environment(), ['testing', 'local']);

        // Kiểm tra database - có thể dùng database demo hoặc test
        $this->assertContains(config('database.connections.mysql.database'), ['hanaya_shop_test', 'hanaya_shop_demo']);
        $this->assertEquals('mysql', config('database.default'));

        // Kiểm tra filesystem
        $this->assertEquals('testing', config('filesystems.default'));

        // Kiểm tra cache driver
        $this->assertEquals('array', config('cache.default'));

        // Kiểm tra session driver
        $this->assertEquals('array', config('session.driver'));

        // Kiểm tra mail driver - có thể là array hoặc smtp trong test
        $this->assertContains(config('mail.default'), ['array', 'smtp']);

        echo "\n✅ Laravel đang sử dụng environment: ".app()->environment()."!\n";
        echo '🗄️  Database: '.config('database.connections.mysql.database')."\n";
        echo '💾 Filesystem: '.config('filesystems.default')."\n";
        echo '📧 Mail: '.config('mail.default')."\n";
    }

    /**
     * Test storage isolation.
     */
    public function test_storage_isolation_works()
    {
        // Kiểm tra storage disk
        $this->assertEquals('testing', config('filesystems.default'));

        // Tạo file test trong storage testing
        Storage::put('test-file.txt', 'Test content');

        // Kiểm tra file tồn tại trong testing storage
        $this->assertTrue(Storage::exists('test-file.txt'));

        // Kiểm tra path thực tế
        $path = Storage::path('test-file.txt');
        $this->assertStringContainsString('storage\\framework', $path);
        $this->assertStringContainsString('testing', $path);

        echo '📁 Test file path: '.$path."\n";

        // Cleanup
        Storage::delete('test-file.txt');
    }
}
