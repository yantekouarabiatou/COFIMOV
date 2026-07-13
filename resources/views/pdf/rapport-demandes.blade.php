

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>{{ $titre }}</title>

    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 9.5pt;
            color: #000;
            padding: 1.8cm 1.6cm;
            background: #fff;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #1B3A6B;
        }
        .header img {
            max-width: 200px;
            height: auto;
        }
        .header-title {
            text-align: right;
        }
        .header-title h1 {
            font-size: 13pt;
            color: #1B3A6B;
        }
        .header-title p {
            font-size: 9pt;
            color: #6B7280;
        }

        table.rapport {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        table.rapport th {
            text-align: left;
            padding: 6px 8px;
            border: 1px solid #cbd5e1;
            background: #f8fafc;
            color: #6B7280;
            font-size: 8pt;
            text-transform: uppercase;
        }
        table.rapport td {
            padding: 5px 8px;
            border: 1px solid #cbd5e1;
            font-size: 8.5pt;
        }
        table.rapport tfoot td {
            font-weight: bold;
            background: #f8fafc;
        }

        .badge {
            display: inline-block;
            padding: 1px 8px;
            border-radius: 8px;
            font-size: 7.5pt;
            font-weight: bold;
        }
        .badge-en_attente { background: #fef3c7; color: #b45309; }
        .badge-validee { background: #d1fae5; color: #047857; }
        .badge-rejetee { background: #fee2e2; color: #b91c1c; }

        .footer {
            margin-top: 1.5rem;
            font-size: 8pt;
            color: #94a3b8;
            text-align: center;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('logo_cofima_bon.jpg') }}" alt="Logo COFIMA">
        <div class="header-title">
            <h1>{{ $titre }}</h1>
            <p>Période du {{ $from->isoFormat('D MMMM YYYY') }} au {{ $to->isoFormat('D MMMM YYYY') }}</p>
        </div>
    </div>

    <table class="rapport">
        <thead>
            <tr>
                @if ($showCollaborateur)
                    <th>Collaborateur</th>
                @endif
                <th>Date</th>
                <th>Trajet</th>
                <th>Transport</th>
                <th>Statut</th>
                <th>Motif</th>
                <th>Coût</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($trajets as $trajet)
                @php $demande = $trajet->demandeTransport; @endphp
                <tr>
                    @if ($showCollaborateur)
                        <td>{{ $demande->user->full_name }}</td>
                    @endif
                    <td>{{ $trajet->date_deplacement->format('d/m/Y') }}</td>
                    <td>{{ $trajet->lieu_depart }} → {{ $trajet->lieu_arrivee }}</td>
                    <td>{{ $trajet->moyen_transport }}</td>
                    <td>
                        <span class="badge badge-{{ $demande->statut }}">
                            {{ match ($demande->statut) {
                                'en_attente' => 'En attente',
                                'validee' => 'Validée',
                                'rejetee' => 'Rejetée',
                            } }}
                        </span>
                    </td>
                    <td>{{ $demande->motif }}</td>
                    <td>{{ number_format($trajet->cout_estime, 0, ',', ' ') }} FCFA</td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $showCollaborateur ? 7 : 6 }}" style="text-align:center; color:#94a3b8; padding: 20px;">
                        Aucun trajet sur cette période.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if ($trajets->isNotEmpty())
            <tfoot>
                <tr>
                    <td colspan="{{ $showCollaborateur ? 6 : 5 }}">Total ({{ $trajets->count() }} trajet(s))</td>
                    <td>{{ number_format($trajets->sum('cout_estime'), 0, ',', ' ') }} FCFA</td>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        Document généré automatiquement le {{ now()->isoFormat('D MMMM YYYY à HH:mm') }} par le système de gestion des frais de transport COFIMA.
    </div>

</body>
</html>
