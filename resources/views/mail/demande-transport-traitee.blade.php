@extends('emails.layouts.email')

@php
    $estValidee = $demande->statut === 'validee';
@endphp

@section('title', $estValidee ? 'Demande de transport validée' : 'Demande de transport rejetée')

@section('content')

    {{-- ── Header ─────────────────────────────────────────────────────────── --}}
    <div style="background: linear-gradient(135deg, {{ $estValidee ? '#059669 0%, #10b981 100%' : '#b91c1c 0%, #dc2626 100%' }});
                border-radius: 8px 8px 0 0; padding: 28px 30px; text-align: center;">
        <h2 style="color:#fff; margin:0; font-size:1.2rem; font-weight:700;">
            {{ $estValidee ? 'Demande de transport validée' : 'Demande de transport rejetée' }}
        </h2>
        <p style="color:{{ $estValidee ? '#d1fae5' : '#fecaca' }}; margin:8px 0 0; font-size:.9rem;">
            Décision de la Direction Générale
        </p>
    </div>

    <div style="padding: 30px;">

        <p style="font-size:1rem; color:#333; margin-bottom:20px;">
            Bonjour {{ $demande->user->prenom }},
        </p>

        <p style="color:#555; line-height:1.8;">
            Votre demande de frais de transport
            @if ($demande->trajets->count() === 1)
                du <strong>{{ $demande->trajets->first()->date_deplacement->isoFormat('D MMMM YYYY') }}</strong>
                ({{ $demande->trajets->first()->lieu_depart }} → {{ $demande->trajets->first()->lieu_arrivee }})
            @else
                comportant {{ $demande->trajets->count() }} trajets
            @endif
            a été <strong>{{ $estValidee ? 'validée' : 'rejetée' }}</strong> par la Direction Générale.
        </p>

        @if ($estValidee)
            <div style="background:#d1fae5; border:1px solid #059669; border-radius:6px; padding:16px; margin:20px 0;">
                <p style="margin:0; color:#065f46; font-size:.9rem;">
                    <strong>🟢 Prochaine étape :</strong> La demande sera traité par le service comptable de COFIMA.
                </p>
            </div>
        @else
            <div style="background:#fee2e2; border:1px solid #dc2626; border-radius:6px; padding:16px; margin:20px 0;">
                <p style="margin:0; color:#991b1b; font-size:.9rem;">
                    <strong>🔴 Motif du rejet :</strong> {{ $demande->motif_rejet ?: 'Non précisé.' }}
                </p>
            </div>
        @endif

        {{-- ── Informations de la demande ────────────────────────────────── --}}
        <div style="background:#f8f9fa; border-left:4px solid {{ $estValidee ? '#059669' : '#dc2626' }}; padding:16px; margin:20px 0; border-radius:4px;">
            <h4 style="margin:0 0 12px; color:#333; font-size:0.95rem;">Détails de la demande :</h4>
            <table style="width:100%; font-size:0.9rem; color:#555;">
                <tr>
                    <td style="padding:6px 0;"><strong>Coût total :</strong></td>
                    <td style="padding:6px 0;"><strong style="color:#1B3A6B;">{{ number_format($demande->cout_estime, 0, ',', ' ') }} FCFA</strong></td>
                </tr>
                <tr>
                    <td style="padding:6px 0;"><strong>Statut :</strong></td>
                    <td style="padding:6px 0;">
                        <strong style="color:{{ $estValidee ? '#059669' : '#dc2626' }};">
                            {{ $estValidee ? 'Validée' : 'Rejetée' }}
                        </strong>
                    </td>
                </tr>
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
                </tbody>
            </table>
        </div>

        <div style="background:#e3f2fd; border:1px solid #2196f3; border-radius:6px; padding:16px; margin:20px 0;">
            <p style="margin:0; color:#1565c0; font-weight:600;">
                📎 La lettre de demande au format PDF, avec le statut à jour, est jointe à cet e-mail.
            </p>
        </div>

        <div style="text-align: center; margin: 30px 0 20px;">
            <a href="{{ route('dashboard') }}"
               style="display: inline-block; background: #1B3A6B; color: white; text-decoration: none; border-radius: 30px; padding: 12px 28px; font-weight: bold; font-size: 14px; border: none; cursor: pointer;">
                 Voir mes demandes
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


