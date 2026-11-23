<?php

namespace Tests\Feature;

use App\Models\Document;
use App\Models\Filiere;
use App\Models\Tfe;
use App\Models\TfeModification;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TfeAccessTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Base en mémoire : ne touche jamais à la vraie base ni aux vrais documents.
        config([
            'database.default' => 'sqlite',
            'database.connections.sqlite.database' => ':memory:',
            'database.connections.sqlite.url' => null,
        ]);
        DB::purge('sqlite');
        Storage::fake('local');
        config(['filesystems.default' => 'local']);
        Mail::fake();
        \Illuminate\Support\Facades\Cache::flush();
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        Schema::create('users', function (Blueprint $t) {
            $t->id();
            foreach (['name', 'email', 'password', 'entity', 'study_year', 'matricule', 'remember_token'] as $c) {
                $t->string($c)->nullable();
            }
            $t->boolean('is_admin')->default(false);
            $t->timestamp('email_verified_at')->nullable();
            $t->timestamps();
        });
        Schema::create('documents', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('path');
            $t->timestamps();
        });
        Schema::create('tves', function (Blueprint $t) {
            $t->id();
            $t->integer('document_id');
            $t->unsignedBigInteger('user_id')->nullable();
            $t->integer('status')->default(1);
            foreach (['theme', 'auteurs', 'annee_de_realisation', 'tuteur_de_stage', 'lieu_de_stage', 'email_tuteur', 'email_maitre_memoire', 'maitre_memoire', 'groupe_pedagogique', 'resume'] as $c) {
                $t->text($c);
            }
            $t->timestamps();
        });
        Schema::create('filieres', function (Blueprint $t) {
            $t->id();
            $t->string('nom');
            $t->text('description')->nullable();
            $t->timestamps();
        });
        foreach (['GEI', 'GC', 'MS', 'GE', 'GMP', 'BTPCD', 'FC'] as $nom) {
            DB::table('filieres')->insert(['nom' => $nom, 'description' => $nom]);
        }
        require_once database_path('migrations/2026_11_23_100000_add_niveaux.php');
        (new \AddNiveaux())->up();
        // doublons anciens, présents avant la mise en place du contrôle
        foreach ([1, 2] as $i) {
            DB::table('users')->insert(['name' => "Ancien $i", 'email' => 'ancien@example.com', 'matricule' => '55555555', 'password' => 'x', 'entity' => 'GEI']);
        }
        require_once database_path('migrations/2026_11_23_200000_add_cles_uniques_users.php');
        (new \AddClesUniquesUsers())->up();
        require_once database_path('migrations/2026_11_23_000000_create_tfe_modifications_table.php');
        (new \CreateTfeModificationsTable())->up();
    }

    private function form(array $extra = [])
    {
        return array_merge([
            'theme' => 'Étude HTA/BTA', 'auteurs' => 'A', 'annee_de_realisation' => '2026',
            'tuteur_de_stage' => 'T', 'lieu_de_stage' => 'L', 'email_tuteur' => 't@example.com',
            'email_maitre_memoire' => 'm@example.com', 'maitre_memoire' => 'M',
            'filiere_id' => 1, 'resume' => 'Un résumé',
        ], $extra);
    }

    private function user($admin = false)
    {
        $user = new User(['name' => 'U', 'email' => uniqid() . '@example.com', 'password' => bcrypt('secret123'), 'matricule' => uniqid(), 'entity' => 'GEI', 'niveau_id' => 1]);
        $user->is_admin = $admin;
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }

    private function tfe(User $owner, $status = 0, $path = null)
    {
        $path = $path ?: 'public/tfe_documents/' . uniqid() . '.pdf';
        Storage::put($path, '%PDF-1.4');
        $tfe = new Tfe($this->form() + ['groupe_pedagogique' => 'GEI']);
        $tfe->niveau_id = 1;
        $tfe->filiere_id = 1;
        $tfe->user_id = $owner->id;
        $tfe->document_id = Document::create(['name' => 'd', 'path' => $path])->id;
        $tfe->status = $status;
        $tfe->save();

        return $tfe;
    }

    /** Un autre utilisateur, c'est un autre navigateur : session neuve. */
    public function actingAs($user, $driver = null)
    {
        $this->flushSession();

        return parent::actingAs($user, $driver);
    }

    private function pdf()
    {
        return UploadedFile::fake()->createWithContent('a.pdf', "%PDF-1.4\n%%EOF");
    }

    public function testAnotherStudentCannotTouchATfe()
    {
        Document::create(['name' => 'decalage', 'path' => 'x']);
        $tfe = $this->tfe($this->user());
        $this->actingAs($this->user());

        $this->get(route('tfe.show', $tfe))->assertForbidden();
        $this->get(route('profil', $tfe->id))->assertForbidden();
        $this->get(route('editTfe', $tfe->id))->assertForbidden();
        $this->get(route('tfe.edit', $tfe->id))->assertForbidden();
        $this->post(route('updateTfe'), $this->form(['tfe_id' => $tfe->id, 'theme' => 'pirate']))->assertForbidden();
        $this->put(route('tfe.update', $tfe->id), $this->form(['tfe_id' => $tfe->id]))->assertForbidden();
        $this->delete(route('tfeDelete', $tfe->id))->assertForbidden();
        $this->delete(route('tfe.destroy', $tfe->id))->assertForbidden();
        $this->assertSame('Étude HTA/BTA', $tfe->fresh()->theme);
    }

    public function testStoreIgnoresProtectedFieldsAndNeverOverwrites()
    {
        foreach ([$this->user(), $this->user()] as $user) {
            $this->actingAs($user)->post(route('tfe.store'), $this->form([
                'document' => $this->pdf(), 'status' => 2, 'user_id' => 999, 'document_id' => 999,
            ]))->assertSessionHasNoErrors()->assertRedirect();
            $tfe = Tfe::where('user_id', $user->id)->firstOrFail();
            $this->assertEquals(1, $tfe->status);
            $this->assertSame('Un résumé', $tfe->resume);
        }
        $paths = Document::pluck('path');
        $this->assertCount(2, $paths->unique());
        $this->assertCount(2, Storage::files('tfe_documents'));
    }

    private function justified(array $extra = [])
    {
        return $this->form(array_merge(['motif' => 'Correction du thème', 'justificatif' => $this->pdf()], $extra));
    }

    public function testOwnerMustJustifyAndCanNeverDelete()
    {
        $owner = $this->user();
        foreach ([0, 1] as $status) {
            $tfe = $this->tfe($owner, $status);
            $this->actingAs($owner);
            $this->get(route('editTfe', $tfe->id))->assertOk()->assertSee('justificatif')->assertDontSee('Supprimer');
            $this->post(route('updateTfe'), $this->form(['tfe_id' => $tfe->id, 'theme' => 'X']))
                ->assertSessionHasErrors(['motif', 'justificatif']);
            $this->post(route('updateTfe'), $this->form(['tfe_id' => $tfe->id, 'motif' => 'Correction du thème']))
                ->assertSessionHasErrors(['justificatif']);
            $this->assertSame('Étude HTA/BTA', $tfe->fresh()->theme);
            $this->delete(route('tfeDelete', $tfe->id))->assertForbidden();
            $this->delete(route('tfe.destroy', $tfe->id))->assertForbidden();
        }
        $rejected = $this->tfe($owner, 2);
        $this->get(route('editTfe', $rejected->id))->assertForbidden();
        $this->post(route('updateTfe'), $this->justified(['tfe_id' => $rejected->id]))->assertForbidden();
    }

    public function testJustifiedEditGoesBackToPendingAndKeepsPreviousVersion()
    {
        $owner = $this->user();
        $tfe = $this->tfe($owner, 1);
        $old = Document::find($tfe->document_id)->path;
        $this->actingAs($this->user())->get(route('tfe.show', $tfe))->assertOk();

        $this->actingAs($owner)->post(route('updateTfe'), $this->justified([
            'tfe_id' => $tfe->id, 'theme' => 'Neuf', 'status' => 1, 'document' => $this->pdf(),
        ]))->assertSessionHasNoErrors()->assertRedirect();
        $this->assertSame('Neuf', $tfe->fresh()->theme);
        $this->assertEquals(0, $tfe->fresh()->status);
        $m = TfeModification::firstOrFail();
        $this->assertSame('Étude HTA/BTA', $m->anciennes_donnees['theme']);
        Storage::assertExists($old);
        Storage::assertExists($m->justificatif_path);

        $this->get(route('justificatif', $m->id))->assertRedirect(route('welcome'));
        $this->actingAs($this->user())->get(route('tfe.show', $tfe))->assertForbidden();

        $this->actingAs($this->user(true));
        $this->get(route('dashboard'))->assertOk()->assertSee('Correction du thème');
        $this->get(route('justificatif', $m->id))->assertOk();
        $this->post(route('updateTfe'), $this->form(['tfe_id' => $tfe->id]))->assertSessionHasNoErrors();
        $this->post(route('status', ['id' => $tfe->id, 'status' => -3]))->assertRedirect();
        $this->assertSame(0, TfeModification::count());
        Storage::assertMissing($old);
        Storage::assertMissing($m->justificatif_path);
    }

    public function testAdminCanValidateAndDeleteWithoutLosingASharedFile()
    {
        $shared = 'public/tfe_documents/partage.pdf';
        $a = $this->tfe($this->user(), 0, $shared);
        $b = $this->tfe($this->user(), 0, $shared);
        $this->actingAs($this->user(true));
        $this->post(route('status', ['id' => $a->id, 'status' => 1]))->assertRedirect();
        $this->assertEquals(1, $a->fresh()->status);
        $this->post(route('status', ['id' => $a->id, 'status' => -3]))->assertRedirect();
        $this->assertNull(Tfe::find($a->id));
        Storage::assertExists($shared);
        $this->delete(route('tfeDelete', $b->id))->assertRedirect();
        Storage::assertMissing($shared);
    }

    public function testNonAdminIsRedirectedHome()
    {
        $this->actingAs($this->user())->get(route('dashboard'))->assertRedirect(route('welcome'));
    }

    public function testOneDocumentPerLevel()
    {
        $user = $this->user();
        $this->actingAs($user);
        $depot = function ($filiere) {
            return $this->post(route('tfe.store'), $this->form(['filiere_id' => $filiere, 'document' => $this->pdf()]));
        };
        $this->get(route('tfe.create', ['niveau' => 1, 'filiere' => 1]))->assertOk()->assertSee('Déposer');
        $depot(1)->assertRedirect(route('profil'));
        $depot(2)->assertRedirect(route('tfe.create'))->assertSessionHas('error'); // même niveau, autre filière
        $this->get(route('tfe.create', ['niveau' => 1, 'filiere' => 2]))->assertRedirect(route('tfe.create'))->assertSessionHas('error');
        $this->get(route('tfe.create', ['niveau' => 1, 'filiere' => 7]))->assertSessionHas('error'); // niveau et filière incohérents
        $depot(7)->assertRedirect(route('profil.edit'))->assertSessionHas('error'); // grade licence
        $this->get(route('tfe.create', ['niveau' => 2, 'filiere' => 7]))->assertRedirect(route('profil.edit'));
        $this->get(route('tfe.create'))->assertOk()->assertSee('Mettre à jour mon profil');
        $this->get(route('profil.edit'))->assertOk();
        $this->post(route('profil.update'), ['entity' => 'FC', 'study_year' => 3])->assertSessionHasErrors('study_year');
        $this->post(route('profil.update'), ['entity' => 'FC', 'study_year' => 4, 'is_admin' => 1, 'matricule' => '00000000'])->assertRedirect(route('profil'));
        $avant = $user->matricule;
        $user->refresh();
        $this->assertSame([$avant, 2, false], [$user->matricule, (int) $user->niveau_id, (bool) $user->is_admin]);
        $this->actingAs($user);
        $depot(6)->assertRedirect(route('profil.edit')); // filière différente du profil
        $depot(7)->assertRedirect(route('profil'))->assertSessionHas('recu');
        $master = $user->tfes()->where('niveau_id', 2)->first();
        $this->get(route('tfe.attestation', $master->id))->assertOk()->assertHeader('Content-Type', 'application/pdf');
        $html = view('tfe.attestation', ['tfe' => $master->load('user', 'niveau', 'filiere'), 'reference' => 'R', 'verifyUrl' => 'u', 'qrDataUri' => '', 'logoDataUri' => null])->render();
        $this->assertStringContainsString($user->matricule, $html);
        $this->assertStringContainsString('Master', $html);
        $this->assertSame([1, 2], $user->tfes()->orderBy('niveau_id')->pluck('niveau_id')->map('intval')->all());
        $this->assertCount(2, Storage::files('tfe_documents'));
        $this->get(route('profil'))->assertOk()->assertSee('Licence')->assertSee('Master');
        $this->get(route('tfe.create'))->assertOk()->assertSee('Vous avez déjà');
        // une modification ne change pas de niveau
        $this->post(route('updateTfe'), $this->justified(['tfe_id' => $user->tfes()->first()->id, 'filiere_id' => 7]))->assertSessionHasErrors('filiere_id');
    }

    public function testAdminManagesFilieres()
    {
        $this->actingAs($this->user())->post(route('filieres.store'), ['nom' => 'X', 'description' => 'X', 'niveau_id' => 3])->assertRedirect(route('welcome'));
        $this->actingAs($this->user(true));
        $this->post(route('filieres.store'), ['nom' => 'robo', 'description' => 'Robotique', 'niveau_id' => 3])->assertSessionHasNoErrors();
        $f = Filiere::where('nom', 'ROBO')->firstOrFail();
        $this->assertEquals(3, $f->niveau_id);
        $this->post(route('filieres.store'), ['nom' => 'ROBO', 'description' => 'x', 'niveau_id' => 1])->assertSessionHasErrors('nom');
        $this->put(route('filieres.update', $f->id), ['nom' => 'ROBO', 'description' => 'Robots', 'niveau_id' => 2])->assertSessionHasNoErrors();
        $this->post(route('filieres.basculer', $f->id));
        $this->assertFalse($f->fresh()->active);
        $this->put(route('filieres.update', 1), ['nom' => 'GEI', 'description' => 'x', 'niveau_id' => 2])->assertSessionHas('error');
        $this->delete(route('filieres.destroy', 1))->assertSessionHas('error');
        $this->delete(route('filieres.destroy', $f->id))->assertSessionHas('success');
        foreach (['filieres', 'journal', 'store', 'dashboard', 'export', 'changepasswordView', 'profil'] as $page) {
            $this->get(route($page))->assertOk();
        }
        $this->get(route('store', ['type' => 'admins']))->assertOk();
    }

    public function testPublicPagesFiltersAndHeaders()
    {
        $licence = $this->tfe($this->user(), 1);
        $master = $this->tfe($this->user(), 1);
        $master->theme = 'Froid industriel <script>x</script>';
        $master->niveau_id = 2;
        $master->filiere_id = 7;
        $master->save();
        $cache = $this->tfe($this->user(), 0);

        $this->get('/?niveau=2')->assertOk()->assertSee('Froid industriel')->assertDontSee('Étude HTA/BTA')->assertDontSee('<script>x', false)
            ->assertHeader('X-Frame-Options', 'SAMEORIGIN')->assertHeader('Content-Security-Policy');
        $this->get('/?tri=filiere')->assertOk()->assertSee('Trier par');
        $this->get('/?filiere=1')->assertOk()->assertDontSee('Froid industriel');
        $this->get('/search?groupe_pedagogique=@_FC')->assertRedirect(route('welcome', ['filiere' => 7]));
        $this->get($master->url_publique)->assertOk()->assertDontSee('m@example.com')->assertDontSee('<script>x', false);
        $this->get(route('rapport', $master->id))->assertRedirect($master->url_publique);
        $this->get(route('rapport', $cache->id))->assertNotFound();
        $this->get(route('sitemap'))->assertOk()->assertSee($licence->url_publique)->assertDontSee('/rapports/' . $cache->id . '-');
        $this->get(route('tfe.show', $licence))->assertRedirect(route('login'));
        foreach (['style', 'script', 'logo'] as $r) {
            $this->get(\App\Http\Controllers\RessourceController::url($r))->assertOk();
        }
        $this->get('/ressources/x/env')->assertNotFound();
        foreach (['login', 'register', 'password.request'] as $page) {
            $this->get(route($page))->assertOk();
        }
    }

    public function testRejectionReasonAndRestore()
    {
        $owner = $this->user();
        $tfe = $this->tfe($owner, 1);
        $old = Document::find($tfe->document_id)->path;
        $this->actingAs($owner)->post(route('updateTfe'), $this->justified(['tfe_id' => $tfe->id, 'theme' => 'Mauvais', 'document' => $this->pdf()]));
        $this->actingAs($this->user(true))->post(route('status', ['id' => $tfe->id, 'status' => 2]), ['motif_rejet' => 'Page manquante']);
        $this->actingAs($owner)->get(route('profil'))->assertSee('Page manquante');
        $this->post(route('restaurer', TfeModification::first()->id))->assertRedirect(route('welcome'));
        $this->actingAs($this->user(true))->post(route('restaurer', TfeModification::first()->id))->assertSessionHas('success');
        $this->assertSame('Étude HTA/BTA', $tfe->fresh()->theme);
        $this->assertEquals(1, $tfe->fresh()->status);
        $this->assertSame($old, Document::find($tfe->document_id)->path);
    }

    public function testRegistrationLoginAndPassword()
    {
        $form = ['name' => 'Ada', 'email' => 'ada@example.com', 'password' => 'secret123', 'password_confirmation' => 'secret123', 'entity' => 'FC', 'study_year' => 4, 'matricule' => '12345678', 'is_admin' => 1];
        foreach ([['FC', 3], ['GEI', 4], ['FC', 6], ['GEI', 0]] as $essai) {
            $this->post(route('register'), ['entity' => $essai[0], 'study_year' => $essai[1]] + $form)->assertSessionHasErrors('study_year');
        }
        $this->post(route('register'), $form)->assertSessionHasNoErrors();
        $ada = User::where('email', 'ada@example.com')->firstOrFail();
        $this->assertFalse((bool) $ada->is_admin);
        $this->assertEquals(2, $ada->niveau_id);
        $this->post(route('passwordUpdate'), ['password' => 'faux', 'new_password' => 'nouveau123', 'comfirm_new_password' => 'nouveau123'])->assertSessionHas('error');
        $this->post(route('passwordUpdate'), ['password' => 'secret123', 'new_password' => 'nouveau123', 'comfirm_new_password' => 'nouveau123'])->assertSessionHas('success'); // email non confirmé : sans code
        $this->post(route('logout'));
        $this->post(route('login'), ['email' => 'ada@example.com', 'password' => 'nouveau123'])->assertRedirect(route('profil'));
        $this->post(route('logout'));
        for ($i = 0; $i < 6; $i++) {
            $reponse = $this->post(route('login'), ['email' => 'ada@example.com', 'password' => 'x']);
        }
        $this->assertGuest();
        $this->post(route('login'), ['email' => 'ada@example.com', 'password' => 'nouveau123']);
        $this->assertGuest(); // bloqué après 5 essais
    }

    public function testHardening()
    {
        // http vers https sur l'adresse officielle uniquement
        config(['app.url' => 'https://www.exemple.test']);
        $this->get('http://exemple.test/login')->assertStatus(301)->assertRedirect('https://exemple.test/login');
        $this->get('http://localhost/login')->assertOk();
        $cookies = $this->get('https://exemple.test/login')->assertOk()->headers->getCookies();
        $this->assertNotEmpty($cookies);
        foreach ($cookies as $cookie) {
            $this->assertTrue($cookie->isSecure(), $cookie->getName());
        }
        // lien de réinitialisation insensible à l'en-tête Host
        $this->user()->sendPasswordResetNotification('jeton');
        $lien = $this->dernierBouton();
        $this->assertStringStartsWith('https://www.exemple.test/password/reset/jeton', $lien);
        // pas d'API, pas d'email à retour chariot, réponse identique pour un compte inconnu
        $this->get('/api/user?api_token[]=1')->assertNotFound();
        $this->post(route('register'), ['email' => "a@exemple.test\r\nBcc: b@exemple.test"])->assertSessionHasErrors('email');
        $this->post('/password/email', ['email' => 'inconnu@example.com'])->assertSessionHas('status')->assertSessionHasNoErrors();
    }

    public function testNoNewDuplicateEmailOrMatricule()
    {
        $this->assertSame(2, User::where('email', 'ancien@example.com')->count()); // conservés
        $this->assertSame(1, User::enDouble()->count());
        $form = ['name' => 'N', 'email' => 'neuf@example.com', 'password' => 'secret123', 'password_confirmation' => 'secret123', 'entity' => 'GEI', 'study_year' => 1, 'matricule' => 'ab123456'];
        foreach ([['email' => 'ANCIEN@example.com'], ['email' => ' ancien@Example.com '], ['matricule' => '55555555']] as $essai) {
            $this->post(route('register'), $essai + $form)->assertSessionHasErrors(array_keys($essai));
        }
        $this->post(route('register'), $form)->assertSessionHasNoErrors();
        $this->assertSame('AB123456', User::where('email', 'neuf@example.com')->value('matricule'));
        $this->post(route('logout'));
        $this->post(route('register'), ['email' => 'x@example.com'] + $form)->assertSessionHasErrors('matricule');

        $this->actingAs($this->user(true));
        $this->post(route('addStudent'), $form)->assertSessionHasErrors(['email', 'matricule']);
        $this->post(route('addAdmin'), ['username' => 'NEUF@example.com', 'password' => 'secret12345', 'passwordV' => 'secret123'])->assertSessionHasErrors('username');
        $double = User::enDouble()->first();
        $this->get(route('store', ['doublons' => 1]))->assertOk()->assertSee('Ancien 2')->assertDontSee('Ancien 1');
        $edit = ['name' => 'Ancien 2', 'entity' => 'GEI', 'study_year' => 1];
        $this->post(route('update_student', $double->id), $edit + ['email' => 'neuf@example.com', 'matricule' => '66666666'])->assertSessionHasErrors('email');
        $this->post(route('update_student', $double->id), $edit + ['email' => 'autre@example.com', 'matricule' => '66666666'])->assertSessionHasNoErrors();
        $this->assertSame(0, User::enDouble()->count());

        // filet de sécurité : la base refuse même sans validation
        $this->expectException(\Illuminate\Database\QueryException::class);
        User::create(['name' => 'Z', 'email' => 'Neuf@Example.com', 'password' => 'x']);
    }

    private function dernierCode()
    {
        $code = null;
        Mail::assertSent(\App\Mail\MessageInsti::class, function ($m) use (&$code) {
            $code = $m->code ?: $code;

            return true;
        });

        return $code;
    }

    private function dernierBouton()
    {
        $lien = null;
        Mail::assertSent(\App\Mail\MessageInsti::class, function ($m) use (&$lien) {
            $lien = $m->bouton ? $m->bouton[1] : $lien;

            return true;
        });

        return $lien;
    }

    public function testEmailConfirmationAndReceipt()
    {
        $user = $this->user();
        $user->forceFill(['email_verified_at' => null])->save();
        $this->actingAs($user);
        $this->get(route('tfe.create'))->assertRedirect(route('email.confirmer'));
        $this->post(route('tfe.store'), $this->form(['document' => $this->pdf()]))->assertRedirect(route('email.confirmer'));
        $this->get(route('email.confirmer'))->assertOk();
        $this->post(route('email.envoyer'))->assertSessionHas('success');
        $code = $this->dernierCode();
        $this->assertMatchesRegularExpression('/^\d{6}$/', $code);
        $this->post(route('email.verifier'), ['code' => $code === '123456' ? '654321' : '123456'])->assertSessionHasErrors('code');
        $this->post(route('email.verifier'), ['code' => $code])->assertRedirect(route('profil'));
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->post(route('email.verifier'), ['code' => $code])->assertSessionHasErrors('code'); // usage unique

        $this->actingAs($user->fresh());
        $this->post(route('tfe.store'), $this->form(['document' => $this->pdf()]))->assertRedirect(route('profil'));
        Mail::assertSent(\App\Mail\RecuDepot::class, function ($m) use ($user) {
            $m->build();

            return $m->hasTo($user->email) && strpos(implode($m->lignes), $user->matricule) !== false && count($m->rawAttachments) === 1;
        });
        $tfe = $user->tfes()->first();
        $this->post(route('tfe.recu', $tfe->id))->assertSessionHas('success');
        $this->actingAs($this->user())->post(route('tfe.recu', $tfe->id))->assertForbidden();
    }

    public function testCodeLimitsAndAddressChange()
    {
        $user = $this->user();
        $autre = $this->user();
        $this->actingAs($user);
        $this->post(route('email.changer'), ['email' => 'nouveau@example.com', 'mot_de_passe' => 'faux'])->assertSessionHasErrors('mot_de_passe');
        $this->post(route('email.changer'), ['email' => $autre->email, 'mot_de_passe' => 'secret123'])->assertSessionHasErrors('email');
        $this->post(route('email.changer'), ['email' => 'Nouveau@example.com', 'mot_de_passe' => 'secret123'])->assertSessionHas('success');
        $this->assertNotSame('nouveau@example.com', $user->fresh()->email); // pas avant le code
        $code = $this->dernierCode();
        for ($i = 0; $i < 5; $i++) {
            $this->post(route('email.verifier'), ['code' => $code === '111111' ? '222222' : '111111']);
        }
        $this->post(route('email.verifier'), ['code' => $code])->assertSessionHasErrors('code'); // trop d'essais
        $this->post(route('email.changer'), ['email' => 'nouveau@example.com', 'mot_de_passe' => 'secret123'])->assertSessionHas('success');
        $this->post(route('email.verifier'), ['code' => $this->dernierCode()])->assertSessionHas('success');
        $this->assertSame('nouveau@example.com', $user->fresh()->email);
        for ($i = 0; $i < 3; $i++) {
            $this->post(route('email.changer'), ['email' => 'z@example.com', 'mot_de_passe' => 'secret123']);
        }
        $this->post(route('email.changer'), ['email' => 'z@example.com', 'mot_de_passe' => 'secret123'])->assertSessionHas('error');
    }

    public function testPasswordChangeNeedsCodeAndResetLinkWorks()
    {
        $user = $this->user();
        $this->actingAs($user);
        $this->post(route('passwordUpdate'), ['password' => 'secret123', 'new_password' => 'nouveau123', 'comfirm_new_password' => 'nouveau123'])->assertRedirect(route('password.code'));
        $this->assertTrue(\Hash::check('secret123', $user->fresh()->password));
        $this->get(route('password.code'))->assertOk();
        $this->post(route('password.code.verifier'), ['code' => $this->dernierCode()])->assertSessionHas('success');
        $this->assertTrue(\Hash::check('nouveau123', $user->fresh()->password));

        $mail = new \App\Mail\MessageInsti('s', 'INSTI', ['a', 'b'], null, ['Choisir', 'https://x/password/reset/jeton']);
        $html = view('emails.message', $mail->buildViewData())->render();
        $this->assertStringContainsString('/password/reset/jeton', $html);
        $this->assertStringContainsString('INSTI', $html);
        event(new \Illuminate\Auth\Events\PasswordReset($user->forceFill(['email_verified_at' => null])));
        $this->assertNotNull($user->fresh()->email_verified_at);

        // une adresse changée par un admin redevient à confirmer
        $u = $user->fresh();
        $u->update(['email' => 'admin-change@example.com']);
        $this->assertNull($u->fresh()->email_verified_at);
    }

    public function testForgottenPasswordEndToEnd()
    {
        Schema::create('password_resets', function (Blueprint $t) {
            $t->string('email');
            $t->string('token');
            $t->timestamp('created_at')->nullable();
        });
        $user = $this->user();
        $user->forceFill(['email_verified_at' => null])->save();
        $jeton = null;
        $this->post(route('password.email'), ['email' => $user->email])->assertSessionHas('status');
        preg_match('#/password/reset/([^?]+)#', $this->dernierBouton(), $m);
        $jeton = $m[1];
        $this->get(route('password.reset', ['token' => $jeton, 'email' => $user->email]))->assertOk()->assertSee($user->email);
        $form = ['token' => $jeton, 'email' => $user->email, 'password' => 'oublie1234', 'password_confirmation' => 'oublie1234'];
        $this->post(route('password.update'), ['token' => 'faux'] + $form)->assertSessionHasErrors('email');
        $this->post(route('password.update'), ['password' => 'abcdefgh', 'password_confirmation' => 'abcdefgh'] + $form)->assertSessionHasErrors('password');
        $this->post(route('password.update'), $form)->assertSessionHasNoErrors()->assertRedirect();
        $this->assertTrue(\Hash::check('oublie1234', $user->fresh()->password));
        $this->assertNotNull($user->fresh()->email_verified_at);
        $this->post(route('logout'));
        $this->post(route('password.update'), $form)->assertSessionHasErrors('email'); // lien à usage unique
    }

    public function testEmailDiagnosticAndFallback()
    {
        $this->actingAs($this->user(true));
        $this->post(route('testEmail'), ['adresse' => 'moi@example.com'])->assertSessionHas('success');
        Mail::assertSent(\App\Mail\MessageInsti::class, function ($m) {
            return $m->hasTo('moi@example.com');
        });
        $this->get(route('journal'))->assertOk()->assertSee('mo***@example.com');

        config(['mail.default' => 'failover']);
        $this->assertSame(['smtp', 'smtps', 'serveur'], \App\Services\Courriel::messageries());
        config(['mail.mailers.smtp.password' => 'motsecret', 'mail.mailers.smtp.username' => 'u@example.com']);
        $net = \App\Services\Courriel::nettoyer('Refus pour u@example.com motsecret ' . base64_encode('motsecret'));
        $this->assertStringNotContainsString('motsecret', $net);
        $this->assertStringNotContainsString(base64_encode('motsecret'), $net);
        $this->assertStringNotContainsString('u@example.com', $net);
        $this->actingAs($this->user())->post(route('testEmail'), ['adresse' => 'moi@example.com'])->assertRedirect(route('welcome'));
    }

    private function vraieMessagerie()
    {
        Mail::swap(new \Illuminate\Mail\MailManager($this->app));
        (new \App\Providers\AppServiceProvider($this->app))->boot();
    }

    public function testGmailRelais()
    {
        $cle = str_repeat('a', 64);
        config(['mail.default' => 'failover', 'mail.mailers.failover.mailers' => [], 'mail.mailers.relais.url' => 'https://script.google.com/macros/s/abc_DEF-1/exec', 'mail.mailers.relais.cle' => $cle, 'mail.from.address' => 'moi@example.com', 'mail.from.name' => 'INSTI', 'app.url' => 'https://exemple.test']);
        $this->vraieMessagerie();
        \Illuminate\Support\Facades\Http::fake(['script.google.com/*' => \Illuminate\Support\Facades\Http::sequence()->push(['ok' => true])->push(['ok' => false, 'erreur' => 'clé refusée'])->push('<html>Connexion</html>')]);

        $user = $this->user();
        $tfe = $this->tfe($user, 1)->load('user', 'niveau');
        $this->assertTrue(\App\Services\Courriel::envoyer($user->email, new \App\Mail\RecuDepot($tfe, '%PDF-test', 'recu.pdf')));
        \Illuminate\Support\Facades\Http::assertSent(function ($r) use ($user, $cle) {
            return $r['cle'] === $cle && $r['to'] === $user->email && $r['nom'] === 'INSTI'
                && count($r['pieces']) === 1 && base64_decode($r['pieces'][0]['contenu']) === '%PDF-test'
                && strpos($r['html'], 'cid:') === false && strpos($r['html'], 'https://exemple.test/ressources/') !== false
                && strpos($r['text'], $user->matricule) !== false;
        });
        foreach (['Relais Gmail 200 : clé refusée', 'Relais Gmail 200 : réponse inattendue (déploiement ou accès du script)'] as $attendu) {
            $resultats = [];
            $this->assertFalse(\App\Services\Courriel::envoyer($user->email, new \App\Mail\MessageInsti('s', 't', ['a']), $resultats));
            $this->assertSame(['relais' => $attendu], $resultats);
        }
    }

    public function testHebergeurDetecteLesEchecs()
    {
        config(['mail.default' => 'failover', 'mail.mailers.failover.mailers' => ['serveur'], 'mail.mailers.relais.url' => null, 'mail.from.address' => 'moi@example.com', 'mail.from.name' => 'INSTI']);
        $php = '"' . PHP_BINARY . '"';
        $sortie = str_replace('\\', '/', tempnam(sys_get_temp_dir(), 'mail'));
        foreach ([["file_put_contents('$sortie', stream_get_contents(STDIN));", true], ["fwrite(STDERR, 'refus'); exit(67);", false]] as [$code, $attendu]) {
            $script = tempnam(sys_get_temp_dir(), 'sm');
            file_put_contents($script, '<?php ' . $code);
            config(['mail.mailers.serveur.path' => "$php $script -t -i"]);
            $this->vraieMessagerie();
            $resultats = [];
            $this->assertSame($attendu, \App\Services\Courriel::envoyer('dest@example.com', new \App\Mail\MessageInsti('Sujet', 't', ['a']), $resultats), json_encode($resultats));
            unlink($script);
        }
        $this->assertStringContainsString('code 67', $resultats['serveur']);
        $recu = file_get_contents($sortie);
        unlink($sortie);
        $this->assertStringContainsString('To: dest@example.com', $recu);
        $this->assertStringContainsString('moi@example.com', $recu);
    }
}
