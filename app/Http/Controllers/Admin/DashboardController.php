<?php

namespace App\Http\Controllers\Admin;

use App\Models\Character;
use App\Models\EquipmentItem;
use App\Models\Skill;
use App\Models\User;
use App\Models\Weapon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends AdminController
{
    /**
     * The admin landing page: what the admin's group currently holds, plus the
     * empty state that explains how to get a group when they have none.
     */
    public function index(Request $request): Response
    {
        $group = $this->currentGroup($request);

        return Inertia::render('Admin/Index', [
            'group' => $group === null ? null : [
                'id'   => $group->id,
                'name' => $group->name,
                'era'  => $group->era->value,
            ],
            'counts' => [
                'members'     => $group === null ? 0 : User::query()->where('group_id', $group->id)->count(),
                'characters'  => $group === null ? 0 : Character::query()->where('group_id', $group->id)->count(),
                'invitations' => $group === null ? 0 : $group->pendingInvitations()->count(),
                'skills'      => Skill::query()->count(),
                'weapons'     => Weapon::query()->count(),
                'equipment'   => EquipmentItem::query()->count(),
            ],
            'referenceDataEditable' => $this->referenceDataIsEditable(),
        ]);
    }
}
