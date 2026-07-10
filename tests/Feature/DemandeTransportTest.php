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

    public function test_user_can_submit_a_demande_with_a_justificatif(): void
    {
        Mail::fake();
        Storage::fake('public');
        config(['cofima.email_dg' => ['dg@example.com']]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/demandes', [
            'lieu_depart' => 'Plateau',
            'lieu_arrivee' => 'Cocody',
            'date_deplacement' => now()->addDay()->format('Y-m-d'),
            'moyen_transport' => 'Taxi',
            'motif' => 'Mission client',
            'cout_estime' => 8500,
            'justificatif' => UploadedFile::fake()->image('recu.jpg'),
        ]);

        $response->assertRedirect(route('dashboard'));

        $demande = DemandeTransport::first();
        $this->assertNotNull($demande);
        $this->assertSame($user->id, $demande->user_id);
        $this->assertSame('en_attente', $demande->statut);
        $this->assertCount(1, $demande->justificatifs);
        $this->assertCount(1, $demande->historiques);

        Storage::disk('public')->assertExists($demande->justificatifs->first()->chemin);

        Mail::assertSent(DemandeTransportSoumise::class, function ($mail) use ($demande) {
            return $mail->demande->id === $demande->id
                && $mail->hasTo('dg@example.com')
                && count($mail->attachments()) === 1;
        });
    }

    public function test_demande_requires_mandatory_fields(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/demandes', []);

        $response->assertSessionHasErrors([
            'lieu_depart', 'lieu_arrivee', 'date_deplacement', 'moyen_transport', 'motif', 'cout_estime',
        ]);
    }

    public function test_dashboard_shows_historique_of_validated_and_rejected_demandes(): void
    {
        $user = User::factory()->create();
        $dg = User::factory()->create(['nom' => 'AVANDE', 'prenom' => 'Jean-Michel']);

        $validee = $user->demandeTransports()->create([
            'lieu_depart' => 'Plateau',
            'lieu_arrivee' => 'Cocody',
            'date_deplacement' => now()->addDay(),
            'moyen_transport' => 'Taxi',
            'motif' => 'Mission',
            'cout_estime' => 5000,
            'statut' => DemandeTransport::STATUT_VALIDEE,
            'valide_par' => $dg->id,
            'date_validation' => now(),
        ]);
        $validee->historiques()->create([
            'user_id' => $dg->id,
            'statut' => DemandeTransport::STATUT_VALIDEE,
            'commentaire' => 'Demande validée.',
        ]);

        $rejetee = $user->demandeTransports()->create([
            'lieu_depart' => 'Cotonou',
            'lieu_arrivee' => 'Porto-Novo',
            'date_deplacement' => now()->addDay(),
            'moyen_transport' => 'Moto',
            'motif' => 'Mission 2',
            'cout_estime' => 3000,
            'statut' => DemandeTransport::STATUT_REJETEE,
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
        $user->demandeTransports()->create([
            'lieu_depart' => 'Abomey',
            'lieu_arrivee' => 'Bohicon',
            'date_deplacement' => now()->addDay(),
            'moyen_transport' => 'Taxi',
            'motif' => 'Mission 3',
            'cout_estime' => 2000,
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
        ]);

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
        $demande = $this->createDemande($user);

        $response = $this->actingAs($user)->get(route('demandes.pdf', $demande));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_other_users_cannot_download_the_pdf_letter(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();
        $demande = $this->createDemande($user);

        $response = $this->actingAs($other)->get(route('demandes.pdf', $demande));

        $response->assertForbidden();
    }

    private function createDemande(User $user): DemandeTransport
    {
        return $user->demandeTransports()->create([
            'lieu_depart' => 'Plateau',
            'lieu_arrivee' => 'Cocody',
            'date_deplacement' => now()->addDay(),
            'moyen_transport' => 'Taxi',
            'motif' => 'Mission',
            'cout_estime' => 5000,
            'statut' => DemandeTransport::STATUT_EN_ATTENTE,
        ]);
    }
}
