<?php

namespace Boy132\TawktoWidget\Providers;

use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class TawktoWidgetPluginProvider extends ServiceProvider
{
    public function boot(): void
    {
        $providerId = config('tawkto-widget.provider_id');
        $widgetId = config('tawkto-widget.widget_id');

        if ($providerId && $widgetId) {
            FilamentView::registerRenderHook(
                PanelsRenderHook::STYLES_BEFORE,
                fn () => Blade::render(<<<'HTML'
                <!--Start of Tawk.to Script-->
                <script type="text/javascript">
                    var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
                    (function(){
                        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
                        s1.async=true;
                        s1.src='https://embed.tawk.to/{{ $providerId }}/{{ $widgetId }}';
                        s1.charset='UTF-8';
                        s1.setAttribute('crossorigin','*');
                        s0.parentNode.insertBefore(s1,s0);
                    })();
                </script>
                <!--End of Tawk.to Script-->
            HTML, [
                    'providerId' => $providerId,
                    'widgetId' => $widgetId,
                ])
            );
        }
    }
}
