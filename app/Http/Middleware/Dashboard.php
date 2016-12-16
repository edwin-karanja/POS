<?php
namespace App\Http\Middleware;

use Closure;

class Dashboard
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->registerSidebarMenu();
        return $next($request);
    }
    protected function registerSidebarMenu()
    {
        $menu = app('laravel_dashboard')->getSidebarMenu();

        $assetSubMenu = app('menu.manager')->createMenu('Custom assets')
            ->addLink('Assets in the header', ['route' => ''], ['before' => '<i class="fa fa-circle-o"></i>'])
            ->addLink('Assets in the footer', ['route' => ''], ['before' => '<i class="fa fa-circle-o"></i>']);
        $cusSubMenu = app('menu.manager')->createMenu('View Customization')
            ->addLink('General', ['route' => ''], ['before' => '<i class="fa fa-circle-o"></i>'])
            ->addLink('Logo', ['route' => ''], ['before' => '<i class="fa fa-circle-o"></i>'])
            ->addLink('Top bar', ['route' => ''], ['before' => '<i class="fa fa-circle-o"></i>'])
            ->addLink('Sidebar', ['route' => ''], ['before' => '<i class="fa fa-circle-o"></i>'])
            ->addLink('Control Sidebar', ['route' => ''], ['before' => '<i class="fa fa-circle-o"></i>'])
            ->addLink('Footer', ['route' => ''], ['before' => '<i class="fa fa-circle-o"></i>'])
            ->addSubMenu($assetSubMenu, ['before' => '<i class="fa fa-asterisk"></i>', 'url_def' => ['route_pattern' => '']]);
        $menu->setLabel('Main Sidebar')
            ->addLink('Dashboard', ['route' => 'home'], ['before' => '<i class="fa fa-home"></i>'])
            ->addLink('Stocks', ['route' => 'items'], ['before' => '<i class="fa fa-server"></i>'])
            ->addLink('Categories', ['route' => 'categories'], ['before' => '<i class="fa fa-bookmark"></i>'])
            ->addLink('Customers', ['route' => 'customers'], ['before' => '<i class="fa fa-users"></i>'])
            ->addLink('Suppliers', ['route' => 'suppliers.index'], ['before' => '<i class="fa fa-users"></i>'])
            ->addLink('Sales', ['route' => 'sales.index'], ['before' => '<i class="fa fa-shopping-bag"></i>'])
            ->addLink('Invoices', ['route' => ''], ['before' => '<i class="fa fa-money"></i>'])
            ->addSubMenu($cusSubMenu, ['before' => '<i class="fa fa-street-view"></i>', 'url_def' => ['route_pattern' => 'customise.*']])
            ->addLink('Settings', ['route' => 'settings'], ['before' => '<i class="fa fa-cog"></i>'])
            ->addLink('GitHub', ['to' => 'https://github.com/letrunghieu/laravel-dashboard'], ['before' => '<i class="fa fa-github"></i>']);
    }
}