<?php

namespace App\Policies;

use App\Enums\PageModulesEnum;
use App\Models\CustomRole;
use App\Models\Page;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return $user->canAny(['manage.*', 'manage.page']);
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Page $page)
    {
        return $user->canAny(['manage.*', 'manage.page']);
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->canAny(['manage.*', 'manage.page']);
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Page $page)
    {
        if($page->module_enum){
            return $user->canAny(['manage.*', 'manage.page'])
                || ($page->module_enum == PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value) //TODO add role for ОВ
                || ($page->module_enum == PageModulesEnum::MODULE_OGP->value && $user->hasRole([CustomRole::MODERATOR_PARTNERSHIP]));
        }

        return $user->canAny(['manage.*', 'manage.page'])
            || (
                in_array($page->system_name, [Page::ADV_BOARD_DOCUMENTS, Page::ADV_BOARD_INFO])
                && $user->hasRole([CustomRole::MODERATOR_ADVISORY_BOARDS])
            )
            || (
                in_array($page->system_name, [Page::STRATEGIC_DOCUMENT_DOCUMENTS, Page::STRATEGIC_DOCUMENT_INFO])
                && $user->hasRole([CustomRole::MODERATOR_STRATEGIC_DOCUMENTS])
            )
            || (
                in_array($page->system_name, [Page::OGP_INFO])
                && $user->hasRole([CustomRole::MODERATOR_PARTNERSHIP])
            )
            || (
                in_array($page->system_name, [Page::LEGISLATIVE_INITIATIVE_INFO])
                && $user->can('manage.legislative_initiatives')
            );
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Page $page)
    {
        if($page->module_enum){
            return $user->canAny(['manage.*', 'manage.page'])
                || ($page->module_enum == PageModulesEnum::MODULE_IMPACT_ASSESSMENT->value) //TODO add role for ОВ
                || ($page->module_enum == PageModulesEnum::MODULE_OGP->value && $user->hasRole([CustomRole::MODERATOR_PARTNERSHIP]));
        }

        return $user->canAny(['manage.*', 'manage.page'])
            || (
                in_array($page->system_name, [Page::ADV_BOARD_DOCUMENTS, Page::ADV_BOARD_INFO])
                && $user->hasRole([CustomRole::MODERATOR_ADVISORY_BOARDS])
            )
            || (
                in_array($page->system_name, [Page::STRATEGIC_DOCUMENT_DOCUMENTS, Page::STRATEGIC_DOCUMENT_INFO])
                && $user->hasRole([CustomRole::MODERATOR_STRATEGIC_DOCUMENTS])
            )
            || (
                in_array($page->system_name, [Page::OGP_INFO])
                && $user->hasRole([CustomRole::MODERATOR_PARTNERSHIP])
            );
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Page $page)
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Page  $page
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Page $page)
    {
        return false;
    }
}
