<?php

namespace Tests\Feature;

use App\Mail\DemandeTransportTraitee;
use App\Models\DemandeTransport;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function directeurGeneral(): User
    {
        Role::findOrCreate('directeur-general', 'web');

        $dg = User::factory()->create();
        $dg->assignRole('directeur-general');

        return $dg;
    }

    private function createPendingDemande(User $collaborateur, string $depart = 'Plateau', string $arrivee = 'Cocody'): DemandeTransport
    {
        $demande = $collaborateur->demandeTransports()->create([
            'motif' => 'Mission',
            'cout_estime' => 5000,
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
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

    public function test_regular_user_cannot_access_validation_space(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/validation');

        $response->assertForbidden();
    }

    public function test_admin_can_access_validation_space(): void
    {
        Role::findOrCreate('admin', 'web');

        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/validation');

        $response->assertOk();
    }

    public function test_directeur_general_can_see_pending_demandes(): void
    {
        $dg = $this->directeurGeneral();
        $collaborateur = User::factory()->create();

        $this->createPendingDemande($collaborateur);

        $response = $this->actingAs($dg)->get('/validation');

        $response->assertOk();
        $response->assertSee($collaborateur->full_name);
        $response->assertSee('Plateau');
    }

    public function test_directeur_general_can_valider_a_demande(): void
    {
        Mail::fake();

        $dg = $this->directeurGeneral();
        $collaborateur = User::factory()->create();
        $demande = $this->createPendingDemande($collaborateur);

        $response = $this->actingAs($dg)->post("/validation/{$demande->id}/valider");

        $response->assertRedirect();
        $demande->refresh();
        $this->assertSame(DemandeTransport::STATUT_VALIDEE, $demande->statut);
        $this->assertSame($dg->id, $demande->valide_par);
        $this->assertNotNull($demande->date_validation);
        $this->assertCount(1, $demande->historiques);

        Mail::assertSent(DemandeTransportTraitee::class, function ($mail) use ($collaborateur) {
            return $mail->hasTo($collaborateur->email);
        });
    }

    public function test_directeur_general_can_rejeter_a_demande_with_a_reason(): void
    {
        Mail::fake();

        $dg = $this->directeurGeneral();
        $collaborateur = User::factory()->create();
        $demande = $this->createPendingDemande($collaborateur);

        $response = $this->actingAs($dg)->post("/validation/{$demande->id}/rejeter", [
            'motif_rejet' => 'Justificatif manquant.',
        ]);

        $response->assertRedirect();
        $demande->refresh();
        $this->assertSame(DemandeTransport::STATUT_REJETEE, $demande->statut);
        $this->assertSame('Justificatif manquant.', $demande->motif_rejet);

        Mail::assertSent(DemandeTransportTraitee::class);
    }

    public function test_rejeter_requires_a_reason(): void
    {
        $dg = $this->directeurGeneral();
        $collaborateur = User::factory()->create();
        $demande = $this->createPendingDemande($collaborateur);

        $response = $this->actingAs($dg)->post("/validation/{$demande->id}/rejeter", []);

        $response->assertSessionHasErrors('motif_rejet');
    }

    public function test_dg_can_export_all_demandes_as_pdf_for_a_period(): void
    {
        $dg = $this->directeurGeneral();
        $collaborateurA = User::factory()->create();
        $collaborateurB = User::factory()->create();

        $demandeA = $collaborateurA->demandeTransports()->create(['motif' => 'A', 'cout_estime' => 5000, 'statut' => 'en_attente']);
        $demandeA->trajets()->create([
            'lieu_depart' => 'Plateau', 'lieu_arrivee' => 'Cocody',
            'date_deplacement' => '2026-03-10', 'moyen_transport' => 'Taxi', 'cout_estime' => 5000,
        ]);

        $demandeB = $collaborateurB->demandeTransports()->create(['motif' => 'B', 'cout_estime' => 3000, 'statut' => 'validee']);
        $demandeB->trajets()->create([
            'lieu_depart' => 'Cotonou', 'lieu_arrivee' => 'Porto-Novo',
            'date_deplacement' => '2026-03-20', 'moyen_transport' => 'Moto', 'cout_estime' => 3000,
        ]);

        $response = $this->actingAs($dg)->get('/validation/export/pdf?from=2026-03-01&to=2026-03-31');

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_dg_can_export_all_demandes_as_excel_for_a_period(): void
    {
        $dg = $this->directeurGeneral();
        $collaborateur = User::factory()->create();
        $demande = $this->createPendingDemande($collaborateur);
        $demande->trajets()->first()->update(['date_deplacement' => now()]);

        $response = $this->actingAs($dg)->get('/validation/export/excel');

        $response->assertOk();
        $response->assertHeader(
            'content-type',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        );
    }

    public function test_regular_user_cannot_export_validation_reports(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/validation/export/pdf')->assertForbidden();
        $this->actingAs($user)->get('/validation/export/excel')->assertForbidden();
    }
}
