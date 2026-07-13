<?php

namespace Tests\Feature;

use App\Mail\DemandeTransportSoumise;
use App\Models\DemandeTransport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class DemandeTransportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_submit_a_demande_with_a_single_trajet_and_a_justificatif(): void
    {
        Mail::fake();
        Storage::fake('public');
        config(['cofima.email_dg' => ['dg@example.com']]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/demandes', [
            'trajets' => [
                [
                    'lieu_depart' => 'Plateau',
                    'lieu_arrivee' => 'Cocody',
                    'date_deplacement' => now()->addDay()->format('Y-m-d'),
                    'moyen_transport' => 'Taxi',
                    'cout_estime' => 8500,
                ],
            ],
            'motif' => 'Mission client',
            'justificatif' => UploadedFile::fake()->image('recu.jpg'),
        ]);

        $response->assertRedirect(route('dashboard'));

        $demande = DemandeTransport::first();
        $this->assertNotNull($demande);
        $this->assertSame($user->id, $demande->user_id);
        $this->assertSame('en_attente', $demande->statut);
        $this->assertSame('8500.00', (string) $demande->cout_estime);
        $this->assertCount(1, $demande->trajets);
        $this->assertCount(1, $demande->justificatifs);
        $this->assertCount(1, $demande->historiques);

        Storage::disk('public')->assertExists($demande->justificatifs->first()->chemin);

        Mail::assertSent(DemandeTransportSoumise::class, function ($mail) use ($demande) {
            return $mail->demande->id === $demande->id
                && $mail->hasTo('dg@example.com')
                && count($mail->attachments()) === 1;
        });
    }

    public function test_user_can_submit_a_demande_with_multiple_trajets(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/demandes', [
            'trajets' => [
                [
                    'lieu_depart' => 'Cotonou',
                    'lieu_arrivee' => 'Porto-Novo',
                    'date_deplacement' => now()->addDay()->format('Y-m-d'),
                    'moyen_transport' => 'Taxi',
                    'cout_estime' => 5000,
                ],
                [
                    'lieu_depart' => 'Porto-Novo',
                    'lieu_arrivee' => 'Ouidah',
                    'date_deplacement' => now()->addDays(2)->format('Y-m-d'),
                    'moyen_transport' => 'Moto',
                    'cout_estime' => 3000,
                ],
            ],
            'motif' => 'Mission multi-étapes',
        ]);

        $response->assertRedirect(route('dashboard'));

        $demande = DemandeTransport::first();
        $this->assertCount(2, $demande->trajets);
        $this->assertSame('8000.00', (string) $demande->cout_estime);
    }

    public function test_demande_requires_at_least_one_trajet(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/demandes', ['motif' => 'Mission']);

        $response->assertSessionHasErrors(['trajets']);
    }

    public function test_trajet_requires_mandatory_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/demandes', [
            'trajets' => [[]],
            'motif' => 'Mission',
        ]);

        $response->assertSessionHasErrors([
            'trajets.0.lieu_depart',
            'trajets.0.lieu_arrivee',
            'trajets.0.date_deplacement',
            'trajets.0.moyen_transport',
            'trajets.0.cout_estime',
        ]);
    }

    public function test_dashboard_shows_historique_of_validated_and_rejected_demandes(): void
    {
        $user = User::factory()->create();
        $dg = User::factory()->create(['nom' => 'AVANDE', 'prenom' => 'Jean-Michel']);

        $validee = $this->createDemandeWithTrajet($user, 'Plateau', 'Cocody', DemandeTransport::STATUT_VALIDEE, [
            'valide_par' => $dg->id,
            'date_validation' => now(),
        ]);
        $validee->historiques()->create([
            'user_id' => $dg->id,
            'statut' => DemandeTransport::STATUT_VALIDEE,
            'commentaire' => 'Demande validée.',
        ]);

        $rejetee = $this->createDemandeWithTrajet($user, 'Cotonou', 'Porto-Novo', DemandeTransport::STATUT_REJETEE, [
            'valide_par' => $dg->id,
            'date_validation' => now(),
            'motif_rejet' => 'Justificatif manquant.',
        ]);
        $rejetee->historiques()->create([
            'user_id' => $dg->id,
            'statut' => DemandeTransport::STATUT_REJETEE,
            'commentaire' => 'Justificatif manquant.',
        ]);

        // Pending demande: shows in "Mes demandes" but must not appear in the history section.
        $this->createDemandeWithTrajet($user, 'Abomey', 'Bohicon', DemandeTransport::STATUT_EN_ATTENTE);

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertOk();
        $response->assertSee('Historique des validations / rejets');
        $response->assertSee('Jean-Michel AVANDE');
        $response->assertSee('Justificatif manquant.');
        $response->assertSee('Plateau');
        $response->assertSee('Cotonou');
        $response->assertViewHas('historiques', fn ($historiques) => $historiques->count() === 2);
    }

    public function test_owner_can_download_the_pdf_letter(): void
    {
        $user = User::factory()->create();
        $demande = $this->createDemandeWithTrajet($user, 'Plateau', 'Cocody');

        $response = $this->actingAs($user)->get(route('demandes.pdf', $demande));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_other_users_cannot_download_the_pdf_letter(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $demande = $this->createDemandeWithTrajet($user, 'Plateau', 'Cocody');

        $response = $this->actingAs($other)->get(route('demandes.pdf', $demande));

        $response->assertForbidden();
    }

    public function test_user_can_export_own_demandes_as_pdf_for_a_period(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $demande = $user->demandeTransports()->create(['motif' => 'Mission', 'cout_estime' => 5000, 'statut' => 'en_attente']);
        $demande->trajets()->create([
            'lieu_depart' => 'Plateau', 'lieu_arrivee' => 'Cocody',
            'date_deplacement' => '2026-03-15', 'moyen_transport' => 'Taxi', 'cout_estime' => 5000,
        ]);

        // Belongs to another user: must not appear in this export.
        $autreDemande = $other->demandeTransports()->create(['motif' => 'Autre', 'cout_estime' => 2000, 'statut' => 'en_attente']);
        $autreDemande->trajets()->create([
            'lieu_depart' => 'Abomey', 'lieu_arrivee' => 'Bohicon',
            'date_deplacement' => '2026-03-16', 'moyen_transport' => 'Taxi', 'cout_estime' => 2000,
        ]);

        $response = $this->actingAs($user)->get('/demandes/export/pdf?from=2026-03-01&to=2026-03-31');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_user_can_export_own_demandes_as_excel_for_a_period(): void
    {
        $user = User::factory()->create();

        $demande = $user->demandeTransports()->create(['motif' => 'Mission', 'cout_estime' => 5000, 'statut' => 'en_attente']);
        $demande->trajets()->create([
            'lieu_depart' => 'Plateau', 'lieu_arrivee' => 'Cocody',
            'date_deplacement' => '2026-03-15', 'moyen_transport' => 'Taxi', 'cout_estime' => 5000,
        ]);

        $response = $this->actingAs($user)->get('/demandes/export/excel?from=2026-03-01&to=2026-03-31');

        $response->assertOk();
        $response->assertHeader(
            'content-type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
    }

    public function test_owner_can_update_a_pending_demande(): void
    {
        $user = User::factory()->create();
        $demande = $this->createDemandeWithTrajet($user, 'Plateau', 'Cocody');

        $response = $this->actingAs($user)->put(route('demandes.update', $demande), [
            'trajets' => [
                [
                    'lieu_depart' => 'Godomey',
                    'lieu_arrivee' => 'Calavi',
                    'date_deplacement' => now()->addDays(3)->format('Y-m-d'),
                    'moyen_transport' => 'Moto',
                    'cout_estime' => 2000,
                ],
            ],
            'motif' => 'Mission modifiée',
        ]);

        $response->assertRedirect(route('dashboard'));

        $demande->refresh();
        $this->assertSame('Mission modifiée', $demande->motif);
        $this->assertSame('en_attente', $demande->statut);
        $this->assertSame('2000.00', (string) $demande->cout_estime);
        $this->assertCount(1, $demande->trajets);
        $this->assertSame('Godomey', $demande->trajets->first()->lieu_depart);
        $this->assertCount(1, $demande->historiques);
        $this->assertSame('en_attente', $demande->historiques->first()->statut);
    }

    public function test_other_users_cannot_update_a_demande(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $demande = $this->createDemandeWithTrajet($owner, 'Plateau', 'Cocody');

        $response = $this->actingAs($other)->put(route('demandes.update', $demande), [
            'trajets' => [[
                'lieu_depart' => 'X', 'lieu_arrivee' => 'Y',
                'date_deplacement' => now()->addDay()->format('Y-m-d'),
                'moyen_transport' => 'Taxi', 'cout_estime' => 1000,
            ]],
            'motif' => 'Piraté',
        ]);

        $response->assertForbidden();
        $this->assertSame('Mission', $demande->fresh()->motif);
    }

    public function test_a_validated_demande_cannot_be_updated(): void
    {
        $user = User::factory()->create();
        $demande = $this->createDemandeWithTrajet($user, 'Plateau', 'Cocody', DemandeTransport::STATUT_VALIDEE);

        $response = $this->actingAs($user)->put(route('demandes.update', $demande), [
            'trajets' => [[
                'lieu_depart' => 'X', 'lieu_arrivee' => 'Y',
                'date_deplacement' => now()->addDay()->format('Y-m-d'),
                'moyen_transport' => 'Taxi', 'cout_estime' => 1000,
            ]],
            'motif' => 'Trop tard',
        ]);

        $response->assertForbidden();
    }

    public function test_owner_can_cancel_a_pending_demande(): void
    {
        $user = User::factory()->create();
        $demande = $this->createDemandeWithTrajet($user, 'Plateau', 'Cocody');

        $response = $this->actingAs($user)->post(route('demandes.annuler', $demande));

        $response->assertRedirect(route('dashboard'));

        $demande->refresh();
        $this->assertSame('annulee', $demande->statut);
        $this->assertCount(1, $demande->historiques);
        $this->assertSame('annulee', $demande->historiques->first()->statut);
    }

    public function test_other_users_cannot_cancel_a_demande(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $demande = $this->createDemandeWithTrajet($owner, 'Plateau', 'Cocody');

        $response = $this->actingAs($other)->post(route('demandes.annuler', $demande));

        $response->assertForbidden();
        $this->assertSame('en_attente', $demande->fresh()->statut);
    }

    public function test_a_rejected_demande_cannot_be_cancelled(): void
    {
        $user = User::factory()->create();
        $demande = $this->createDemandeWithTrajet($user, 'Plateau', 'Cocody', DemandeTransport::STATUT_REJETEE);

        $response = $this->actingAs($user)->post(route('demandes.annuler', $demande));

        $response->assertForbidden();
    }

    private function createDemandeWithTrajet(
        User $user,
        string $depart,
        string $arrivee,
        string $statut = DemandeTransport::STATUT_EN_ATTENTE,
        array $extra = []
    ): DemandeTransport {
        $demande = $user->demandeTransports()->create([
            'motif' => 'Mission',
            'cout_estime' => 5000,
            'statut' => $statut,
            ...$extra,
        ]);

        $demande->trajets()->create([
            'lieu_depart' => $depart,
            'lieu_arrivee' => $arrivee,
            'date_deplacement' => now()->addDay(),
            'moyen_transport' => 'Taxi',
            'cout_estime' => 5000,
        ]);

        return $demande;
    }
}
