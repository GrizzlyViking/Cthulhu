<?php

use App\Enums\Era;
use App\Mail\InvitationMail;
use App\Models\Character;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

function createSessionFor(User $user): void
{
    DB::table('sessions')->insert([
        'id'            => Str::random(40),
        'user_id'       => $user->id,
        'ip_address'    => '127.0.0.1',
        'user_agent'    => 'Pest',
        'payload'       => base64_encode(serialize([])),
        'last_activity' => now()->timestamp,
    ]);
}

describe('group:create', function () {
    it('creates a group from arguments', function () {
        $this->artisan('group:create', ['name' => 'Arkham Irregulars', '--era' => 'modern'])
            ->expectsOutputToContain('Group [Arkham Irregulars] created (era: modern).')
            ->assertExitCode(0);

        $group = Group::where('name', 'Arkham Irregulars')->first();

        expect($group)->not->toBeNull()
            ->and($group->era)->toBe(Era::Modern);
    });

    it('prompts for name and era when omitted', function () {
        $this->artisan('group:create')
            ->expectsQuestion('Group name', 'Miskatonic Society')
            ->expectsQuestion('Era', '1920s')
            ->assertExitCode(0);

        expect(Group::where('name', 'Miskatonic Society')->first()->era)->toBe(Era::Twenties);
    });

    it('refuses a duplicate group name', function () {
        Group::factory()->create(['name' => 'Arkham Irregulars']);

        $this->artisan('group:create', ['name' => 'Arkham Irregulars', '--era' => 'modern'])
            ->expectsOutputToContain('already exists')
            ->assertExitCode(1);

        expect(Group::where('name', 'Arkham Irregulars')->count())->toBe(1);
    });

    it('rejects an invalid era', function () {
        $this->artisan('group:create', ['name' => 'Timeless', '--era' => 'medieval'])
            ->expectsOutputToContain('Valid eras are: 1920s, modern.')
            ->assertExitCode(1);

        expect(Group::where('name', 'Timeless')->exists())->toBeFalse();
    });
});

describe('group:list', function () {
    it('renders groups with user and pending invitation counts', function () {
        $group = Group::factory()->create(['name' => 'Innsmouth Watch', 'era' => Era::Twenties]);
        User::factory()->count(2)->inGroup($group)->create();
        Invitation::factory()->create(['group_id' => $group->id]);
        Invitation::factory()->accepted()->create(['group_id' => $group->id]);

        $this->artisan('group:list')
            ->expectsTable(
                ['Id', 'Name', 'Era', 'Users', 'Pending invitations'],
                [[$group->id, 'Innsmouth Watch', '1920s', 2, 1]],
            )
            ->assertExitCode(0);
    });

    it('mentions when no groups exist', function () {
        $this->artisan('group:list')
            ->expectsOutputToContain('No groups exist yet.')
            ->assertExitCode(0);
    });
});

