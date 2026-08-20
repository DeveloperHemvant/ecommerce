<?php

test('company and information pages render successfully', function () {
    // About Us
    $about = $this->get('/about-us');
    $about->assertStatus(200);
    $about->assertSee('Our Royal Heritage');
    $about->assertSee('The Atelier Legacy');

    // Contact Us
    $contact = $this->get('/contact-us');
    $contact->assertStatus(200);
    $contact->assertSee('Connect with Our Stylists');
    $contact->assertSee('Flagship Atelier');
    $contact->assertSee('Send an Inquiry');
});

test('legal and customer policy pages render successfully', function () {
    // Shipping Policy
    $shipping = $this->get('/shipping-policy');
    $shipping->assertStatus(200);
    $shipping->assertSee('Shipping & Delivery Policy');
    $shipping->assertSee('Domestic Delivery Timelines');

    // Terms of Service
    $terms = $this->get('/terms-of-service');
    $terms->assertStatus(200);
    $terms->assertSee('Terms of Service');
    $terms->assertSee('Handloom Authenticity');

    // Returns Policy
    $returns = $this->get('/return-policy');
    $returns->assertStatus(200);
    $returns->assertSee('Returns & Exchange Policy');
    $returns->assertSee('7-Day Exchange Window');

    // Privacy Policy
    $privacy = $this->get('/privacy-policy');
    $privacy->assertStatus(200);
    $privacy->assertSee('Privacy Policy');
    $privacy->assertSee('Information We Collect');
});

test('contact inquiry form submission succeeds with validation', function () {
    $response = $this->post('/contact-us', [
        'name' => 'Pooja Verma',
        'email' => 'pooja@example.com',
        'phone' => '+91 9988776655',
        'subject' => 'Bespoke Bridal Sizing & Measurements',
        'message' => 'I am looking for a custom size Banarasi Lacha for my reception on Dec 12.',
    ]);

    $response->assertSessionHas('success');
});
