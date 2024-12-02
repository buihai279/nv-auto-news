<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('crawler_urls', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default("");
            $table->string('url');
            $table->boolean('is_crawled')->default(false);
            $table->tinyText('site');
            $table->mediumText('html');
            $table->json('images')->nullable();
            $table->json('thumbnail')->nullable();
            $table->timestamp('nvgate_publish_at')->nullable();
            $table->timestamp('vfilm_publish_at')->nullable();
            $table->timestamp('mfilm_publish_at')->nullable();
            $table->timestamp('balodi_publish_at')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('crawler_urls');
    }
};