describe('player:invite', function () {
    it('sends the invitation mail and prints the accept URL', function () {
        Mail::fake();

        $group = Group::factory()->create(['name' => 'Dunwich Circle']);

        $this->artisan('player:invite', ['email' => 'newcomer@example.com', 'group' => 'Dunwich Circle'])
            ->expectsOutputToContain('Invitation emailed to [newcomer@example.com]')
            ->assertExitCode(0);

        $invitation = Invitation::where('email', 'newcomer@example.com')->first();

        expect($invitation)->not->toBeNull()
            ->and($invitation->group_id)->toBe($group->id);

        Mail::assertSent(InvitationMail::class, fn (InvitationMail $mail): bool => $mail->hasTo('newcomer@example.com'));
    });

    it('prints the accept URL for manual delivery', function () {
        Mail::fake();

        Group::factory()->create(['name' => 'Dunwich Circle']);

        $this->artisan('player:invite', ['email' => 'newcomer@example.com', 'group' => 'Dunwich Circle'])
            ->expectsOutputToContain('Accept URL (for manual delivery): http')
            ->assertExitCode(0);

        $invitation = Invitation::where('email', 'newcomer@example.com')->first();

        expect($invitation)->not->toBeNull();
    });

    it('links the accept URL to the created invitation token', function () {
        Mail::fake();

        Group::factory()->create(['name' => 'Dunwich Circle']);

        Artisan::call('player:invite', [
            'email' => 'newcomer@example.com',
            'group' => 'Dunwich Circle',
        ]);

        $invitation = Invitation::where('email', 'newcomer@example.com')->first();

        expect(Artisan::output())->toContain(route('invitation.show', $invitation->token));
    });

    it('records the first admin as the inviter', function () {
        Mail::fake();

        User::factory()->create(['role' => 'player']);
        $admin = User::factory()->create(['role' => 'admin']);
        Group::factory()->create(['name' => 'Dunwich Circle']);

        $this->artisan('player:invite', ['email' => 'newcomer@example.com', 'group' => 'Dunwich Circle'])
            ->assertExitCode(0);

        expect(Invitation::where('email', 'newcomer@example.com')->first()->invited_by)->toBe($admin->id);
    });

    it('leaves the inviter empty when no admin exists', function () {
        Mail::fake();

        Group::factory()->create(['name' => 'Dunwich Circle']);

        $this->artisan('player:invite', ['email' => 'newcomer@example.com', 'group' => 'Dunwich Circle'])
            ->assertExitCode(0);

        expect(Invitation::where('email', 'newcomer@example.com')->first()->invited_by)->toBeNull();
    });

    it('shows a friendly error when the email already belongs to a user', function () {
        Mail::fake();

        User::factory()->create(['email' => 'veteran@example.com']);
        Group::factory()->create(['name' => 'Dunwich Circle']);

        $this->artisan('player:invite', ['email' => 'veteran@example.com', 'group' => 'Dunwich Circle'])
            ->expectsOutputToContain('already exists. Use player:assign')
            ->assertExitCode(1);

        Mail::assertNothingSent();
        expect(Invitation::where('email', 'veteran@example.com')->exists())->toBeFalse();
    });

    it('fails when the group cannot be found', function () {
        Mail::fake();

        $this->artisan('player:invite', ['email' => 'newcomer@example.com', 'group' => 'Nonexistent'])
            ->expectsOutputToContain('No group found with name or id [Nonexistent].')
            ->assertExitCode(1);

        Mail::assertNothingSent();
    });
});

describe('player:assign', function () {
    it('moves an ungrouped user and their characters into the group', function () {
        $group     = Group::factory()->create(['name' => 'Kingsport Lodge']);
        $user      = User::factory()->create();
        $character = Character::factory()->create(['user_id' => $user->id]);

        $this->artisan('player:assign', ['email' => $user->email, 'group' => 'Kingsport Lodge'])
            ->expectsOutputToContain('assigned to group [Kingsport Lodge]')
            ->assertExitCode(0);

        expect($user->fresh()->group_id)->toBe($group->id)
            ->and($character->fresh()->group_id)->toBe($group->id);
    });

    it('asks for confirmation before moving a user out of another group', function () {
        $old       = Group::factory()->create(['name' => 'Old Guard']);
        $new       = Group::factory()->create(['name' => 'New Blood']);
        $user      = User::factory()->inGroup($old)->create();
        $character = Character::factory()->create(['user_id' => $user->id, 'group_id' => $old->id]);

        $this->artisan('player:assign', ['email' => $user->email, 'group' => 'New Blood'])
            ->expectsConfirmation('Move them (and their characters) to [New Blood]?', 'yes')
            ->assertExitCode(0);

        expect($user->fresh()->group_id)->toBe($new->id)
            ->and($character->fresh()->group_id)->toBe($new->id);
    });

    it('makes no changes when the move is declined', function () {
        $old = Group::factory()->create(['name' => 'Old Guard']);
        Group::factory()->create(['name' => 'New Blood']);
        $user = User::factory()->inGroup($old)->create();

        $this->artisan('player:assign', ['email' => $user->email, 'group' => 'New Blood'])
            ->expectsConfirmation('Move them (and their characters) to [New Blood]?', 'no')
            ->expectsOutputToContain('No changes made.')
            ->assertExitCode(0);

        expect($user->fresh()->group_id)->toBe($old->id);
    });

    it('fails for an unknown email', function () {
        Group::factory()->create(['name' => 'Kingsport Lodge']);

        $this->artisan('player:assign', ['email' => 'ghost@example.com', 'group' => 'Kingsport Lodge'])
            ->expectsOutputToContain('No user found with email [ghost@example.com].')
            ->assertExitCode(1);
    });
});

