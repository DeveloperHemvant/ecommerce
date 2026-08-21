# Graph Report - .  (2026-08-21)

## Corpus Check
- 173 files · ~59,062 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 532 nodes · 945 edges · 108 communities (104 shown, 4 thin omitted)
- Extraction: 98% EXTRACTED · 2% INFERRED · 0% AMBIGUOUS · INFERRED: 18 edges (avg confidence: 0.8)
- Token cost: 0 input · 0 output

## Community Hubs (Navigation)
- Customer Account & Auth
- Coupons & Categories (Admin)
- Composer Package Config
- Admin Dashboard & Exports
- Order/Contact Email Notifications
- Composer Scripts
- Cart & Checkout Flow
- Product Catalog (Storefront)
- PWA Manifest
- NPM/Vite Frontend Build
- YouTube CMS & Collections
- Contact Message Admin
- Admin Auth Middleware
- App Service Provider
- Test Harness
- Service Worker (PWA)

## God Nodes (most connected - your core abstractions)
1. `Product` - 48 edges
2. `Controller` - 36 edges
3. `User` - 31 edges
4. `Order` - 29 edges
5. `Category` - 25 edges
6. `YouTubeVideo` - 22 edges
7. `Coupon` - 20 edges
8. `Review` - 18 edges
9. `CheckoutController` - 15 edges
10. `CartSyncService` - 14 edges

## Surprising Connections (you probably didn't know these)
- `ContactMessageController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/ContactMessageController.php → app/Http/Controllers/Controller.php
- `CouponController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/CouponController.php → app/Http/Controllers/Controller.php
- `DashboardController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/DashboardController.php → app/Http/Controllers/Controller.php
- `ExportController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/ExportController.php → app/Http/Controllers/Controller.php
- `ProductController` --inherits--> `Controller`  [EXTRACTED]
  app/Http/Controllers/Admin/ProductController.php → app/Http/Controllers/Controller.php

## Import Cycles
- None detected.

## Communities (108 total, 4 thin omitted)

### Community 0 - "Customer Account & Auth"
Cohesion: 0.06
Nodes (18): AccountController, AuthController, CategoryController, CustomerController, OrderController, ReviewController, CustomerAuthController, CartController (+10 more)

### Community 1 - "Coupons & Categories (Admin)"
Cohesion: 0.08
Nodes (12): CouponController, Coupon, OrderItem, Review, Wishlist, DatabaseSeeder, Illuminate\Database\Console\Seeds\WithoutModelEvents, Illuminate\Database\Eloquent\Factories\HasFactory (+4 more)

### Community 2 - "Composer Package Config"
Cohesion: 0.05
Nodes (43): pestphp/pest-plugin, php-http/discovery, autoload, autoload-dev, psr-4, psr-4, config, allow-plugins (+35 more)

### Community 3 - "Admin Dashboard & Exports"
Cohesion: 0.08
Nodes (11): DashboardController, ExportController, Category, User, UserFactory, Illuminate\Database\Eloquent\Factories\Factory, Illuminate\Database\Eloquent\Relations\HasMany, Illuminate\Foundation\Auth\User (+3 more)

### Community 4 - "Order/Contact Email Notifications"
Cohesion: 0.12
Nodes (9): ContactMessageReceived, OrderConfirmation, OrderStatusUpdated, Order, Illuminate\Bus\Queueable, Illuminate\Mail\Mailable, Illuminate\Mail\Mailables\Content, Illuminate\Mail\Mailables\Envelope (+1 more)

### Community 5 - "Composer Scripts"
Cohesion: 0.08
Nodes (27): scripts, dev, post-autoload-dump, post-create-project-cmd, post-root-package-install, post-update-cmd, pre-package-uninstall, setup (+19 more)

### Community 6 - "Cart & Checkout Flow"
Cohesion: 0.12
Nodes (5): CheckoutController, CartItem, CartSyncService, RazorpayService, Razorpay\Api\Api

### Community 7 - "Product Catalog (Storefront)"
Cohesion: 0.11
Nodes (4): ProductController, ProductDetailController, Product, Tag

### Community 8 - "PWA Manifest"
Cohesion: 0.09
Nodes (22): background_color, categories, description, dir, display, display_override, icons, id (+14 more)

### Community 9 - "NPM/Vite Frontend Build"
Cohesion: 0.10
Nodes (19): axios, concurrently, laravel-vite-plugin, devDependencies, axios, concurrently, laravel-vite-plugin, tailwindcss (+11 more)

### Community 10 - "YouTube CMS & Collections"
Cohesion: 0.16
Nodes (4): YouTubeVideoController, CollectionsController, HomeController, YouTubeVideo

### Community 12 - "Admin Auth Middleware"
Cohesion: 0.47
Nodes (3): EnsureUserIsAdmin, Closure, Symfony\Component\HttpFoundation\Response

## Knowledge Gaps
- **81 isolated node(s):** `$schema`, `name`, `type`, `description`, `laravel` (+76 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **4 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `Product` connect `Product Catalog (Storefront)` to `Customer Account & Auth`, `Coupons & Categories (Admin)`, `Admin Dashboard & Exports`, `Cart & Checkout Flow`, `YouTube CMS & Collections`?**
  _High betweenness centrality (0.043) - this node is a cross-community bridge._
- **Why does `Order` connect `Order/Contact Email Notifications` to `Customer Account & Auth`, `Coupons & Categories (Admin)`, `Admin Dashboard & Exports`, `Cart & Checkout Flow`?**
  _High betweenness centrality (0.040) - this node is a cross-community bridge._
- **Why does `User` connect `Admin Dashboard & Exports` to `Customer Account & Auth`, `Coupons & Categories (Admin)`, `Contact Message Admin`?**
  _High betweenness centrality (0.038) - this node is a cross-community bridge._
- **What connects `$schema`, `name`, `type` to the rest of the system?**
  _81 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Customer Account & Auth` be split into smaller, more focused modules?**
  _Cohesion score 0.060424469413233456 - nodes in this community are weakly interconnected._
- **Should `Coupons & Categories (Admin)` be split into smaller, more focused modules?**
  _Cohesion score 0.08421985815602837 - nodes in this community are weakly interconnected._
- **Should `Composer Package Config` be split into smaller, more focused modules?**
  _Cohesion score 0.045454545454545456 - nodes in this community are weakly interconnected._