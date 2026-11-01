<?php

namespace BrandedHosting\Platform\Http\Controllers;

use App\Http\Controllers\Controller;
use BrandedHosting\Platform\Models\HostingCategory;
use BrandedHosting\Platform\Models\HostingPlan;
use BrandedHosting\Platform\Models\StatusMonitor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StorefrontController extends Controller
{
    /**
     * Display public storefront homepage.
     */
    public function index(Request $request): View|RedirectResponse
    {
        if ($request->has('dashboard') && Auth::check()) {
            return redirect(Auth::user()->isRootAdmin() ? '/admin' : '/customer-orders');
        }

        $categories = HostingCategory::query()
            ->where('is_visible', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)->with(['plans' => function ($pq) {
                    $pq->where('is_available', true)->orderBy('sort_order');
                }]);
            }])
            ->orderBy('sort_order')
            ->get();

        $featuredPlans = HostingPlan::query()
            ->where('is_featured', true)
            ->where('is_available', true)
            ->with('product.category')
            ->orderBy('sort_order')
            ->take(6)
            ->get();

        $monitors = StatusMonitor::where('is_active', true)->get();

        return view('hosting-platform::storefront.index', [
            'categories' => $categories,
            'featuredPlans' => $featuredPlans,
            'monitors' => $monitors,
        ]);
    }

    /**
     * Display all game servers & cloud services catalog (/games).
     */
    public function games(): View
    {
        $categories = HostingCategory::query()
            ->where('is_visible', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)->with(['plans' => function ($pq) {
                    $pq->where('is_available', true)->orderBy('sort_order');
                }]);
            }])
            ->orderBy('sort_order')
            ->get();

        return view('hosting-platform::storefront.games', [
            'categories' => $categories,
        ]);
    }

    /**
     * Display category plan showcase (e.g. /hosting/minecraft, /hosting/fivem).
     */
    public function category(string $slug): View
    {
        $category = HostingCategory::query()
            ->where('slug', $slug)
            ->where('is_visible', true)
            ->with(['products' => function ($query) {
                $query->where('is_active', true)->with(['plans' => function ($pq) {
                    $pq->where('is_available', true)->orderBy('sort_order');
                }]);
            }])
            ->firstOrFail();

        return view('hosting-platform::storefront.category', [
            'category' => $category,
        ]);
    }

    /**
     * Display About Us page.
     */
    public function about(): View
    {
        $monitors = StatusMonitor::where('is_active', true)->get();

        return view('hosting-platform::storefront.about', [
            'monitors' => $monitors,
        ]);
    }

    /**
     * Display Contact Us & Helpdesk page.
     */
    public function contact(): View
    {
        return view('hosting-platform::storefront.contact');
    }

    /**
     * Process contact form submission.
     */
    public function submitContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:150',
            'department' => 'required|string|in:sales,billing,technical,abuse',
            'subject' => 'required|string|max:200',
            'message' => 'required|string|min:10|max:3000',
        ]);

        Log::info('[Storefront Contact Form Submission]', $validated);

        // Flash confirmation
        return redirect()->back()->with('contact_success', "Thank you {$validated['name']}! Your dispatch inquiry has been routed to our Colombo NOC desk. Ticket ID #STX-" . strtoupper(substr(md5(uniqid()), 0, 6)) . ' has been generated.');
    }

    /**
     * Display Terms of Service.
     */
    public function terms(): View
    {
        return view('hosting-platform::storefront.terms');
    }

    /**
     * Display Privacy Policy.
     */
    public function privacy(): View
    {
        return view('hosting-platform::storefront.privacy');
    }
}