describe('player:password', function () {
    it('sets a new password via the option', function () {
        $user = User::factory()->create();

        $this->artisan('player:password', ['email' => $user->email, '--password' => 'eldritch-secrets'])
            ->expectsOutputToContain("Password updated for [{$user->email}].")
            ->assertExitCode(0);

        expect(Hash::check('eldritch-secrets', $user->fresh()->password))->toBeTrue();
    });

    it('prompts for the password when not provided', function () {
        $user = User::factory()->create();

        $this->artisan('player:password', ['email' => $user->email])
            ->expectsQuestion('New password', 'eldritch-secrets')
            ->assertExitCode(0);

        expect(Hash::check('eldritch-secrets', $user->fresh()->password))->toBeTrue();
    });

    it('rejects a password that fails the default rules', function () {
        $user    = User::factory()->create();
        $oldHash = $user->password;

        $this->artisan('player:password', ['email' => $user->email, '--password' => 'short'])
            ->assertExitCode(1);

        expect($user->fresh()->password)->toBe($oldHash);
    });
});

describe('player:block and player:unblock', function () {
    it('blocks a user and terminates their sessions', function () {
        $user  = User::factory()->create();
        $other = User::factory()->create();
        createSessionFor($user);
        createSessionFor($other);

        $this->artisan('player:block', ['email' => $user->email])
            ->expectsOutputToContain('1 active session(s) terminated')
            ->assertExitCode(0);

        expect($user->fresh()->isBlocked())->toBeTrue()
            ->and(DB::table('sessions')->where('user_id', $user->id)->exists())->toBeFalse()
            ->and(DB::table('sessions')->where('user_id', $other->id)->exists())->toBeTrue();
    });

    it('unblocks a blocked user', function () {
        $user = User::factory()->blocked()->create();

        $this->artisan('player:unblock', ['email' => $user->email])
            ->expectsOutputToContain('unblocked')
            ->assertExitCode(0);

        expect($user->fresh()->isBlocked())->toBeFalse();
    });

    it('treats unblocking an unblocked user as a no-op', function () {
        $user = User::factory()->create();

        $this->artisan('player:unblock', ['email' => $user->email])
            ->expectsOutputToContain('Nothing to do.')
            ->assertExitCode(0);
    });
});

describe('player:list', function () {
    it('renders players with group, blocked state, characters and pending invite', function () {
        $group = Group::factory()->create(['name' => 'Innsmouth Watch']);
        $user  = User::factory()->inGroup($group)->create(['name' => 'Ada Ward']);
        Character::factory()->create(['user_id' => $user->id, 'group_id' => $group->id]);
        Invitation::factory()->create(['email' => $user->email, 'group_id' => $group->id, 'invited_by' => null]);

        // The character factory spawns a throwaway owner while evaluating its
        // definition; remove every user except Ada so the table is deterministic.
        User::query()->whereKeyNot($user->id)->forceDelete();

        $this->artisan('player:list')
            ->expectsTable(
                ['Name', 'Email', 'Group', 'Blocked', 'Characters', 'Pending invite'],
                [['Ada Ward', $user->email, 'Innsmouth Watch', 'No', 1, 'Yes']],
            )
            ->assertExitCode(0);
    });

    it('filters by group', function () {
        $group = Group::factory()->create(['name' => 'Innsmouth Watch']);
        User::factory()->inGroup($group)->create(['name' => 'Ada Ward']);
        User::factory()->create(['name' => 'Loner Larry']);

        $this->artisan('player:list', ['group' => 'Innsmouth Watch'])
            ->expectsOutputToContain('Ada Ward')
            ->doesntExpectOutputToContain('Loner Larry')
            ->assertExitCode(0);
    });
});

describe('cthulhu:manage', function () {
    it('shows the menu and exits', function () {
        $this->artisan('cthulhu:manage')
            ->expectsQuestion('What would you like to do?', 'Exit')
            ->expectsOutputToContain('Farewell, Keeper.')
            ->assertExitCode(0);
    });

    it('dispatches a menu action before exiting', function () {
        $this->artisan('cthulhu:manage')
            ->expectsQuestion('What would you like to do?', 'List groups')
            ->expectsOutputToContain('No groups exist yet.')
            ->expectsQuestion('What would you like to do?', 'Exit')
            ->assertExitCode(0);
    });
});
