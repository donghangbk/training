<?php

namespace App\Http\View\Composer;

use Illuminate\View\View;

class MenuComposer
{
    protected $menuAdmin = [
        ['Dashboard','admin.dashboard', 'fas fa-tachometer-alt'],
        ['User', 'admin.users.index', 'fas fa-users'],
        ['Setting', 'admin.setting.index', 'fas fa-cog']
    ];

    protected $menuUser = [
        ['Dashboard', 'dashboard', 'fas fa-tachometer-alt'],
        ['Timesheet', 'timesheets.index', 'fas fa-tasks'],
        ['Member', 'timesheets.member', 'fas fa-user-friends']
    ];
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $currentMenu = $this->getCurrentMenu();
        $menu = auth()->user()->role_id == 1 ? $this->menuAdmin : $this->menuUser;
        $view->with(compact('menu', 'currentMenu'));
    }

    protected function getCurrentMenu()
    {
        $currentRoute = \Route::currentRouteName();

        $menus = array_merge($this->menuAdmin, $this->menuUser);
        foreach ($menus as $route) {
            if (str_is($route[1], $currentRoute)) {
                return $currentRoute;
            }
        }
        return null;
    }
}
