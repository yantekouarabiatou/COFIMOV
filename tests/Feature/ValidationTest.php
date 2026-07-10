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

        $demande = $collaborateur->demandeTransports()->create([
            'lieu_depart' => 'Plateau',
            'lieu_arrivee' => 'Cocody',
            'date_deplacement' => now()->addDay(),
            'moyen_transport' => 'Taxi',
            'motif' => 'Mission',
            'cout_estime' => 5000,
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
        ]);

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

        $demande = $collaborateur->demandeTransports()->create([
            'lieu_depart' => 'Plateau',
            'lieu_arrivee' => 'Cocody',
            'date_deplacement' => now()->addDay(),
            'moyen_transport' => 'Taxi',
            'motif' => 'Mission',
            'cout_estime' => 5000,
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
        ]);

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

        $demande = $collaborateur->demandeTransports()->create([
            'lieu_depart' => 'Plateau',
            'lieu_arrivee' => 'Cocody',
            'date_deplacement' => now()->addDay(),
            'moyen_transport' => 'Taxi',
            'motif' => 'Mission',
            'cout_estime' => 5000,
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
        ]);

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

        $demande = $collaborateur->demandeTransports()->create([
            'lieu_depart' => 'Plateau',
            'lieu_arrivee' => 'Cocody',
            'date_deplacement' => now()->addDay(),
            'moyen_transport' => 'Taxi',
            'motif' => 'Mission',
            'cout_estime' => 5000,
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
        ]);

        $response = $this->actingAs($dg)->post("/validation/{$demande->id}/rejeter", []);

        $response->assertSessionHasErrors('motif_rejet');
    }
}
