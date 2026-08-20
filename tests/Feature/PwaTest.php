<?php

test('pwa web app manifest is accessible and contains required attributes', function () {
    $response = $this->get('/manifest.json');
    $response->assertStatus(200);

    $json = $response->json();
    expect($json['name'])->toBe('Sonakshi Fashion Hub - Royal Ethnic Couture');
    expect($json['short_name'])->toBe('Sonakshi');
    expect($json['display'])->toBe('standalone');
    expect($json['theme_color'])->toBe('#600018');
    expect($json['background_color'])->toBe('#FAF7F2');
    expect(count($json['icons']))->toBeGreaterThanOrEqual(4);
});

test('pwa service worker and offline fallback page are accessible', function () {
    $sw = $this->get('/sw.js');
    $sw->assertStatus(200);
    $sw->assertSee('CACHE_NAME');
    $sw->assertSee('/offline.html');

    $offline = $this->get('/offline.html');
    $offline->assertStatus(200);
    $offline->assertSee('Connection Lost');
    $offline->assertSee('Retry Connection');
});

test('storefront pages include pwa manifest and theme color tags', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
    $response->assertSee('<link rel="manifest" href="/manifest.json"', false);
    $response->assertSee('<meta name="theme-color" content="#600018"', false);
    $response->assertSee('<meta name="apple-mobile-web-app-capable" content="yes"', false);
    $response->assertSee('pwaInstallBanner', false);
});
