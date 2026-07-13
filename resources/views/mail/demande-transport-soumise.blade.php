@extends('emails.layouts.email')

@section('title', 'Nouvelle demande de frais de transport')

@section('content')

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    <div style="background: linear-gradient(135deg, #1B3A6B 0%, #2A4F8A 100%);
                border-radius: 8px 8px 0 0; padding: 28px 30px; text-align: center;">
        <h2 style="color:#fff; margin:0; font-size:1.2rem; font-weight:700;">
            Demande de frais de transport en attente
        </h2>
        <p style="color:#cdd8ea; margin:8px 0 0; font-size:.9rem;">
            Validation requise du Directeur Général
        </p>
    </div>

    <div style="padding: 30px;">

        <p style="font-size:1rem; color:#333; margin-bottom:20px;">
            Madame, Monsieur le Directeur Général,
        </p>

        <p style="color:#555; line-height:1.8;">
            Une demande de <strong>de frais de transport</strong> de la part de
            <strong>{{ $demande->user->full_name }}</strong>
            est en attente de votre approbation depuis le {{ $demande->created_at->isoFormat('D MMMM YYYY à HH:mm') }}.
        </p>

        <div style="background:#fef3c7; border:1px solid #d97706; border-radius:6px; padding:16px; margin:20px 0;">
            <p style="margin:0; color:#92400e; font-size:.9rem;">
                <strong>🟠 Priorité :</strong> Cette demande nécessite votre validation ou votre rejet pour être finalisée.
            </p>
        </div>

        {{-- ── Informations de la demande ────────────────────────────────── --}}
        <div style="background:#f8f9fa; border-left:4px solid #1B3A6B; padding:16px; margin:20px 0; border-radius:4px;">
            <h4 style="margin:0 0 12px; color:#333; font-size:0.95rem;">Détails de la demande :</h4>
            <table style="width:100%; font-size:0.9rem; color:#555;">
                <tr>
                    <td style="padding:6px 0;"><strong>Collaborateur :</strong></td>
                    <td style="padding:6px 0;">{{ $demande->user->full_name }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;"><strong>Poste :</strong></td>
                    <td style="padding:6px 0;">{{ $demande->user->poste?->intitule ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0;"><strong>Motif :</strong></td>
                    <td style="padding:6px 0;">{{ $demande->motif }}</td>
                </tr>
                @if ($demande->commentaire)
                    <tr>
                        <td style="padding:6px 0;"><strong>Commentaire :</strong></td>
                        <td style="padding:6px 0;">{{ $demande->commentaire }}</td>
                    </tr>
                @endif
            </table>
        </div>

        <div style="margin: 16px 0;">
            <table style="width:100%; border-collapse: collapse; font-size:0.85rem;">
                <thead>
                    <tr style="background:#f1f5f9;">
                        <th style="padding:6px 8px; text-align:left; border:1px solid #e2e8f0; color:#6B7280; font-size:0.75rem; text-transform:uppercase;">Trajet</th>
                        <th style="padding:6px 8px; text-align:left; border:1px solid #e2e8f0; color:#6B7280; font-size:0.75rem; text-transform:uppercase;">Date</th>
                        <th style="padding:6px 8px; text-align:left; border:1px solid #e2e8f0; color:#6B7280; font-size:0.75rem; text-transform:uppercase;">Transport</th>
                        <th style="padding:6px 8px; text-align:right; border:1px solid #e2e8f0; color:#6B7280; font-size:0.75rem; text-transform:uppercase;">Coût</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($demande->trajets as $trajet)
                        <tr>
                            <td style="padding:6px 8px; border:1px solid #e2e8f0; color:#555;">{{ $trajet->lieu_depart }} → {{ $trajet->lieu_arrivee }}</td>
                            <td style="padding:6px 8px; border:1px solid #e2e8f0; color:#555;">{{ $trajet->date_deplacement->isoFormat('D MMMM YYYY') }}</td>
                            <td style="padding:6px 8px; border:1px solid #e2e8f0; color:#555;">{{ $trajet->moyen_transport }}</td>
                            <td style="padding:6px 8px; border:1px solid #e2e8f0; color:#555; text-align:right;">{{ number_format($trajet->cout_estime, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endforeach
                    <tr style="background:#f8f9fa;">
                        <td colspan="3" style="padding:6px 8px; border:1px solid #e2e8f0; font-weight:600; color:#333;">Total</td>
                        <td style="padding:6px 8px; border:1px solid #e2e8f0; font-weight:600; color:#1B3A6B; text-align:right;">{{ number_format($demande->cout_estime, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div style="background:#e3f2fd; border:1px solid #2196f3; border-radius:6px; padding:16px; margin:20px 0;">
            <p style="margin:0; color:#1565c0; font-weight:600;">
                📎 La lettre de demande au format PDF est jointe à cet e-mail pour consultation.
            </p>
        </div>

        <div style="text-align: center; margin: 30px 0 20px;">
            <a href="{{ route('validation.index') }}"
               style="display: inline-block; background: #1B3A6B; color: white; text-decoration: none; border-radius: 30px; padding: 12px 28px; font-weight: bold; font-size: 14px; border: none; cursor: pointer;">
                 Accéder aux demandes en attente
            </a>
        </div>

    </div>

    {{-- ── Footer ──────────────────────────────────────────────────────────── --}}
    <hr style="border:none; border-top:1px solid #ddd; margin:0;">
    <div style="text-align:center; padding:20px 30px; font-size:0.85rem; color:#888;">
        <p style="margin:10px 0;">
            Cet e-mail a été généré automatiquement par le système de gestion des frais de transport COFIMA.
        </p>
    </div>

@endsection
