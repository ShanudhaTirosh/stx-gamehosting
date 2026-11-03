<?php

namespace BrandedHosting\Platform\Providers;

use BrandedHosting\Platform\Http\Controllers\CheckoutController;
use BrandedHosting\Platform\Http\Controllers\KnowledgeBaseController;
use BrandedHosting\Platform\Http\Controllers\OrderTrackingController;
use BrandedHosting\Platform\Http\Controllers\PaymentProofController;
use BrandedHosting\Platform\Http\Controllers\SeoController;
use BrandedHosting\Platform\Http\Controllers\StatusPageController;
use BrandedHosting\Platform\Http\Controllers\StorefrontController;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class HostingRoutesServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->routes(function () {
            // Old client-panel URLs (before the panel moved off the site root) keep working.
            if (config('panel.client_path', 'app') !== '') {
                Route::middleware(['web'])->group(function () {
                    Route::get('/login', fn () => redirect()->route('filament.app.auth.login'));
                    Route::get('/app', fn () => redirect('/app/servers'));
                    foreach (['customer-orders', 'account'] as $legacy) {
                        Route::get('/' . $legacy, fn () => redirect(url(trim(config('panel.client_path', 'app') . '/' . $legacy, '/'))));
                    }
                });
            }

            Route::middleware(['web'])->group(function () {
                // Public Storefront & Core Pages
                Route::get('/', [StorefrontController::class, 'index'])->name('hosting.storefront.index');
                Route::get('/games', [StorefrontController::class, 'games'])->name('hosting.storefront.games');
                Route::get('/about', [StorefrontController::class, 'about'])->name('hosting.storefront.about');
                Route::get('/contact', [StorefrontController::class, 'contact'])->name('hosting.storefront.contact');
                Route::post('/contact', [StorefrontController::class, 'submitContact'])->middleware('throttle:5,10')->name('hosting.storefront.contact.submit');
                Route::get('/terms', [StorefrontController::class, 'terms'])->name('hosting.storefront.terms');
                Route::get('/privacy', [StorefrontController::class, 'privacy'])->name('hosting.storefront.privacy');

                // Dynamic XML Sitemap & Search Engine Robots.txt
                Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('hosting.seo.sitemap');
                Route::get('/robots.txt', [SeoController::class, 'robots'])->name('hosting.seo.robots');

                // Category Showcases
                Route::get('/hosting/{slug}', [StorefrontController::class, 'category'])->name('hosting.storefront.category');

                // Customer Knowledge Base & In-Dashboard Help Desk
                Route::get('/help', [KnowledgeBaseController::class, 'index'])->name('hosting.kb.index');
                Route::get('/knowledgebase', [KnowledgeBaseController::class, 'index'])->name('hosting.kb.index_alt');
                Route::get('/knowledgebase/category/{slug}', [KnowledgeBaseController::class, 'category'])->name('hosting.kb.category');
                Route::get('/knowledgebase/article/{category}/{article}', [KnowledgeBaseController::class, 'article'])->name('hosting.kb.article');
                Route::get('/api/knowledgebase/search', [KnowledgeBaseController::class, 'apiSearch'])->middleware('throttle:60,1')->name('hosting.kb.api_search');

                // Automated Live Status Page & Telemetry
                Route::get('/status', [StatusPageController::class, 'index'])->name('hosting.status.index');
                Route::get('/status/data', [StatusPageController::class, 'data'])->middleware('throttle:60,1')->name('hosting.status.data');
                Route::get('/status/monitor/{slug}', [StatusPageController::class, 'monitorDetails'])->name('hosting.status.monitor');
                Route::get('/status/incidents', [StatusPageController::class, 'incidents'])->name('hosting.status.incidents');
                Route::get('/status/incident/{id}', [StatusPageController::class, 'incidentDetails'])->name('hosting.status.incident_details');
                Route::get('/api/status/ping', [StatusPageController::class, 'ping'])->middleware('throttle:60,1')->name('hosting.status.ping');

                // Checkout & Ordering
                Route::get('/checkout/{slug}', [CheckoutController::class, 'show'])->name('hosting.checkout.show');
                Route::post('/checkout/{slug}', [CheckoutController::class, 'process'])->middleware('throttle:10,10')->name('hosting.checkout.process');

                // Order Tracking, Invoice & Manual Payment Proof
                Route::get('/order/status/{uuid}', [OrderTrackingController::class, 'show'])->name('hosting.order.tracking');
                Route::get('/order/invoice/{uuid}', [OrderTrackingController::class, 'invoice'])->name('hosting.order.invoice');
                Route::post('/order/status/{uuid}/proof', [OrderTrackingController::class, 'submitProof'])->middleware('throttle:10,10')->name('hosting.order.submit_proof');

                // Client Panel Order & Invoice Routes (all client operations under /app/)
                Route::get('/app/order/{uuid}', [OrderTrackingController::class, 'show'])->name('hosting.app.order.tracking');
                Route::get('/app/invoice/{uuid}', [OrderTrackingController::class, 'invoice'])->name('hosting.app.order.invoice');
                Route::post('/app/order/{uuid}/proof', [OrderTrackingController::class, 'submitProof'])->middleware('throttle:10,10')->name('hosting.app.order.submit_proof');

                // Secure File Download Stream for Proofs
                Route::get('/order/proof/{proof}/download', [PaymentProofController::class, 'download'])->middleware('auth')->name('hosting.proof.download');
            });
        });
    }
}
